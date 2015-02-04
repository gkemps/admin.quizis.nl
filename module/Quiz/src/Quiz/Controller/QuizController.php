<?php
namespace Quiz\Controller;

use Quiz\Service\Quiz as QuizService;
use Quiz\Service\QuizRoundQuestion as QuizRoundQuestionService;
use Zend\Form\FormInterface;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class QuizController extends AbstractCrudController
{
    /** @var QuizService  */
    protected $quizService;

    /** @var QuizRoundQuestionService  */
    protected $quizRoundQuestionService;

    /**
     * @param QuizService $quizService
     * @param QuizRoundQuestionService $quizRoundQuestionService
     */
    public function __construct(
        QuizService $quizService,
        QuizRoundQuestionService $quizRoundQuestionService
    ) {
        $this->quizService = $quizService;
        $this->quizRoundQuestionService = $quizRoundQuestionService;
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

        return new ViewModel(
            [
                'quiz' => $quiz
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

        $quizRoundQuestion = $this->quizRoundQuestionService->getQuizRoundQuestionById($quizRoundQuestionId);

        $this->quizRoundQuestionService->resetQuestionNumber($quizRoundQuestion, $newPosition);

        die();
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
