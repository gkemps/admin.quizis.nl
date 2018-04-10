<?php
namespace Quiz\Form;

use Quiz\Form\Quiz as QuizForm;
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
        $services = $serviceLocator->getServiceLocator();

        /** @var \Doctrine\ORM\EntityManagerInterface $em */
        $em = $services->get('Doctrine\ORM\EntityManager');

        /** @var \Quiz\Service\Quiz $quizService */
        $quizService = $services->get("Quiz\Service\Quiz");

        return new QuizForm(
            $em,
            $quizService
        );
    }
}
