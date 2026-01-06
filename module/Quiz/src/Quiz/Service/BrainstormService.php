<?php
namespace Quiz\Service;

use Quiz\Service\Category as CategoryService;
use Zend\Http\Client as HttpClient;
use Zend\Http\Request;

class BrainstormService
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var CategoryService
     */
    protected $categoryService;

    /**
     * @param array $config OpenRouter configuration
     * @param CategoryService $categoryService
     */
    public function __construct(array $config, CategoryService $categoryService)
    {
        $this->config = $config;
        $this->categoryService = $categoryService;
    }

    /**
     * Generate quiz questions from a webpage URL
     *
     * @param string $url
     * @param int $numQuestions Number of questions to generate (1-10)
     * @return array Array of questions with structure: [['question' => '...', 'answer' => '...', 'category' => '...', 'category_id' => 1]]
     * @throws \Exception
     */
    public function generateQuestions($url, $numQuestions = 5)
    {
        // Increase PHP execution time limit for LLM API calls (2 calls can take time)
        set_time_limit(120);
        
        // Validate number of questions
        $numQuestions = max(1, min(10, (int)$numQuestions));
        
        // Fetch webpage content
        $content = $this->fetchWebpageContent($url);
        
        if (empty($content)) {
            throw new \Exception('Could not fetch content from URL');
        }

        // Clean and filter content using LLM before generating questions
        $cleanedContent = $this->cleanAndSummarizeContent($content);
        
        if (empty($cleanedContent)) {
            throw new \Exception('Could not extract relevant content from webpage');
        }

        // Call OpenRouter API with cleaned content
        $questions = $this->callOpenRouterApi($cleanedContent, $url, $numQuestions);

        return $questions;
    }

    /**
     * Fetch content from a webpage URL
     *
     * @param string $url
     * @return string
     * @throws \Exception
     */
    protected function fetchWebpageContent($url)
    {
        try {
            // Auto-add ?action=raw for wiki pages to get cleaner content
            $url = $this->prepareWikiUrl($url);
            
            $client = new HttpClient();
            $client->setUri($url);
            $client->setMethod(Request::METHOD_GET);
            $client->setOptions([
                'timeout' => 30,
                'useragent' => 'Mozilla/5.0 (compatible; QuizBot/1.0)',
            ]);

            $response = $client->send();

            if (!$response->isSuccess()) {
                throw new \Exception('HTTP request failed with status: ' . $response->getStatusCode());
            }

            $html = $response->getBody();
            
            // Strip HTML tags to get plain text
            $text = strip_tags($html);
            
            // Remove extra whitespace
            $text = preg_replace('/\s+/', ' ', $text);
            $text = trim($text);
            
            // Limit content length to avoid token limits (approximately 8000 characters)
            if (strlen($text) > 8000) {
                $text = substr($text, 0, 8000) . '...';
            }

            return $text;
        } catch (\Exception $e) {
            throw new \Exception('Failed to fetch webpage: ' . $e->getMessage());
        }
    }

    /**
     * Prepare wiki URL by adding ?action=raw parameter
     *
     * @param string $url
     * @return string
     */
    protected function prepareWikiUrl($url)
    {
        // Check if this is a wiki page (Wikipedia or MediaWiki)
        if (stripos($url, 'wikipedia.org') !== false || stripos($url, '/wiki/') !== false) {
            // Check if URL already has query parameters
            if (strpos($url, '?') !== false) {
                // Append with &
                if (stripos($url, 'action=raw') === false) {
                    $url .= '&action=raw';
                }
            } else {
                // Add new query parameter
                if (stripos($url, 'action=raw') === false) {
                    $url .= '?action=raw';
                }
            }
        }
        
        return $url;
    }

    /**
     * Clean and summarize content using LLM to remove navigation, metadata, etc.
     *
     * @param string $content
     * @return string
     * @throws \Exception
     */
    protected function cleanAndSummarizeContent($content)
    {
        $prompt = <<<PROMPT
Je bent een content curator. Je taak is om de volgende webpagina content op te schonen en samen te vatten, waarbij je alleen de relevante feitelijke informatie behoudt.

ORIGINELE CONTENT:
{$content}

INSTRUCTIES:
1. Verwijder alle navigatie-elementen, menu's, headers, footers, en sidebar informatie
2. Verwijder metadata zoals "Laatste bewerking op...", "Bronnen:", "Zie ook:", "Navigatie", etc.
3. Verwijder doorverwijzingen naar andere pagina's of artikelen
4. Verwijder disclaimers, copyright informatie, en juridische teksten
5. Behoud ALLEEN de hoofdinhoud met feitelijke informatie over het onderwerp
6. Vat de inhoud licht samen als deze erg lang is (max ~2000 woorden), maar behoud alle belangrijke feiten en details
7. Behoud de originele taal van de content

OUTPUT:
Geef alleen de opgeschoonde content terug als platte tekst, zonder extra uitleg of opmaak.
PROMPT;

        $requestBody = [
            'model' => $this->config['model'],
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'temperature' => 0.3, // Lower temperature for more consistent cleaning
        ];

        try {
            $client = new HttpClient();
            $client->setUri($this->config['api_url']);
            $client->setMethod(Request::METHOD_POST);
            $client->setHeaders([
                'Authorization' => 'Bearer ' . $this->config['api_key'],
                'Content-Type' => 'application/json',
                'HTTP-Referer' => 'https://admin.quizis.nl',
                'X-Title' => 'Quizis Admin',
            ]);
            $client->setRawBody(json_encode($requestBody));
            $client->setOptions([
                'timeout' => 45,
            ]);

            $response = $client->send();

            if (!$response->isSuccess()) {
                // If cleaning fails, return original content as fallback
                return $content;
            }

            $responseData = json_decode($response->getBody(), true);

            if (!isset($responseData['choices'][0]['message']['content'])) {
                // If response is invalid, return original content as fallback
                return $content;
            }

            $cleanedContent = trim($responseData['choices'][0]['message']['content']);
            
            // Return cleaned content if it's not empty, otherwise return original
            return !empty($cleanedContent) ? $cleanedContent : $content;
        } catch (\Exception $e) {
            // If something goes wrong, return original content as fallback
            return $content;
        }
    }

    /**
     * Call OpenRouter API to generate questions
     *
     * @param string $content
     * @param string $sourceUrl
     * @param int $numQuestions
     * @return array
     * @throws \Exception
     */
    protected function callOpenRouterApi($content, $sourceUrl, $numQuestions)
    {
        $categories = $this->categoryService->getAllCategories();
        $prompt = $this->buildPrompt($content, $categories, $numQuestions);

        $requestBody = [
            'model' => $this->config['model'],
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'response_format' => ['type' => 'json_object'],
            'temperature' => 0.7,
        ];

        try {
            $client = new HttpClient();
            $client->setUri($this->config['api_url']);
            $client->setMethod(Request::METHOD_POST);
            $client->setHeaders([
                'Authorization' => 'Bearer ' . $this->config['api_key'],
                'Content-Type' => 'application/json',
                'HTTP-Referer' => 'https://admin.quizis.nl',
                'X-Title' => 'Quizis Admin',
            ]);
            $client->setRawBody(json_encode($requestBody));
            $client->setOptions([
                'timeout' => 45,
            ]);

            $response = $client->send();

            if (!$response->isSuccess()) {
                $errorBody = $response->getBody();
                throw new \Exception('OpenRouter API request failed: ' . $response->getStatusCode() . ' - ' . $errorBody);
            }

            $responseData = json_decode($response->getBody(), true);

            if (!isset($responseData['choices'][0]['message']['content'])) {
                throw new \Exception('Invalid response format from OpenRouter API');
            }

            $content = $responseData['choices'][0]['message']['content'];
            $questionsData = json_decode($content, true);

            if (!isset($questionsData['questions']) || !is_array($questionsData['questions'])) {
                throw new \Exception('Invalid questions format in API response');
            }

            // Add source URL to each question
            foreach ($questionsData['questions'] as &$question) {
                if (!isset($question['source']) || empty($question['source'])) {
                    $question['source'] = $sourceUrl;
                }
            }

            return $questionsData['questions'];
        } catch (\Exception $e) {
            throw new \Exception('OpenRouter API call failed: ' . $e->getMessage());
        }
    }

    /**
     * Build the prompt for the LLM
     *
     * @param string $content
     * @param array $categories
     * @param int $numQuestions
     * @return string
     */
    protected function buildPrompt($content, array $categories, $numQuestions)
    {
        // Build categories list for prompt
        $categoriesList = "";
        foreach ($categories as $category) {
            $categoriesList .= "- ID {$category->getId()}: {$category->getName()}\n";
        }

        // Build example questions array for JSON format
        $exampleQuestions = [];
        for ($i = 0; $i < min(3, $numQuestions); $i++) {
            $exampleQuestions[] = <<<JSON
    {
      "question": "Vraag tekst hier",
      "answer": "Antwoord tekst hier",
      "category": "Naam van de gekozen categorie",
      "category_id": 1
    }
JSON;
        }
        $exampleJson = implode(",\n", $exampleQuestions);

        return <<<PROMPT
Je bent een expert quizmaster. Analyseer de volgende webpagina content en genereer precies {$numQuestions} open quizvragen (open-ended questions).

WEBPAGINA CONTENT:
{$content}

INSTRUCTIES:
1. Genereer precies {$numQuestions} quizvragen
2. Elke vraag moet een open vraag zijn (geen multiple choice)
3. Elke vraag moet beantwoordbaar zijn op basis van de content
4. Het antwoord moet kort en bondig zijn
5. Varieer in moeilijkheidsgraad (makkelijk tot moeilijk)
6. Kies voor elke vraag de meest passende categorie uit de bovenstaande lijst
7. Gebruik het category_id van de gekozen categorie

BESCHIKBARE CATEGORIEËN:
{$categoriesList}

RESPONSE FORMAT (JSON):
Geef je antwoord als een JSON object met de volgende structuur:
{
  "questions": [
{$exampleJson}
  ]
}

Genereer nu de {$numQuestions} quizvragen in het opgegeven JSON formaat.
PROMPT;
    }
}
