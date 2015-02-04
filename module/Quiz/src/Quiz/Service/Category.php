<?php
namespace Quiz\Service;

use Quiz\Entity\Category as CategoryEntity;
use Doctrine\ORM\EntityManager;

class Category
{
    protected $em;

    protected $categoryRepository;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->categoryRepository = $this->em->getRepository('Quiz\Entity\Category');
    }

    /**
     * @return CategoryEntity[]
     */
    public function getAllCategories()
    {
        return $this->categoryRepository->findBy([], ['name' => 'ASC']);
    }

    /**
     * @param $id
     * @return null|CategoryEntity
     */
    public function getCategoryById($id)
    {
        return $this->categoryRepository->find($id);
    }
}
