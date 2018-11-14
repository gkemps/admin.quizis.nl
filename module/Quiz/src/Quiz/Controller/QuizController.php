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
        $quizzes = $this->quizService->getAllQuizzes();

        return new ViewModel(
            [
                'quizzes' => $quizzes
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

        return new ViewModel(
            [
                'quizRound' => $quizRound
            ]
        );
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
            //no update of quiz
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
        return $this->redirect()->toRoute('quiz');
    }
}
