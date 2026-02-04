<?php
namespace Quiz\Controller;

use Quiz\Service\Category as CategoryService;
use Quiz\Service\Quiz as QuizService;
use Quiz\Service\QuizRoundQuestion as QuizRoundQuestionService;
use Quiz\Service\QuizLog as QuizLogService;
use Quiz\Entity\Quiz as QuizEntity;
use Quiz\Form\Quiz as QuizForm;
use Zend\Form\FormInterface;
use Zend\View\Model\ViewModel;

class QuizController extends AbstractCrudController
{
    /** @var QuizService  */
    protected $quizService;

    /** @var QuizRoundQuestionService  */
    protected $quizRoundQuestionService;

    /** @var QuizLogService  */
    protected $quizLogService;

    /** @var CategoryService */
    protected $categoryService;

    /** @var QuizForm  */
    protected $quizForm;

    /**
     * @param QuizService $quizService
     * @param QuizRoundQuestionService $quizRoundQuestionService
     * @param QuizLogService $quizLogService
     * @param CategoryService $categoryService
     * @param QuizForm $quizForm
     */
    public function __construct(
        QuizService $quizService,
        QuizRoundQuestionService $quizRoundQuestionService,
        QuizLogService $quizLogService,
        CategoryService $categoryService,
        QuizForm $quizForm
    ) {
        parent::__construct();

        $this->quizService = $quizService;
        $this->quizRoundQuestionService = $quizRoundQuestionService;
        $this->quizLogService = $quizLogService;
        $this->categoryService = $categoryService;
        $this->quizForm = $quizForm;
    }

    public function indexAction()
    {
        $splitQuizzes = $this->quizService->getSplitQuizzesByDate();

        return new ViewModel(
            [
                'futureQuizzes' => $splitQuizzes['future'],
                'pastQuizzes' => $splitQuizzes['past']
            ]
        );
    }

    public function detailAction()
    {
        $quizId = $this->params('quizId');

        $quiz = $this->quizService->getQuizById($quizId);

        $labels = [];
        $data = [];

        $cats = $this->categoryService->getAllCategories();
        foreach ($cats as $cat) {
            $data[$cat->getId()] = 0;
            $labels[$cat->getId()] = $cat->getName();
        }

        foreach ($quiz->getQuizRounds() as $round) {
            if (strtolower($round->getTheme()) == "muziek ronde" || strtolower($round->getTheme()) == "muziekronde") {
                continue;
            }
            foreach ($round->getQuizRoundQuestions() as $question) {
                $cat = $question->getQuestion()->getCategory();
                $data[$cat->getId()] += $question->getQuestion()->getPoints();
            }
        }

        return new ViewModel(
            [
                'quiz' => $quiz,
                'labels' => $labels,
                'data' => $data
            ]
        );
    }

    public function nextQuizAction()
    {
        $nextQuiz = $this->quizService->findNextQuiz();

        if (is_null($nextQuiz)) {
            return $this->redirect()->toRoute("quiz");
        }

        return $this->redirect()->toRoute('quiz/detail', ['quizId' => $nextQuiz->getId()]);
    }

    public function resetQuizRoundQuestionNumberAction()
    {
        $quizRoundQuestionId = $this->params('quizRoundQuestionId');
        $newPosition = $this->params('newPosition');

        $quizRoundQuestion = $this->quizRoundQuestionService->getById($quizRoundQuestionId);

        $this->quizRoundQuestionService->resetQuestionNumber($quizRoundQuestion, $newPosition);
        $this->quizLogService->createNewQuestionChangedLog($quizRoundQuestion);

        die();
    }

    public function removeQuizRoundQuestionAction()
    {
        $quizRoundQuestionId = $this->params('quizRoundQuestionId');
        $quizRoundQuestion = $this->quizRoundQuestionService->getById($quizRoundQuestionId);
        $quizId = $quizRoundQuestion->getQuizRound()->getQuiz()->getId();

        $this->quizRoundQuestionService->remove($quizRoundQuestion);
        $this->quizLogService->createNewQuestionRemovedLog($quizRoundQuestion);

        return $this->redirect()->toRoute('quiz/detail', ['quizId' => $quizId]);
    }

    public function printQuestionsAction()
    {
        $this->layout('print/layout');

        $quizId = $this->params('quizId');

        $quiz = $this->quizService->getQuizById($quizId);

        return new ViewModel(
            [
                'quiz' => $quiz
            ]
        );
    }

