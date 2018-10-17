<?php
namespace Quiz\Controller;

use Quiz\Service\QuizRound;
use Quiz\Service\ThemeRoundService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TempControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $services = $serviceLocator->getServiceLocator();

        /** @var ThemeRoundService $themeRoundService */
        $themeRoundService = $services->get(ThemeRoundService::class);

        /** @var QuizRound $quizRoundService */
        $quizRoundService = $services->get(QuizRound::class);

        return new TempController(
            $themeRoundService,
            $quizRoundService
        );
    }
}