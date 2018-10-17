<?php
namespace Quiz\Form;

use Doctrine\ORM\EntityManager;
use Quiz\Service\ThemeRoundService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ThemeRoundFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $services = $serviceLocator->getServiceLocator();

        /** @var \Doctrine\ORM\EntityManagerInterface $em */
        $em = $services->get(EntityManager::class);

        /** @var ThemeRoundService $themeRoundService */
        $themeRoundService = $services->get(ThemeRoundService::class);

        return new ThemeRound(
            $em,
            $themeRoundService
        );
    }
}