    public function printPhotosAction()
    {
        $this->layout('print/layout');

        $quizId = $this->params('quizId');

        $quiz = $this->quizService->getQuizById($quizId);
        $quizRound = $quiz->getPhotoRound();

        // Collect photo dimensions in database order (no resorting)
        $photos = [];
        foreach ($quizRound->getQuizRoundQuestions() as $quizRoundQuestion) {
            $question = $quizRoundQuestion->getQuestion();
            if ($question->isImageQuestion()) {
                $imagePath = 'data/images/' . $question->getId() . '.png';
                if (file_exists($imagePath)) {
                    $imageSize = getimagesize($imagePath);
                    if ($imageSize) {
                        $photos[] = [
                            'question' => $question,
                            'questionNumber' => $quizRoundQuestion->getQuestionNumber(),
                            'quizRoundQuestion' => $quizRoundQuestion,
                            'quizRoundQuestionId' => $quizRoundQuestion->getId(),
                            'width' => $imageSize[0],
                            'height' => $imageSize[1],
                        ];
                    }
                }
            }
        }

        // Distribute photos across two rows without changing order
        // Just split them in half for display
        $halfCount = ceil(count($photos) / 2);
        $row1 = array_slice($photos, 0, $halfCount);
        $row2 = array_slice($photos, $halfCount);
        
        // Assign display numbers sequentially
        $displayNumber = 1;
        foreach ($row1 as &$photo) {
            $photo['displayNumber'] = $displayNumber++;
        }
        foreach ($row2 as &$photo) {
            $photo['displayNumber'] = $displayNumber++;
        }
        
        $rows = [$row1, $row2];

        return new ViewModel(
            [
                'quizRound' => $quizRound,
                'photos' => array_merge($row1, $row2),
                'rows' => $rows
            ]
        );
    }

    public function printPhotosOptimizedAction()
    {
        $this->layout('print/layout');

        $quizId = $this->params('quizId');

        $quiz = $this->quizService->getQuizById($quizId);
        $quizRound = $quiz->getPhotoRound();

        // Collect photo dimensions
        $photos = [];
        foreach ($quizRound->getQuizRoundQuestions() as $quizRoundQuestion) {
            $question = $quizRoundQuestion->getQuestion();
            if ($question->isImageQuestion()) {
                $imagePath = 'data/images/' . $question->getId() . '.png';
                if (file_exists($imagePath)) {
                    $imageSize = getimagesize($imagePath);
                    if ($imageSize) {
                        $photos[] = [
                            'question' => $question,
                            'questionNumber' => $quizRoundQuestion->getQuestionNumber(),
                            'quizRoundQuestion' => $quizRoundQuestion,
                            'quizRoundQuestionId' => $quizRoundQuestion->getId(),
                            'width' => $imageSize[0],
                            'height' => $imageSize[1],
                        ];
                    }
                }
            }
        }

        // Distribute photos across two rows and assign display numbers
        $rows = $this->distributePhotos($photos);

        // Automatically save the optimized order to database
        $allPhotos = [];
        $updates = [];
        
        foreach ($rows as $row) {
            foreach ($row as $photo) {
                $quizRoundQuestion = $photo['quizRoundQuestion'];
                $oldNumber = $quizRoundQuestion->getQuestionNumber();
                $newNumber = $photo['displayNumber'];
                
                // Collect updates for batch processing
                if ($oldNumber != ($newNumber * 10)) {
                    $updates[] = [
                        'quizRoundQuestion' => $quizRoundQuestion,
                        'newPosition' => $newNumber
                    ];
                }
                
                // Collect all photos with displayNumber for the view
                $allPhotos[] = $photo;
            }
        }
        
        // Perform batch update
        if (!empty($updates)) {
            $this->quizRoundQuestionService->batchUpdateQuestionNumbers($updates);
            
            // Log changes
            foreach ($updates as $update) {
                $this->quizLogService->createNewQuestionChangedLog($update['quizRoundQuestion']);
            }
        }

        return new ViewModel(
            [
                'quizRound' => $quizRound,
                'photos' => $allPhotos,
                'rows' => $rows
            ]
        );
    }

    /**
     * Distribute photos across two rows optimally based on aspect ratios
     * and assign display numbers from left to right, top to bottom
     * 
     * @param array $photos
     * @return array [row1, row2] with displayNumber added to each photo
     */
    private function distributePhotos($photos)
    {
        $totalRatio = 0;
        foreach ($photos as $photo) {
            $totalRatio += $photo['width'] / $photo['height'];
        }
        $targetRatio = $totalRatio / 2;
        
        $row1 = [];
        $row2 = [];
        $row1Ratio = 0;
        
        // Sort by aspect ratio descending for better distribution
        $sorted = $photos;
        usort($sorted, function($a, $b) {
            $ratioA = $a['width'] / $a['height'];
            $ratioB = $b['width'] / $b['height'];
            return $ratioB <=> $ratioA;
        });
        
        foreach ($sorted as $photo) {
            $aspectRatio = $photo['width'] / $photo['height'];
            if ($row1Ratio < $targetRatio && count($row1) < ceil(count($photos) / 2)) {
                $row1[] = $photo;
                $row1Ratio += $aspectRatio;
            } else {
                $row2[] = $photo;
            }
        }
        
        // Sort rows back by question number for consistent display
        usort($row1, function($a, $b) {
            return $a['questionNumber'] <=> $b['questionNumber'];
        });
        usort($row2, function($a, $b) {
            return $a['questionNumber'] <=> $b['questionNumber'];
        });
        
        // Assign display numbers from left to right, top to bottom
        $displayNumber = 1;
        foreach ($row1 as &$photo) {
            $photo['displayNumber'] = $displayNumber++;
        }
        foreach ($row2 as &$photo) {
            $photo['displayNumber'] = $displayNumber++;
        }
        
        return [$row1, $row2];
    }

