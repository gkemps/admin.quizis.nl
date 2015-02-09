<?php
namespace Quiz\Controller;

use Quiz\Service\Question as QuestionService;
use Quiz\Service\Category as CategoryService;
use Quiz\Service\Quiz as QuizService;
use Quiz\Service\QuizLog as QuizLogService;
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

    /** @var QuizLogService  */
    protected $quizLogService;

    /**
     * @param QuestionService $questionService
     * @param CategoryService $categoryService
     * @param QuizService $quizService
     * @param AuthenticationService $userAuthenticationService
     * @param QuestionForm $questionForm
     * @param QuizLogService $quizLogService
     */
    public function __construct(
        QuestionService $questionService,
        CategoryService $categoryService,
        QuizService $quizService,
        AuthenticationService $userAuthenticationService,
        QuestionForm $questionForm,
        QuizLogService $quizLogService
    ) {
        $this->questionService = $questionService;
        $this->categoryService = $categoryService;
        $this->quizService = $quizService;
        $this->userAuthenticationService = $userAuthenticationService;
        $this->questionForm = $questionForm;
        $this->quizLogService = $quizLogService;
    }

    public function indexAction()
    {
        $questions = $this->questionService->getQuestions();
        if ($questions->count() == 0) {
            $this->redirect()->toRoute('question/form');
        }
        $questions->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
        $questions->setDefaultItemCountPerPage(10);

        $view = $this->getBasicView();
        $view->setVariable('questions', $questions);

        return $view;
    }

    public function searchAction()
    {
        $search = $this->params('term');

        $questions = $this->questionService->searchQuestions($search);
        $questions->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
        $questions->setDefaultItemCountPerPage(10);

        $view = $this->getBasicView();
        $view->setVariable('questions', $questions);
        $view->setVariable('search', $search);

        return $view;
    }

    public function yoursAction()
    {
        $user = $this->userAuthenticationService->getIdentity();

        $questions = $this->questionService->yourQuestions($user);
        $questions->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
        $questions->setDefaultItemCountPerPage(10);

        $view = $this->getBasicView();
        $view->setVariable('questions', $questions);
        $view->setTemplate('quiz/question/index');

        return $view;
    }

    public function likedAction()
    {
        $user = $this->userAuthenticationService->getIdentity();

        $questions = $this->questionService->likedQuestions($user);
        $questions->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
        $questions->setDefaultItemCountPerPage(10);

        $view = $this->getBasicView();
        $view->setVariable('questions', $questions);
        $view->setTemplate('quiz/question/index');

        return $view;
    }

    public function likesAction()
    {
        $questions = $this->questionService->likesQuestions();
        $questions->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
        $questions->setDefaultItemCountPerPage(10);

        $view = $this->getBasicView();
        $view->setVariable('questions', $questions);
        $view->setTemplate('quiz/question/index');

        return $view;
    }

    public function noSourceAction()
    {
        $questions = $this->questionService->noSourceQuestions();
        $questions->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
        $questions->setDefaultItemCountPerPage(10);

        $view = $this->getBasicView();
        $view->setVariable('questions', $questions);
        $view->setTemplate('quiz/question/index');

        return $view;
    }

    public function notAskedAction()
    {
        $questions = $this->questionService->notAskedQuestions();
        $questions->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
        $questions->setDefaultItemCountPerPage(10);

        $view = $this->getBasicView();
        $view->setVariable('questions', $questions);
        $view->setTemplate('quiz/question/index');

        return $view;
    }

    public function detailAction()
    {
        $questionId = $this->params('questionId');

        $question = $this->questionService->getById($questionId);

        return new ViewModel(
            [
                'question' => $question
            ]
        );
    }

    public function likeAction()
    {
        $questionId = $this->params('questionId');

        $question = $this->questionService->getById($questionId);

        $this->questionService->likeQuestion($question);

        $url = $this->getRequest()->getHeader('Referer')->getUri();
        return $this->redirect()->toUrl($url);
    }

    public function unlikeAction()
    {
        $user = $this->userAuthenticationService->getIdentity();

        $questionId = $this->params('questionId');
        $question = $this->questionService->getById($questionId);

        $this->questionService->unlikeQuestion($question, $user);

        $url = $this->getRequest()->getHeader('Referer')->getUri();
        return $this->redirect()->toUrl($url);
    }

    public function addToQuizRoundAction()
    {
        $questionId = $this->params('questionId');
        $quizRoundId = $this->params('quizRoundId');

        $question = $this->questionService->getById($questionId);
        $quizRound = $this->quizService->getQuizRoundById($quizRoundId);

        $quizRoundQuestion = $this->quizService->addQuestionToQuizRound($question, $quizRound);
        $this->quizLogService->createNewQuestionAddedLog($quizRoundQuestion);

        $url = $this->getRequest()->getHeader('Referer')->getUri();
        return $this->redirect()->toUrl($url);
    }

    public function questionsByCategoryAction()
    {
        $categoryId = $this->params('catId');
        $category = $this->categoryService->getCategoryById($categoryId);

        $questions = $this->questionService->getQuestionsByCategory($category);
        $questions->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
        $questions->setDefaultItemCountPerPage(10);

        $view = $this->getBasicView();
        $view->setVariable('questions', $questions);

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
            $question = $this->questionService->getById($request->getQuery('id'));
        }

        if (empty($question)) {
            $question = new QuestionEntity();
        }

        $this->questionForm->bind($question);

        return $this->questionForm;
    }

    protected function getBasicView()
    {
        $user = $this->userAuthenticationService->getIdentity();
        $futureQuizzes = $this->quizService->findFutureQuizzes();
        $nrOfQuestions = $this->questionService->getNumberOfQuestions();

        return new ViewModel(
            [
                'user' => $user,
                'futureQuizzes' => $futureQuizzes,
                'nrOfQuestions' => $nrOfQuestions
            ]
        );
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
