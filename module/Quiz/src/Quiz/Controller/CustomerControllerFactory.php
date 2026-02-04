<?php
namespace Quiz\Controller;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CustomerControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return CustomerController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $services = $serviceLocator->getServiceLocator();

        /** @var \Quiz\Service\Customer $customerService */
        $customerService = $services->get('Quiz\Service\Customer');

        /** @var \Quiz\Form\Customer $customerForm */
        $customerForm = $services->get('FormElementManager')->get('Quiz\Form\Customer');

        return new CustomerController(
            $customerService,
            $customerForm
        );
    }
}
