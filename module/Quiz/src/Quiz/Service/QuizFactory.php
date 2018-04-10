<?php
namespace Quiz\Service;

use Quiz\Service\Quiz as QuizService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class QuizFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Doctrine\ORM\EntityManager $em */
        $em = $serviceLocator->get('Doctrine\ORM\EntityManager');

        /** @var \Quiz\Service\QuizRound $quizRoundService */
        $quizRoundService = $serviceLocator->get("Quiz\Service\QuizRound");

        /** @var \Quiz\Service\QuizRoundQuestion $quizRoundQuestionService */
        $quizRoundQuestionService = $serviceLocator->get("Quiz\Service\QuizRoundQuestion");

        return new QuizService(
            $em,
            $quizRoundService,
            $quizRoundQuestionService
        );
    }
}
