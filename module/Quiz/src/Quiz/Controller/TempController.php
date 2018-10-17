<?php
namespace Quiz\Controller;

use Quiz\Entity\QuizRound;
use Quiz\Entity\ThemeRound;
use Quiz\Service\ThemeRoundService;
use Quiz\Service\QuizRound as QuizRoundService;
use Zend\Mvc\Controller\AbstractActionController;

class TempController extends AbstractActionController
{
    /** @var ThemeRoundService */
    protected $themeRoundService;

    /** @var QuizRoundService */
    protected $quizRoundService;

    /**
     * TempController constructor.
     * @param ThemeRoundService $themeRoundService
     * @param QuizRoundService $quizRoundService
     */
    public function __construct(ThemeRoundService $themeRoundService, QuizRoundService $quizRoundService)
    {
        $this->themeRoundService = $themeRoundService;
        $this->quizRoundService = $quizRoundService;
    }

    public function importAction()
    {
        $quizRoundIds = [];

        foreach ($quizRoundIds as $quizRoundId) {
            /** @var QuizRound $quizRound */
            $quizRound = $this->quizRoundService->getById($quizRoundId);

            $themeRound = new ThemeRound();
            $themeRound->setName($quizRound->getTheme());

            $themeRound = $this->themeRoundService->createThemeRound($themeRound);

            foreach ($quizRound->getQuizRoundQuestions() as $quizRoundQuestion) {
                $this->themeRoundService->addQuestionToThemeRound(
                    $quizRoundQuestion->getQuestion(),
                    $themeRound
                );
            }
        }

        die('done');
    }
}