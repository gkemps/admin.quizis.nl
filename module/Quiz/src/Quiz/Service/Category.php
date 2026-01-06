<?php
namespace Quiz\Service;

use Quiz\Entity\Category as CategoryEntity;
use Doctrine\ORM\EntityManager;

class Category
{
    const MUSIC_CATEGORY_ID = 4;

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
        $qb = $this->em->createQueryBuilder();

        $qb->select("c")
            ->from("Quiz\Entity\Category", "c")
            ->orderBy("c.name");

        return $qb->getQuery()->getResult();
    }

    /**
     * @param $id
     * @return null|CategoryEntity
     */
    public function getCategoryById($id)
    {
        return $this->categoryRepository->find($id);
    }

    /**
     * Find a category by name (case-insensitive)
     *
     * @param string $name
     * @return null|CategoryEntity
     */
    public function getCategoryByName($name)
    {
        if (empty($name)) {
            return null;
        }

        $qb = $this->em->createQueryBuilder();

        $qb->select("c")
            ->from("Quiz\Entity\Category", "c")
            ->where("LOWER(c.name) = LOWER(:name)")
            ->setParameter('name', $name)
            ->setMaxResults(1);

        $result = $qb->getQuery()->getResult();

        return !empty($result) ? $result[0] : null;
    }
}
