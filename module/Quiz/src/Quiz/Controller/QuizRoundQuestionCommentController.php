<?php
namespace Quiz\Controller;

use DateTime;
use Quiz\Form\QuizRoundQuestionComment as QuizRoundQuestionCommentForm;
use Quiz\Service\QuizRoundQuestion as QuizRoundQuestionService;
use Quiz\Service\QuizLog as QuizLogService;
use Quiz\Entity\QuizRoundQuestionComment as QuizRoundQuestionCommentEntity;
use Zend\Form\FormInterface;
use Zend\View\Model\ViewModel;
use Zend\Authentication\AuthenticationService;

class QuizRoundQuestionCommentController extends AbstractCrudController
{
    /** @var QuizRoundQuestionService */
    protected $quizRoundQuestionService;

    /** @var QuizRoundQuestionCommentForm  */
    protected $form;

    /** @var AuthenticationService  */
    protected $authenticationService;

    /** @var QuizLogService  */
    protected $quizLogService;

    /**
     * @param QuizRoundQuestionService $quizRoundQuestionService
     * @param QuizRoundQuestionCommentForm $quizRoundQuestionCommentForm
     * @param AuthenticationService $authenticationService
     * @param QuizLogService $quizLogService
     */
    public function __construct(
        QuizRoundQuestionService $quizRoundQuestionService,
        QuizRoundQuestionCommentForm $quizRoundQuestionCommentForm,
        AuthenticationService $authenticationService,
        QuizLogService $quizLogService
    ) {
        $this->quizRoundQuestionService = $quizRoundQuestionService;
        $this->form = $quizRoundQuestionCommentForm;
        $this->authenticationService = $authenticationService;
        $this->quizLogService = $quizLogService;
    }

    public function detailAction()
    {
        $quizRoundQuestionId = $this->params('quizRoundQuestionId');
        $quizRoundQuestion = $this->quizRoundQuestionService->getById($quizRoundQuestionId);

        return new ViewModel(
            [
                'quizRoundQuestion' => $quizRoundQuestion,
                'form' => $this->form
            ]
        );
    }

    /**
     * @param FormInterface $form
     * @return mixed
     */
    protected function processFormData(FormInterface $form)
    {
        $quizRoundQuestionId = $this->params('quizRoundQuestionId');
        /** @var \Quiz\Entity\QuizRoundQuestion $quizRoundQuestion */
        $quizRoundQuestion = $this->quizRoundQuestionService->getById($quizRoundQuestionId);

        /** @var QuizRoundQuestionCommentEntity $quizRoundQuestionComment */
        $quizRoundQuestionComment = $form->getObject();
        $quizRoundQuestionComment->setDateCreated(new DateTime('now'));
        $quizRoundQuestionComment->setQuizRoundQuestion($quizRoundQuestion);
        $quizRoundQuestionComment->setUser($this->authenticationService->getIdentity());

        $quizRoundQuestion->addComment($quizRoundQuestionComment);
        $this->quizRoundQuestionService->persist($quizRoundQuestion);

        $this->quizLogService->createNewQuestionCommentLog($quizRoundQuestion);

        return true;
    }

    /**
     * @return FormInterface
     */
    protected function getCrudForm()
    {
        $quizRoundQuestionComment = new QuizRoundQuestionCommentEntity();
        $this->form->bind($quizRoundQuestionComment);

        return $this->form;
    }

    protected function getCrudFailureResponse()
    {
        $quizRoundQuestionId = $this->params('quizRoundQuestionId');
        /** @var \Quiz\Entity\QuizRoundQuestion $quizRoundQuestion */
        $quizRoundQuestion = $this->quizRoundQuestionService->getById($quizRoundQuestionId);

        return $this->redirect()->toRoute('comment', ['quizRoundQuestionId' => $quizRoundQuestion->getId()]);
    }

    protected function getCrudSuccessResponse()
    {
        $quizRoundQuestionId = $this->params('quizRoundQuestionId');
        /** @var \Quiz\Entity\QuizRoundQuestion $quizRoundQuestion */
        $quizRoundQuestion = $this->quizRoundQuestionService->getById($quizRoundQuestionId);

        return $this->redirect()->toRoute('quiz/detail', ['quizId' => $quizRoundQuestion->getQuizRound()->getQuiz()->getId()]);
    }
}
