<?php
namespace Quiz\Service;

use DateTime;
use Quiz\Entity\Question as QuestionEntity;
use Quiz\Entity\Category as CategoryEntity;
use Quiz\Entity\User as UserEntity;
use Doctrine\ORM\EntityManager;
use Zend\Authentication\AuthenticationService;

class Question extends AbstractService
{
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
        parent::__construct($em);

        $this->questionRepository = $this->em->getRepository('Quiz\Entity\Question');
        $this->authenticationService = $authenticationService;
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getRepository()
    {
        return $this->questionRepository;
    }


    /**
     * @param bool $orderByAsked
     * @return \Zend\Paginator\Paginator
     */
    public function getQuestions($orderByAsked = false)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('q')
            ->from('\Quiz\Entity\Question', 'q')
            ->orderBy("q.dateCreated", "DESC");

        if ($orderByAsked) {
            $qb = $this->em->createQueryBuilder();
            $qb->select('q', 'COUNT(qrq.id) AS HIDDEN countId')
                ->from('Quiz\Entity\Question', 'q')
                ->leftJoin('q.quizRoundQuestions', 'qrq')
                ->groupBy('q.id')
                ->addOrderby('countId', 'DESC');

        }

        return $this->returnPaginatedSetFromQueryBuilder($qb);
    }

    /**
     * @return int
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getNumberOfQuestions()
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('COUNT(q)')
            ->from('\Quiz\Entity\Question', 'q');

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param CategoryEntity $category
     * @param bool $orderByAsked
     * @return \Zend\Paginator\Paginator|QuestionEntity[]
     */
    public function getQuestionsByCategory(CategoryEntity $category, bool $orderByAsked = false)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('q')
            ->from('\Quiz\Entity\Question', 'q')
            ->where($qb->expr()->eq('q.category', ':category'))
            ->orderBy('q.dateCreated', 'DESC');

        if ($orderByAsked) {
            $qb = $this->em->createQueryBuilder();
            $qb->select('q', 'COUNT(qrq.id) AS HIDDEN countId')
                ->from('\Quiz\Entity\Question', 'q')
                ->where($qb->expr()->eq('q.category', ':category'))
                ->leftJoin('q.quizRoundQuestions', 'qrq')
                ->groupBy('q.id')
                ->orderBy('countId', 'DESC');
        }

        $qb->setParameter('category', $category);

        return $this->returnPaginatedSetFromQueryBuilder($qb);
    }

    /**
     * @param $search
     * @return \Zend\Paginator\Paginator|QuestionEntity[]
     */
    public function searchQuestions($search)
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('q')
            ->from('Quiz\Entity\Question', 'q')
            ->where($qb->expr()->orX(
                    $qb->expr()->like('q.question', ':search'),
                    $qb->expr()->like('q.answer', ':search')
                ))
            ->orderBy('q.dateCreated', 'DESC');

        $qb->setParameter('search', '%'.$search.'%');

        return $this->returnPaginatedSetFromQueryBuilder($qb);
    }

    /**
     * @return \Zend\Paginator\Paginator
     */
    public function yourQuestions(UserEntity $user)
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('q')
            ->from('Quiz\Entity\Question', 'q')
            ->where($qb->expr()->eq(
                    'q.createdBy', ':user'
                ))
            ->orderBy('q.dateCreated', 'DESC');

        $qb->setParameter('user', $user);

        return $this->returnPaginatedSetFromQueryBuilder($qb);
    }

    /**
     * @return \Zend\Paginator\Paginator
     */
    public function notAskedQuestions()
    {
        $sql = "SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))";
        $stmt = $this->em->getConnection()->prepare($sql);
        $stmt->execute();

        $qb = $this->em->createQueryBuilder();

        $qb->select('q')
            ->from('Quiz\Entity\Question', 'q')
            ->leftJoin('q.quizRoundQuestions', 'qrq')
            ->having($qb->expr()->eq(
                    'COUNT(qrq.id)',
                    0
                ))
            ->groupBy('q.id')
            ->orderBy('q.dateCreated', 'DESC');

        return $this->returnPaginatedSetFromQueryBuilder($qb);
    }

    public function notAskedVdoQuestions()
    {
        $sql = "SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))";
        $stmt = $this->em->getConnection()->prepare($sql);
        $stmt->execute();

        $qb = $this->em->createQueryBuilder();

        $qb->select('q.id')
            ->from('Quiz\Entity\Question', 'q')
            ->leftJoin('q.quizRoundQuestions', 'qrq')
            ->leftJoin('qrq.quizRound', 'qr')
            ->leftJoin('qr.quiz', 'qz')	
            ->where($qb->expr()->eq('qz.location', ':vdo'))
            ->having($qb->expr()->gt(
                    'COUNT(qrq.id)',
                    0
                ))
            ->groupBy('q.id')
            ->orderBy('q.dateCreated', 'DESC');

        $qb2 = $this->em->createQueryBuilder();

        $qb2->select('q2')
            ->from('Quiz\Entity\Question', 'q2')
            ->where($qb2->expr()->notIn('q2.id', $qb->getDQL()))
            ->andWhere($qb2->expr()->notLike("q2.question", $qb2->expr()->literal("%band/artiest%")))
            ->orderBy('q2.dateCreated', 'DESC');

        $qb2->setParameter('vdo', 1);    

        return $this->returnPaginatedSetFromQueryBuilder($qb2);
    }

    public function mostAskedQuestions()
    {
        $sql = "SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))";
        $stmt = $this->em->getConnection()->prepare($sql);
        $stmt->execute();

        $qb = $this->em->createQueryBuilder();

        $qb->select('q, COUNT(qrq.id) AS HIDDEN nrAsked')
            ->from('Quiz\Entity\Question', 'q')
            ->leftJoin('q.quizRoundQuestions', 'qrq')
            ->having($qb->expr()->gt(
                'COUNT(qrq.id)',
                1
            ))
            ->groupBy('q.id')
            ->orderBy('nrAsked', 'DESC');

        return $this->returnPaginatedSetFromQueryBuilder($qb);
    }

    public function mostAskedYearQuestions()
    {
        $sql = "SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))";
        $stmt = $this->em->getConnection()->prepare($sql);
        $stmt->execute();

        $qb = $this->em->createQueryBuilder();

        $qb->select('q, COUNT(qrq.id) AS HIDDEN nrAsked')
            ->from('Quiz\Entity\Question', 'q')
            ->leftJoin('q.quizRoundQuestions', 'qrq')
            ->where($qb->expr()->gt('q.dateCreated', ':date'))
            ->having($qb->expr()->gt(
                'COUNT(qrq.id)',
                1
            ))
            ->groupBy('q.id')
            ->orderBy('nrAsked', 'DESC');

        $qb->setParameter('date', new DateTime('now - 1 year'));

        return $this->returnPaginatedSetFromQueryBuilder($qb);
    }

    /**
     * @return \Zend\Paginator\Paginator
     */
    public function noSourceQuestions()
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('q')
            ->from('Quiz\Entity\Question', 'q')
            ->where($qb->expr()->eq(
                    $qb->expr()->length('q.source'),
                    0
                ))
            ->orderBy('q.dateCreated', 'DESC');

        return $this->returnPaginatedSetFromQueryBuilder($qb);
    }

    public function imageQuestions()
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('q')
            ->from('Quiz\Entity\Question', 'q')
            ->where($qb->expr()->neq(
                $qb->expr()->length('q.imageQuestion'),
                0
            ))
            ->orderBy('q.dateCreated', 'DESC');

        return $this->returnPaginatedSetFromQueryBuilder($qb);
    }

    public function audioQuestions()
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('q')
            ->from('Quiz\Entity\Question', 'q')
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->neq('q.audioQuestion', 0),
                    $qb->expr()->notLike("q.question", $qb->expr()->literal("%band/artiest%"))
                )
            )
            ->orderBy('q.dateCreated', 'DESC');

        return $this->returnPaginatedSetFromQueryBuilder($qb);
    }

    public function musicQuestions()
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('q')
            ->from('Quiz\Entity\Question', 'q')
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->neq('q.audioQuestion', 0),
                    $qb->expr()->like("q.question", $qb->expr()->literal("%band/artiest%"))
                )
            )
            ->orderBy('q.dateCreated', 'DESC');

        return $this->returnPaginatedSetFromQueryBuilder($qb);
    }

    /**
     * @param QuestionEntity $question
     * @return QuestionEntity
     * @throws \Exception
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
     * @throws \Exception
     */
    public function updateQuestion(QuestionEntity $question)
    {
        $question->setDateUpdated(new DateTime('now'));
        $this->persist($question);

        return $question;
    }
}
