<?php
namespace Quiz\Controller;

use Quiz\Entity\QuizRound as QuizRoundEntity;
use Quiz\Service\QuizRound as QuizRoundService;
use Zend\Mvc\Controller\AbstractActionController;

class QuizRoundController extends AbstractActionController
{
    /** @var QuizRoundService  */
    protected $quizRoundService;

    /**
     * @param QuizRoundService $quizRoundService
     */
    public function __construct(
        QuizRoundService $quizRoundService
    ) {
        $this->quizRoundService = $quizRoundService;
    }

    public function downloadMp3Action()
    {
        $silenceMp3 = file_get_contents("./data/mp3/silence.mp3");

        $quizRoundId = $this->params('quizRoundId');
        /** @var QuizRoundEntity $quizRound */
        $quizRound = $this->quizRoundService->getById($quizRoundId);

        $mp3File = "";
        foreach ($quizRound->getQuizRoundQuestions() as $quizRoundQuestion) {
            if ($quizRoundQuestion->getQuestion()->hasAudio()) {
                $silence = !empty($mp3File) ? $silenceMp3 : "";
                $mp3File = $mp3File . $silence . $quizRoundQuestion->getQuestion()->getPureAudio();
            }
        }

        $response = $this->getResponse();
        $response->setContent($mp3File);

        $quizName = $quizRound->getQuiz()->getName();
        $quizName = strtolower(str_replace(" ", "_", $quizName));

        $headers = $response->getHeaders();
        $headers->clearHeaders()
            ->addHeaderLine('Content-Type', 'audio/mpeg')
            ->addHeaderLine('Content-Disposition', 'attachment; filename="audio_ronde_'.$quizRound->getNumber().'.mp3"')
            ->addHeaderLine('Content-Length', strlen($mp3File));


        return $this->response;
    }
}
