<?php
namespace Quiz\Controller;

use Quiz\Service\Question as QuestionService;
use Quiz\Service\Category as CategoryService;
use Quiz\Service\Quiz as QuizService;
use Quiz\Form\Question as QuestionForm;
use Quiz\Entity\Question as QuestionEntity;
use Zend\Form\FormInterface;
use Zend\View\Model\ViewModel;
use Zend\Authentication\AuthenticationService;

class QuestionController extends AbstractCrudController
{
    /** @var QuestionService  */
    protected $questionService;

    /** @var CategoryService  */
    protected $categoryService;

    /** @var QuizService  */
    protected $quizService;

    /** @var AuthenticationService  */
    protected $userAuthenticationService;

    /** @var QuestionForm  */
    protected $questionForm;

    /**
     * @param QuestionService $questionService
     * @param CategoryService $categoryService
     * @param QuizService $quizService
     * @param AuthenticationService $userAuthenticationService
     * @param QuestionForm $questionForm
     */
    public function __construct(
        QuestionService $questionService,
        CategoryService $categoryService,
        QuizService $quizService,
        AuthenticationService $userAuthenticationService,
        QuestionForm $questionForm
    ) {
        $this->questionService = $questionService;
        $this->categoryService = $categoryService;
        $this->quizService = $quizService;
        $this->userAuthenticationService = $userAuthenticationService;
        $this->questionForm = $questionForm;
    }

    public function indexAction()
    {
        $user = $this->userAuthenticationService->getIdentity();
        $questions = $this->questionService->getAllQuestions();
        $futureQuizzes = $this->quizService->findFutureQuizzes();

        return new ViewModel(
            [
                'user' => $user,
                'questions' => $questions,
                'futureQuizzes' => $futureQuizzes
            ]
        );
    }

    public function searchAction()
    {
        $search = $this->getRequest()->getPost('search');

        $user = $this->userAuthenticationService->getIdentity();
        $futureQuizzes = $this->quizService->findFutureQuizzes();
        $questions = $this->questionService->searchQuestions($search);

        return new ViewModel(
            [
                'user' => $user,
                'questions' => $questions,
                'futureQuizzes' => $futureQuizzes
            ]
        );
    }

    public function detailAction()
    {
        $questionId = $this->params('questionId');

        $question = $this->questionService->getQuestionById($questionId);

        return new ViewModel(
            [
                'question' => $question
            ]
        );
    }

    public function likeAction()
    {
        $questionId = $this->params('questionId');

        $question = $this->questionService->getQuestionById($questionId);

        $this->questionService->likeQuestion($question);

        return $this->redirect()->toRoute('question');
    }

    public function unlikeAction()
    {
        $user = $this->userAuthenticationService->getIdentity();

        $questionId = $this->params('questionId');
        $question = $this->questionService->getQuestionById($questionId);

        $this->questionService->unlikeQuestion($question, $user);

        return $this->redirect()->toRoute('question');
    }

    public function addToQuizRoundAction()
    {
        $questionId = $this->params('questionId');
        $quizRoundId = $this->params('quizRoundId');

        $question = $this->questionService->getQuestionById($questionId);
        $quizRound = $this->quizService->getQuizRoundById($quizRoundId);

        $this->quizService->addQuestionToQuizRound($question, $quizRound);

        return $this->redirect()->toRoute('question');
    }

    public function questionsByCategoryAction()
    {
        $categoryId = $this->params('catId');
        $category = $this->categoryService->getCategoryById($categoryId);

        $user = $this->userAuthenticationService->getIdentity();
        $questions = $this->questionService->getQuestionsByCategory($category);
        $futureQuizzes = $this->quizService->findFutureQuizzes();

        $view = new ViewModel(
            [
                'user' => $user,
                'questions' => $questions,
                'futureQuizzes' => $futureQuizzes
            ]
        );
        $view->setTemplate('quiz/question/index.phtml');

        return $view;
    }

    /**
     * @param FormInterface $form
     * @return mixed
     */
    protected function processFormData(FormInterface $form)
    {
        /** @var \Quiz\Entity\Question $question */
        $question = $form->getObject();

        if (!$question->getId()) {
            $this->questionService->createNewQuestion($question);
        } else {
            $this->questionService->updateQuestion($question);
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
            $question = $this->questionService->getQuestionById($request->getQuery('id'));
        }

        if (empty($question)) {
            $question = new QuestionEntity();
        }

        $this->questionForm->bind($question);

        return $this->questionForm;
    }

    protected function getCrudSuccessResponse()
    {
        return $this->redirect()->toRoute('question');
    }

    protected function getCrudFailureResponse()
    {
        return $this->redirect()->toRoute('question');
    }
}