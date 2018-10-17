<?php
namespace Quiz\Controller;

use Quiz\Entity\ThemeRoundQuestion;
use Quiz\Service\ThemeRoundQuestionService;
use Quiz\Service\ThemeRoundService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ThemeRoundController extends AbstractActionController
{
    /** @var ThemeRoundService  */
    protected $themeRoundService;

    /** @var ThemeRoundQuestionService */
    protected $themeRoundQuestionService;

    /**
     * @param ThemeRoundService $themeRoundService
     * @param ThemeRoundQuestionService $themeRoundQuestionService
     */
    public function __construct(
        ThemeRoundService $themeRoundService,
        ThemeRoundQuestionService $themeRoundQuestionService
    ) {
        $this->themeRoundService = $themeRoundService;
        $this->themeRoundQuestionService = $themeRoundQuestionService;
    }

    public function indexAction()
    {
        $themeRounds = $this->themeRoundService->getThemeRounds();

        return new ViewModel(
            [
                'themeRounds' => $themeRounds
            ]
        );
    }

    public function detailAction()
    {
        $themeRoundId = $this->params('themeRoundId');

        $themeRound = $this->themeRoundService->getThemeRoundById($themeRoundId);

        return new ViewModel(
            [
                'themeRound' => $themeRound
            ]
        );
    }

    /**
     * @return \Zend\Http\Response
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function removeThemeRoundQuestionAction()
    {
        $themeRoundQuestionId = $this->params('themeRoundQuestionId');
        /** @var ThemeRoundQuestion $themeRoundQuestion */
        $themeRoundQuestion = $this->themeRoundQuestionService->getById($themeRoundQuestionId);
        $themeRoundId = $themeRoundQuestion->getThemeRound()->getId();

        $this->themeRoundQuestionService->remove($themeRoundQuestion);

        return $this->redirect()->toRoute('theme-rounds/detail', ['themeRoundId' => $themeRoundId]);
    }

    public function resetThemeRoundQuestionNumberAction()
    {
        $themeRoundQuestionId = $this->params('themeRoundQuestionId');
        $newPosition = $this->params('newPosition');

        /** @var ThemeRoundQuestion $themeRoundQuestion */
        $themeRoundQuestion = $this->themeRoundQuestionService->getById($themeRoundQuestionId);

        $this->themeRoundQuestionService->resetQuestionNumber($themeRoundQuestion, $newPosition);

        die();
    }
}