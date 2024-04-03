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
            if ($quizRoundQuestion->getQuestion()->isAudioQuestion()) {
                $silence = !empty($mp3File) ? $silenceMp3 : "";
                $content = file_get_contents("data/audio/" . $quizRoundQuestion->getQuestion()->getId() . ".mp3");
                $mp3File = $mp3File . $silence . $content;
            }
        }

        $response = $this->getResponse();
        $response->setContent($mp3File);

        $quizName = $quizRound->getQuiz()->getName();
        $quizName = strtolower(str_replace(" ", "_", $quizName));

        $headers = $response->getHeaders();
        $headers->clearHeaders()
            ->addHeaderLine('Content-Type', 'audio/mpeg')
            ->addHeaderLine('Content-Disposition', 'attachment; filename="audio_ronde_' . $quizRound->getNumber() . '.mp3"')
            ->addHeaderLine('Content-Length', strlen($mp3File));


        return $this->response;
    }

    public function downloadMp3V2Action()
    {
        $quizRoundId = $this->params('quizRoundId');
        /** @var QuizRoundEntity $quizRound */
        $quizRound = $this->quizRoundService->getById($quizRoundId);

        $mp3Files = [];
        $hash = [];
        foreach ($quizRound->getQuizRoundQuestions() as $quizRoundQuestion) {
            if ($quizRoundQuestion->getQuestion()->isAudioQuestion()) {
                $mp3Files[] = "data/audio/" . $quizRoundQuestion->getQuestion()->getId() . ".mp3";
                $mp3Files[] = "data/mp3/silence.mp3";
                $hash[] = $quizRoundQuestion->getQuestion()->getId() . $quizRoundQuestion->getQuestion()->getDateUpdated()->format("YmdHis");
            }
        }

        $hash = md5(implode("|", $mp3Files));

        $outputFile = "data/mp3/q" . $quizRound->getQuiz()->getId() . "r" . $quizRound->getNumber() . "_" . $hash . ".mp3";

        $command = "/usr/bin/ffmpeg -i \"concat:" . implode("|", $mp3Files) . "\" -c copy $outputFile 2>&1";

        if (!file_exists($outputFile)) {
            echo shell_exec($command);
            die("stop!");
        }

        $this->response->setContent(file_get_contents($outputFile));
        return $this->response;
    }
}
