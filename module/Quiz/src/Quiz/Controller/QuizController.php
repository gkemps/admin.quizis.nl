<?php
namespace Quiz\Controller;

use Quiz\Service\Quiz as QuizService;
use Quiz\Service\QuizRoundQuestion as QuizRoundQuestionService;
use Quiz\Service\QuizLog as QuizLogService;
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

    /**
     * @param QuizService $quizService
     * @param QuizRoundQuestionService $quizRoundQuestionService
     * @param QuizLogService $quizLogService
     */
    public function __construct(
        QuizService $quizService,
        QuizRoundQuestionService $quizRoundQuestionService,
        QuizLogService $quizLogService
    ) {
        $this->quizService = $quizService;
        $this->quizRoundQuestionService = $quizRoundQuestionService;
        $this->quizLogService = $quizLogService;
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

        foreach ($quiz->getQuizRounds() as $round) {
            foreach ($round->getQuizRoundQuestions() as $question) {
                $cat = $question->getQuestion()->getCategory();
                if (!isset($data[$cat->getId()])) {
                    $labels[$cat->getId()] = $cat->getName();
                    $data[$cat->getId()] = 0;
                }
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
        // TODO: Implement processFormData() method.
    }

    /**
     * @return FormInterface
     */
    protected function getCrudForm()
    {
        // TODO: Implement getCrudForm() method.
    }
}
