<?php
namespace Quiz\Controller;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TagControllerFactory implements FactoryInterface
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

        /** @var \Quiz\Service\Tag $tagService */
        $tagService = $services->get('Quiz\Service\Tag');

        /** @var FormElementManager $elementManager */
        $elementManager = $services->get('FormElementManager');

        /** @var \Quiz\Form\Tag $tagForm */
        $tagForm = $elementManager->get('Quiz\Form\Tag');

        return new TagController(
            $tagService,
            $tagForm
        );
    }
}
