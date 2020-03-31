<?php
namespace Quiz\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ConsoleControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var  $services */
        $services = $serviceLocator->getServiceLocator();

        /** @var EntityManagerInterface $em */
        $em = $services->get(EntityManager::class);

        return new ConsoleController(
            $em
        );
    }
}