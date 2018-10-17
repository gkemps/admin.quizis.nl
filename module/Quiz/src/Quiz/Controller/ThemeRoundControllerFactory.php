<?php
namespace Quiz\Controller;

use Quiz\Service\ThemeRoundQuestionService;
use Quiz\Service\ThemeRoundService;
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

        return new ThemeRoundController(
            $themeRoundService,
            $themeRoundQuestionService
        );
    }
}