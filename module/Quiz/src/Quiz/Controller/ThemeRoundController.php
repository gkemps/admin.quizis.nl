<?php
namespace Quiz\Controller;

use Quiz\Entity\ThemeRound as ThemeRoundEntity;
use Quiz\Entity\QuizRound as QuizRoundEntity;
use Quiz\Form\ThemeRound as ThemeRoundForm;
use Quiz\Entity\ThemeRoundQuestion;
use Quiz\Service\QuizRound as QuizRoundService;
use Quiz\Service\ThemeRoundQuestionService;
use Quiz\Service\ThemeRoundService;
use Quiz\Service\Quiz as QuizService;
use Quiz\Service\QuizLog as QuizLogService;
use Zend\Form\FormInterface;
use Zend\View\Model\ViewModel;

class ThemeRoundController extends AbstractCrudController
{
    /** @var ThemeRoundService  */
    protected $themeRoundService;

    /** @var ThemeRoundQuestionService */
    protected $themeRoundQuestionService;

    /** @var QuizService */
    protected $quizService;

    /** @var QuizRoundService */
    protected $quizRoundService;

    /** @var QuizLogService */
    protected $quizLogService;

    /** @var ThemeRoundForm  */
    protected $themeRoundForm;

    /**
     * @param ThemeRoundService $themeRoundService
     * @param ThemeRoundQuestionService $themeRoundQuestionService
     * @param QuizService $quizService
     * @param QuizRoundService $quizRoundService
     * @param QuizLogService $quizLogService
     * @param ThemeRoundForm $themeRoundForm
     */
    public function __construct(
        ThemeRoundService $themeRoundService,
        ThemeRoundQuestionService $themeRoundQuestionService,
        QuizService $quizService,
        QuizRoundService $quizRoundService,
        QuizLogService $quizLogService,
        ThemeRoundForm $themeRoundForm
    ) {
        parent::__construct();

        $this->themeRoundService = $themeRoundService;
        $this->themeRoundQuestionService = $themeRoundQuestionService;
        $this->quizService = $quizService;
        $this->quizRoundService = $quizRoundService;
        $this->quizLogService = $quizLogService;
        $this->themeRoundForm = $themeRoundForm;
    }

    public function indexAction()
    {
        $themeRounds = $this->themeRoundService->getThemeRounds();
        $futureQuizzes = $this->quizService->findFutureQuizzes();

        return new ViewModel(
            [
                'themeRounds' => $themeRounds,
                'futureQuizzes' => $futureQuizzes
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

    public function addToQuizAction()
    {
        $themeRoundId = $this->params('themeRoundId');
        $quizRoundId = $this->params("quizRoundId");

        /** @var ThemeRoundEntity $themeRound */
        $themeRound = $this->themeRoundService->getById($themeRoundId);
        /** @var QuizRoundEntity $quizRound */
        $quizRound = $this->quizRoundService->getById($quizRoundId);

        $this->quizService->addThemeRound($themeRound, $quizRound);

        $this->quizLogService->createThemeRoundAddedLog($themeRound, $quizRound);

        return $this->redirect()->toRoute('quiz/detail', ['quizId' => $quizRound->getQuiz()->getId()]);
    }

    protected function processFormData(FormInterface $form)
    {
        /** @var \Quiz\Entity\ThemeRound $themeRound */
        $themeRound = $form->getObject();

        if (!$themeRound->getId()) {
            $this->themeRoundService->createThemeRound($themeRound);
        } else {
            $this->themeRoundService->updateThemeRound($themeRound);
        }

        return true;
    }

    protected function getCrudForm()
    {
        /* @var $request \Zend\Http\PhpEnvironment\Request */
        $request = $this->getRequest();

        if ($request->getQuery('id')) {
            $themeRound = $this->themeRoundService->getById($request->getQuery('id'));
        }

        if (empty($themeRound)) {
            $themeRound = new ThemeRound();
        }

        $this->themeRoundForm->bind($themeRound);

        return $this->themeRoundForm;
    }

    protected function getCrudSuccessResponse()
    {
        return $this->redirect()->toRoute('theme-rounds');
    }

    protected function getCrudFailureResponse()
    {
        return $this->redirect()->toRoute('theme-rounds');
    }
}