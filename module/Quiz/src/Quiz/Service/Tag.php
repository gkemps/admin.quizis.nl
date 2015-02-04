<?php
namespace Quiz\Service;

use DateTime;
use Quiz\Entity\Tag as TagEntity;
use Doctrine\ORM\EntityManager;
use Zend\Authentication\AuthenticationService;

class Tag
{
    /** @var EntityManager  */
    protected $em;

    /** @var \Doctrine\ORM\EntityRepository  */
    protected $tagRepository;

    /** @var AuthenticationService  */
    protected $authenticationService;

    /**
     * @param EntityManager $em
     * @param AuthenticationService $authenticationService
     */
    public function __construct(
        EntityManager $em,
        AuthenticationService $authenticationService
    ) {
        $this->em = $em;
        $this->tagRepository = $this->em->getRepository('Quiz\Entity\Tag');
        $this->authenticationService = $authenticationService;
    }

    /**
     * @param $id
     * @return null|TagEntity
     */
    public function getTagById($id)
    {
        return $this->tagRepository->find($id);
    }

    /**
     * @return TagEntity[]
     */
    public function getAllTags()
    {
        return $this->tagRepository->findBy([], ['name' => 'ASC']);
    }

    /**
     * @param TagEntity $tag
     * @return TagEntity
     */
    public function createNewTag(TagEntity $tag)
    {
        $user = $this->authenticationService->getIdentity();

        $tag->setName(strtolower($tag->getName()));
        $tag->setDateCreated(new DateTime('now'));
        $tag->setCreatedBy($user);

        $this->persist($tag);

        return $tag;
    }

    /**
     * @param TagEntity $tag
     * @return TagEntity
     */
    public function updateTag(TagEntity $tag)
    {
        $tag->setName(strtolower($tag->getName()));
        $tag->setDateUpdated(new DateTime('now'));

        $this->persist($tag);

        return $tag;
    }

    /**
     * @param TagEntity $tag
     */
    protected function persist(TagEntity $tag)
    {
        $this->em->persist($tag);
        $this->em->flush();
    }
}
