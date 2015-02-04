<?php
namespace Quiz\Service;

use DateTime;
use Quiz\Entity\Question as QuestionEntity;
use Quiz\Entity\QuizRoundQuestion as QuizRoundQuestionEntity;
use Quiz\Entity\Quiz as QuizEntity;
use Quiz\Entity\QuizRound as QuizRoundEntity;
use Doctrine\ORM\EntityManager;
use Zend\Ldap\Node\Collection;

class Quiz
{
    /** @var EntityManager  */
    protected $em;

    /** @var \Doctrine\ORM\EntityRepository  */
    protected $quizRepository;

    /** @var \Doctrine\ORM\EntityRepository  */
    protected $quizRoundRepository;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->quizRepository = $this->em->getRepository('Quiz\Entity\Quiz');
        $this->quizRoundRepository = $this->em->getRepository('Quiz\Entity\QuizRound');
    }

    /**
     * @return QuizEntity[]
     */
    public function getAllQuizzes()
    {
        return $this->quizRepository->findBy([], ['date' => 'DESC']);
    }

    /**
     * @param $id
     * @return null|QuizEntity
     */
    public function  getQuizById($id)
    {
        return $this->quizRepository->find($id);
    }

    /**
     * @param $id
     * @return null|QuizRoundEntity
     */
    public function getQuizRoundById($id)
    {
        return $this->quizRoundRepository->find($id);
    }

    /**
     * @param QuestionEntity $question
     * @param QuizRoundEntity $quizRound
     * @return \Quiz\Entity\QuizRound
     */
    public function addQuestionToQuizRound(QuestionEntity $question, QuizRoundEntity $quizRound)
    {
        $quizRoundQuestion = $this->createQuizRoundQuestion($question, $quizRound);

        $quizRound->addQuizRoundQuestion($quizRoundQuestion);
        $this->storeQuizRound($quizRound);

        return $quizRound;
    }

    /**
     * @return QuizEntity[]
     */
    public function findFutureQuizzes()
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('q')
            ->from('\Quiz\Entity\Quiz', 'q')
            ->where($qb->expr()->gt(
                    'q.date',
                    ':date'
                ))
        ->orderBy('q.date', 'ASC');

        $qb->setParameter('date', new DateTime('now'));

        return $qb->getQuery()->getResult();
    }

    /**
     * @return QuizEntity
     */
    public function findNextQuiz()
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('q')
            ->from('\Quiz\Entity\Quiz', 'q')
            ->orderBy('q.date', 'ASC');

        return $qb->getQuery()->getSingleResult();
    }

    /**
     * @param QuizRoundEntity $quizRound
     */
    protected function storeQuizRound(QuizRoundEntity $quizRound)
    {
        $this->em->persist($quizRound);
        $this->em->flush();
    }

    protected function createQuizRoundQuestion(QuestionEntity $question, QuizRoundEntity $quizRound)
    {
        $quizRoundQuestion = new QuizRoundQuestionEntity();
        $quizRoundQuestion->setQuizRound($quizRound);
        $quizRoundQuestion->setQuestion($question);
        $quizRoundQuestion->setDateCreated(new DateTime('now'));
        $quizRoundQuestion->setQuestionNumber($this->findFirstQuestionNumberInQuizRound($quizRound));

        return $quizRoundQuestion;
    }

    /**
     * @param QuizRoundEntity $quizRound
     * @return integer
     */
    protected function findFirstQuestionNumberInQuizRound($quizRound)
    {
        $spots = range(1, $quizRound::MAX_QUESTIONS);

        $occupied = [];
        foreach ($quizRound->getQuizRoundQuestions() as $quizRoundQuestion) {
            $occupied[] = $quizRoundQuestion->getQuestionNumber() / 10;
        }

        return min(array_diff($spots, $occupied)) * 10;
    }
}
