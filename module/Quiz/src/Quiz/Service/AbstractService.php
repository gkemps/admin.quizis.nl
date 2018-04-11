<?php
namespace Quiz\Service;

use Zend\Paginator\Paginator;
use Doctrine\ORM\EntityManager;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;

abstract class AbstractService
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    /**
     * @param $id
     * @return object
     */
    public function getById($id)
    {
        return $this->getRepository()->find($id);
    }

    /**
     * @param $entity
     * @return $this
     */
    public function persist($entity)
    {
        if (method_exists($entity, 'setDateUpdated')) {
            $entity->setDateUpdated(new \DateTime());
        }

        $this->em->persist($entity);
        $this->em->flush();

        return $this;
    }

    /**
     * @return Paginator
     */
    public function getPaginatedList()
    {
        $qb = $this->getRepository()->createQueryBuilder('entity');

        $paginator = new Paginator(
            new DoctrinePaginator(new ORMPaginator($qb))
        );

        return $paginator;
    }

    /**
     * @param $qb
     * @return Paginator
     */
    protected function returnPaginatedSetFromQueryBuilder($qb)
    {
        $paginator = new Paginator(
            new DoctrinePaginator(new ORMPaginator($qb))
        );

        return $paginator;
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    abstract protected function getRepository();
}