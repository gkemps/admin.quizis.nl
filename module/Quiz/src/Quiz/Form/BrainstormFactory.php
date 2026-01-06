<?php
namespace Quiz\Form;

use Quiz\Form\Brainstorm as BrainstormForm;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BrainstormFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return BrainstormForm
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new BrainstormForm();
    }
}
