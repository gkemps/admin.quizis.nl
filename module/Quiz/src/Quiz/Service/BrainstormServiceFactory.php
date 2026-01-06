<?php
namespace Quiz\Service;

use Quiz\Service\BrainstormService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BrainstormServiceFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return BrainstormService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        $openRouterConfig = isset($config['openrouter']) ? $config['openrouter'] : [];

        /** @var \Quiz\Service\Category $categoryService */
        $categoryService = $serviceLocator->get('Quiz\\Service\\Category');

        return new BrainstormService($openRouterConfig, $categoryService);
    }
}
