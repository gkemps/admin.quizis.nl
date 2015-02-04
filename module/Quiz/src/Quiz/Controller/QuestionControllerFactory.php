<?php
namespace Quiz\Controller;


use Zend\Form\FormElementManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class QuestionControllerFactory implements FactoryInterface
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

        /** @var \Quiz\Service\Question $questionService */
        $questionService = $services->get('Quiz\Service\Question');

        /** @var FormElementManager $elementManager */
        $elementManager = $services->get('FormElementManager');

        /** @var \Quiz\Form\Question $questionForm */
        $questionForm = $elementManager->get('Quiz\Form\Question');

        /** @var \Quiz\Service\Category $categoryService */
        $categoryService = $services->get('Quiz\Service\Category');

        /** @var \Quiz\Service\Quiz $quizService */
        $quizService = $services->get('Quiz\Service\Quiz');

        /** @var \Zend\Authentication\AuthenticationService $authenticationService */
        $authenticationService = $services->get('zfcuser_auth_service');

        return new QuestionController(
            $questionService,
            $categoryService,
            $quizService,
            $authenticationService,
            $questionForm
        );
    }

}

 