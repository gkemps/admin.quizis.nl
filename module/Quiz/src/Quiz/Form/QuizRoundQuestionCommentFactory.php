<?php
namespace Quiz\Form;

use Quiz\Form\QuizRoundQuestionComment as QuizRoundQuestionCommentForm;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class QuizRoundQuestionCommentFactory implements FactoryInterface
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

        return new QuizRoundQuestionCommentForm(
            $em
        );
    }
}
