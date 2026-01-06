<?php
namespace Quiz\Controller;

use Quiz\Controller\BrainstormController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BrainstormControllerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return BrainstormController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $services = $serviceLocator->getServiceLocator();

        /** @var \Quiz\Service\BrainstormService $brainstormService */
        $brainstormService = $services->get('Quiz\Service\Brainstorm');

        /** @var \Quiz\Form\Brainstorm $brainstormForm */
        $brainstormForm = $services->get('FormElementManager')->get('Quiz\Form\Brainstorm');

        return new BrainstormController(
            $brainstormService,
            $brainstormForm
        );
    }
}
