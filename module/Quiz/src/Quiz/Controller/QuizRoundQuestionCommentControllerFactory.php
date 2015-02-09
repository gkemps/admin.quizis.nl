<?php
namespace Quiz\Controller;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class QuizRoundQuestionCommentControllerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var  $services */
        $services = $serviceLocator->getServiceLocator();

        /** @var FormElementManager $elementManager */
        $elementManager = $services->get('FormElementManager');

        /** @var \Quiz\Service\QuizRoundQuestion $quizRoundQuestionService */
        $quizRoundQuestionService = $services->get('Quiz\Service\QuizRoundQuestion');

        /** @var \Quiz\Form\QuizRoundQuestionComment $quizRoundQuestionCommentForm */
        $quizRoundQuestionCommentForm = $elementManager->get('Quiz\Form\QuizRoundQuestionComment');

        /** @var \Zend\Authentication\AuthenticationService $authenticationService */
        $authenticationService = $services->get('zfcuser_auth_service');

        /** @var \Quiz\Service\QuizLog $quizLogService */
        $quizLogService = $services->get('Quiz\Service\QuizLog');

        return new QuizRoundQuestionCommentController(
            $quizRoundQuestionService,
            $quizRoundQuestionCommentForm,
            $authenticationService,
            $quizLogService
        );
    }
}