    public function printPhotosOptimizedA3Action()
    {
        $this->layout('print/layout');

        $quizId = $this->params('quizId');

        $quiz = $this->quizService->getQuizById($quizId);
        $quizRound = $quiz->getPhotoRound();

        return new ViewModel(
            [
                'quizRound' => $quizRound
            ]
        );
    }

    public function saveOptimizedPhotoOrderAction()
    {
        $quizId = $this->params('quizId');

        $quiz = $this->quizService->getQuizById($quizId);
        $quizRound = $quiz->getPhotoRound();

        // Collect photo dimensions
        $photos = [];
        foreach ($quizRound->getQuizRoundQuestions() as $quizRoundQuestion) {
            $question = $quizRoundQuestion->getQuestion();
            if ($question->isImageQuestion()) {
                $imagePath = 'data/images/' . $question->getId() . '.png';
                if (file_exists($imagePath)) {
                    $imageSize = getimagesize($imagePath);
                    if ($imageSize) {
                        $photos[] = [
                            'question' => $question,
                            'questionNumber' => $quizRoundQuestion->getQuestionNumber(),
                            'quizRoundQuestion' => $quizRoundQuestion,
                            'width' => $imageSize[0],
                            'height' => $imageSize[1],
                        ];
                    }
                }
            }
        }

        // Get optimized distribution
        $rows = $this->distributePhotos($photos);

        // Update question numbers in database
        foreach ($rows as $row) {
            foreach ($row as $photo) {
                $quizRoundQuestion = $photo['quizRoundQuestion'];
                $this->quizRoundQuestionService->resetQuestionNumber(
                    $quizRoundQuestion,
                    $photo['displayNumber']
                );
                $this->quizLogService->createNewQuestionChangedLog($quizRoundQuestion);
            }
        }

        // Redirect back to detail page
        return $this->redirect()->toRoute('quiz/detail', ['quizId' => $quizId]);
    }

    public function teamsAction()
    {
        $quizId = $this->params('quizId');
        $quiz = $this->quizService->getQuizById($quizId);

        $teams = [];
        if ($quiz->getTeams()) {
            foreach ($quiz->getTeams() as $team) {
                if (!$team->isCanceled()) {
                    $teams[] = $team;
                }
            }
        }

        return new ViewModel(
            [
                'quiz' => $quiz,
                'teams' => $teams
            ]
        );
    }

    public function addRoundAction()
    {
        $quizId = $this->params('quizId');
        $quiz = $this->quizService->getQuizById($quizId);

        // Voeg een nieuwe ronde toe
        $this->quizService->addNewRound($quiz);

        // Redirect terug naar detail pagina
        return $this->redirect()->toRoute('quiz/detail', ['quizId' => $quizId]);
    }

    public function printAnswersAction()
    {
        $this->layout('print/layout');

        $quizId = $this->params('quizId');

        $quiz = $this->quizService->getQuizById($quizId);

        return new ViewModel(
            [
                'quiz' => $quiz
            ]
        );
    }

    /**
     * Override processAction to handle disabled template field
     */
    public function processAction()
    {
        /* @var $request \Zend\Http\PhpEnvironment\Request */
        $request = $this->getRequest();
        
        // Check if this is an update (id in query string)
        $quizId = $request->getQuery('id');
        
        // If updating existing quiz, restore template value (field is disabled so not submitted)
        if ($quizId) {
            $existingQuiz = $this->quizService->getById($quizId);
            if ($existingQuiz) {
                $postData = $request->getPost();
                $postData->set(QuizForm::ELEM_TEMPLATE, $existingQuiz->getTemplate());
            }
        }
        
        // Call parent processAction
        return parent::processAction();
    }

    /**
     * @param FormInterface $form
     * @return mixed
     */
    protected function processFormData(FormInterface $form)
    {
        /** @var \Quiz\Entity\Quiz $quiz */
        $quiz = $form->getObject();

        if (!$quiz->getId()) {
            $this->quizService->createQuiz($quiz);
        } else {
            $this->quizService->persist($quiz);
        }

        return true;
    }

    /**
     * @return FormInterface
     */
    protected function getCrudForm()
    {
        /* @var $request \Zend\Http\PhpEnvironment\Request */
        $request = $this->getRequest();

        if ($request->getQuery('id')) {
            $question = $this->quizService->getById($request->getQuery('id'));
        }

        if (empty($question)) {
            $question = new QuizEntity();
        }

        $this->quizForm->bind($question);

        return $this->quizForm;
    }

    protected function getCrudSuccessResponse()
    {
        return $this->redirect()->toRoute('quiz');
    }

    protected function getCrudFailureResponse()
    {
        /* @var $request \Zend\Http\PhpEnvironment\Request */
        $request = $this->getRequest();
        
        $params = ['action' => 'form'];
        if ($request->getQuery('id')) {
            $params['id'] = $request->getQuery('id');
        }
        
        return $this->redirect()->toRoute('quiz/form', [], ['query' => $params]);
    }
}
