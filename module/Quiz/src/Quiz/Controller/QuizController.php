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
    const imageHeight = 200;

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

    public function printPhotosV2Action()
    {
        $this->layout('print/layout-v2');

        $files = scandir("data/images");

        $images = [];
        $questions = [];
        for ($i=0; $i<10; $i++) {
            $pick = $files[rand(3, count($files) - 1)];

            list($width, $height) = getimagesize("data/images/".$pick);

            $ratio = $height / self::imageHeight;

            $newWidth = $width / $ratio;
            $newHeight = self::imageHeight;

            $images[] = [str_replace(".png", "", $pick), $newWidth, $newHeight];
            $questions[] = $i." -> ".$pick." ({$width},{$height}) -> ({$newWidth},{$newHeight}})<br />";
        }

        $maxWidth = 650;
        $maxHeight = 800;
        for ($i = 3; $i < 10; $i++) {
            $shelfHeight = $maxHeight / $i;
            $images = $this->rescaleImages($images, $shelfHeight);
            $shelves = $this->createShelves($images, $maxWidth);
            //print "shelves before: {$i} shelves after: ".count($shelves)."<br />";

            if (count($shelves) > $i) {
                continue;
            }
            break;
        }

//        print "<pre>";
//        print_r($shelves);
//        die();

        return new ViewModel(
            [
                'images' => $images,
                'shelves' => $shelves,
                'questions' => $questions
            ]
        );
    }

    /**
     * @param array $images
     * @param int $newHeight
     * @return array
     */
    protected function rescaleImages(array $images, int $newHeight)
    {
        foreach ($images as $nr => $image) {
            $currentHeight = $image[2];
            $ratio = $newHeight / $currentHeight;
            $newWidth = $image[1] * $ratio;
            $images[$nr] = [$image[0], $newWidth, $newHeight];
        }

        return $images;
    }

    /**
     * @param array $images
     * @param int $maxWidth
     * @return array
     */
    protected function createShelves(array $images, int $maxWidth)
    {
        $shelves = [];
        foreach ($images as $image) {
            $imagePlaced = false;
            foreach($shelves as $nr => $shelf) {
                if ($shelf->width + $image[1] > $maxWidth) {
                    continue;
                }

                $shelf->width = $shelf->width + $image[1];
                $shelf->images[] = $image;
                $imagePlaced = true;
                break;
            }
            if (!$imagePlaced) {
                $newShelf = new \stdClass();
                $newShelf->width = $image[1];
                $newShelf->images = [$image];
                $shelves[count($shelves)] = $newShelf;
            }
        }

        return $shelves;
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
