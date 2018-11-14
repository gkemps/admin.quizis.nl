<?php
namespace Quiz\Controller;

use Quiz\Service\Question as QuestionService;
use Quiz\Service\Category as CategoryService;
use Quiz\Service\Quiz as QuizService;
use Quiz\Service\QuizLog as QuizLogService;
use Quiz\Form\Question as QuestionForm;
use Quiz\Entity\Question as QuestionEntity;
use Quiz\Service\ThemeRoundService;
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

    /** @var ThemeRoundService */
    protected $themeRoundService;

    /**
     * @param QuestionService $questionService
     * @param CategoryService $categoryService
     * @param QuizService $quizService
     * @param AuthenticationService $userAuthenticationService
     * @param QuestionForm $questionForm
     * @param QuizLogService $quizLogService
     * @param ThemeRoundService $themeRoundService
     */
    public function __construct(
        QuestionService $questionService,
        CategoryService $categoryService,
        QuizService $quizService,
        AuthenticationService $userAuthenticationService,
        QuestionForm $questionForm,
        QuizLogService $quizLogService,
        ThemeRoundService $themeRoundService
    ) {
        parent::__construct();

        $this->questionService = $questionService;
        $this->categoryService = $categoryService;
        $this->quizService = $quizService;
        $this->userAuthenticationService = $userAuthenticationService;
        $this->questionForm = $questionForm;
        $this->quizLogService = $quizLogService;
        $this->themeRoundService = $themeRoundService;
    }

    public function indexAction()
    {
        $orderByAsked = $this->params()->fromQuery("order") == "asked" ? true : false;

        $questions = $this->questionService->getQuestions($orderByAsked);

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

    public function imageAction()
    {
        $questions = $this->questionService->imageQuestions();
        $questions->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
        $questions->setDefaultItemCountPerPage(10);

        $view = $this->getBasicView();
        $view->setVariable('questions', $questions);
        $view->setTemplate('quiz/question/index');

        return $view;
    }

    public function audioAction()
    {
        $questions = $this->questionService->audioQuestions();
        $questions->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
        $questions->setDefaultItemCountPerPage(10);

        $view = $this->getBasicView();
        $view->setVariable('questions', $questions);
        $view->setTemplate('quiz/question/index');

        return $view;
    }

    public function mostAskedAction()
    {
        $questions = $this->questionService->mostAskedQuestions();
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

    public function addToThemeRoundAction()
    {
        $questionId = $this->params('questionId');
        $themeRoundId = $this->params('themeRoundId');

        $question = $this->questionService->getById($questionId);
        $themeRound = $this->themeRoundService->getThemeRoundById($themeRoundId);

        $this->themeRoundService->addQuestionToThemeRound($question, $themeRound);

        $url = $this->getRequest()->getHeader('Referer')->getUri();
        return $this->redirect()->toUrl($url);
    }

    public function questionsByCategoryAction($orderByAsked = false)
    {
        $categoryId = $this->params('catId');
        $category = $this->categoryService->getCategoryById($categoryId);

        $orderByAsked = $this->params()->fromQuery("order") == "asked" ? true : false;

        $questions = $this->questionService->getQuestionsByCategory($category, $orderByAsked);
        $questions->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
        $questions->setDefaultItemCountPerPage(10);

        $view = $this->getBasicView();
        $view->setVariable('questions', $questions);

        return $view;
    }

    public function downloadMp3Action()
    {
        $questionId = $this->params('questionId');
        $content = file_get_contents("public/data/audio/".$questionId.".mp3");

        $response = $this->getResponse();
        $response->setContent($content);

        $headers = $response->getHeaders();
        $headers->clearHeaders()
            ->addHeaderLine('Content-Type', 'audio/mpeg')
            ->addHeaderLine('Content-Disposition', 'attachment; filename="audio_vraag_'.$questionId.'.mp3"')
            ->addHeaderLine('Content-Length', strlen($content));

        return $this->response;
    }

    /**
     * @param FormInterface $form
     * @return mixed
     */
    protected function processFormData(FormInterface $form)
    {
        /** @var \Quiz\Entity\Question $question */
        $question = $form->getObject();

        if (isset($_FILES['audio']['tmp_name']) && $_FILES['audio']['size'] > 0) {
            $question->setAudioQuestion(true);
        }

        if (!$question->getId()) {
            $this->questionService->createNewQuestion($question);
        } else {
            $this->questionService->updateQuestion($question);
        }

        if (isset($_FILES['audio']['tmp_name']) && $_FILES['audio']['size'] > 0) {
            move_uploaded_file($_FILES['audio']['tmp_name'], "public/data/audio/".$question->getId().".mp3");
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
        $incompleteThemeRounds = $this->themeRoundService->getIncompleteThemeRounds();

        return new ViewModel(
            [
                'user' => $user,
                'futureQuizzes' => $futureQuizzes,
                'nrOfQuestions' => $nrOfQuestions,
                'incompleteThemeRounds' => $incompleteThemeRounds
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
