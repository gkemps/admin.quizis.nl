<?php
namespace Quiz\Controller;

use Quiz\Service\Category;
use Zend\Form\FormElementManager;
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

        /** @var \Quiz\Service\QuizLog $quizLogService */
        $quizLogService = $services->get('Quiz\Service\QuizLog');

        /** @var Category $categoryService */
        $categoryService = $services->get(Category::class);

        /** @var FormElementManager $elementManager */
        $elementManager = $services->get('FormElementManager');

        /** @var \Quiz\Form\Quiz $quizForm */
        $quizForm = $elementManager->get('Quiz\Form\Quiz');

        return new QuizController(
            $quizService,
            $quizRoundQuestionService,
            $quizLogService,
            $categoryService,
            $quizForm
        );
    }
}

 