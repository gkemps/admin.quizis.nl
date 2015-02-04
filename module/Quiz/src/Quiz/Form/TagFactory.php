<?php
namespace Quiz\Form;

use Quiz\Form\Tag as TagForm;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TagFactory implements FactoryInterface
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

        /** @var \Doctrine\ORM\EntityManagerInterface $em */
        $em = $services->get('Doctrine\ORM\EntityManager');

        /** @var \Quiz\Service\Tag $tagService */
        $tagService = $services->get('Quiz\Service\Tag');

        return new TagForm(
            $em,
            $tagService
        );
    }
}
