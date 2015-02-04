<?php
namespace Quiz\Service;

use DateTime;
use Quiz\Entity\Question as QuestionEntity;
use Quiz\Entity\Category as CategoryEntity;
use Quiz\Entity\QuestionLike as QuestionLikeEntity;
use Quiz\Entity\User as UserEntity;
use Doctrine\ORM\EntityManager;
use Zend\Authentication\AuthenticationService;

class Question
{
    /** @var EntityManager  */
    protected $em;

    /** @var \Doctrine\ORM\EntityRepository  */
    protected $questionRepository;

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
        $this->questionRepository = $this->em->getRepository('Quiz\Entity\Question');
        $this->authenticationService = $authenticationService;
    }

    /**
     * @param $id
     * @return null|QuestionEntity
     */
    public function getQuestionById($id)
    {
        return $this->questionRepository->find($id);
    }

    /**
     * @return QuestionEntity[]
     */
    public function getAllQuestions()
    {
        return $this->questionRepository->findBy([], ['dateCreated' => 'DESC']);
    }

    /**
     * @param CategoryEntity $category
     * @return QuestionEntity[]
     */
    public function getQuestionsByCategory(CategoryEntity $category)
    {
        return $this->questionRepository->findBy(['category' => $category->getId()], ['dateCreated' => 'DESC']);
    }

    /**
     * @param $search
     * @return QuestionEntity[]
     */
    public function searchQuestions($search)
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('q')
            ->from('Quiz\Entity\Question', 'q')
            ->where($qb->expr()->like('q.question', ':search'))
            ->orderBy('q.dateCreated', 'DESC');

        $qb->setParameter('search', '%'.$search.'%');

        return $qb->getQuery()->getResult();
    }

    /**
     * @param QuestionEntity $question
     * @return QuestionEntity
     */
    public function createNewQuestion(QuestionEntity $question)
    {
        $user = $this->authenticationService->getIdentity();

        $question->setDateCreated(new DateTime('now'));
        $question->setCreatedBy($user);
        $this->persist($question);

        return $question;
    }

    /**
     * @param QuestionEntity $question
     * @return QuestionEntity
     */
    public function updateQuestion(QuestionEntity $question)
    {
        $question->setDateUpdated(new DateTime('now'));
        $this->persist($question);

        return $question;
    }

    /**
     * @param QuestionEntity $question
     */
    public function likeQuestion(QuestionEntity $question)
    {
        $questionLike = $this->createQuestionLike($question);

        $question->addQuestionLike($questionLike);
        $this->persist($question);
    }

    /**
     * @param QuestionEntity $question
     * @param UserEntity $user
     */
    public function unlikeQuestion(QuestionEntity $question, UserEntity $user)
    {
        foreach ($question->getQuestionLikes() as $questionLike) {
            if ($questionLike->getQuestion()->getId() == $question->getId() &&
                $questionLike->getUser()->getId() == $user->getId()) {

                $question->removeQuestionLike($questionLike);
                $this->removeQuestionLike($questionLike);
            }
        }
    }

    protected function removeQuestionLike(QuestionLikeEntity $questionLike)
    {
        $this->em->remove($questionLike);
        $this->em->flush();
    }

    /**
     * @param QuestionEntity $question
     */
    protected function persist(QuestionEntity $question)
    {
        $this->em->persist($question);
        $this->em->flush();
    }

    /**
     * @param QuestionEntity $question
     * @return QuestionLikeEntity
     */
    protected function createQuestionLike(QuestionEntity $question)
    {
        /** @var UserEntity $user */
        $user = $this->authenticationService->getIdentity();

        $questionLike = new QuestionLikeEntity();
        $questionLike->setQuestion($question);
        $questionLike->setUser($user);
        $questionLike->setDateCreated(new DateTime('now'));

        return $questionLike;
    }
}
