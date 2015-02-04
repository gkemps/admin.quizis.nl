<?php
namespace Quiz\Controller;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class QuizControllerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $services = $serviceLocator->getServiceLocator();

        /** @var \Quiz\Service\Quiz $quizService */
        $quizService = $services->get('Quiz\Service\Quiz');

        /** @var \Quiz\Service\QuizRoundQuestion $quizRoundQuestionService */
        $quizRoundQuestionService = $services->get('Quiz\Service\QuizRoundQuestion');

        return new QuizController(
            $quizService,
            $quizRoundQuestionService
        );
    }
}

 