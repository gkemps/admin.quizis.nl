<?php
namespace Quiz\Controller;

use Quiz\Form\ThemeRound;
use Quiz\Service\ThemeRoundQuestionService;
use Quiz\Service\ThemeRoundService;
use Quiz\Service\Quiz as QuizService;
use Quiz\Service\QuizRound as QuizRoundService;
use Quiz\Service\QuizLog as QuizLogService;
use Zend\Form\FormElementManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ThemeRoundControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $services = $serviceLocator->getServiceLocator();

        /** @var ThemeRoundService $themeRoundService */
        $themeRoundService = $services->get(ThemeRoundService::class);

        /** @var ThemeRoundQuestionService $themeRoundQuestionService */
        $themeRoundQuestionService = $services->get(ThemeRoundQuestionService::class);

        /** @var QuizRoundService $quizRoundService */
        $quizRoundService = $services->get(QuizRoundService::class);

        /** @var QuizService $quizService */
        $quizService = $services->get(QuizService::class);

        /** @var QuizLogService $quizLogService */
        $quizLogService = $services->get(QuizLogService::class);

        /** @var FormElementManager $elementManager */
        $elementManager = $services->get('FormElementManager');

        /** @var ThemeRound $themeRoundForm */
        $themeRoundForm = $elementManager->get(ThemeRound::class);

        return new ThemeRoundController(
            $themeRoundService,
            $themeRoundQuestionService,
            $quizService,
            $quizRoundService,
            $quizLogService,
            $themeRoundForm
        );
    }
}