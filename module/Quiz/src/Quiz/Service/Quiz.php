<?php
namespace Quiz\Service;

use DateInterval;
use DateTime;
use Kemzy\Library\Service\AbstractService;
use Quiz\Entity\Question as QuestionEntity;
use Quiz\Entity\QuizRoundQuestion as QuizRoundQuestionEntity;
use Quiz\Entity\Quiz as QuizEntity;
use Quiz\Entity\QuizRound as QuizRoundEntity;
use Quiz\Service\QuizRound as QuizRoundService;
use Quiz\Service\QuizRoundQuestion as QuizRoundQuestionService;
use Doctrine\ORM\EntityManager;

class Quiz extends AbstractService
{
    /** @var EntityManager  */
    protected $em;

    /** @var \Doctrine\ORM\EntityRepository  */
    protected $quizRepository;

    /** @var QuizRoundService  */
    protected $quizRoundService;

    /** @var QuizRoundQuestion  */
    protected $quizRoundQuestionService;

    /**
     * @param EntityManager $em
     * @param QuizRound $quizRoundService
     * @param QuizRoundQuestion $quizRoundQuestionService
     */
    public function __construct(
        EntityManager $em,
        QuizRoundService $quizRoundService,
        QuizRoundQuestionService $quizRoundQuestionService
    ) {
        parent::__construct($em);

        $this->em = $em;
        $this->quizRepository = $this->em->getRepository('Quiz\Entity\Quiz');
        $this->quizRoundService = $quizRoundService;
        $this->quizRoundQuestionService = $quizRoundQuestionService;
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
    public function getQuizById($id)
    {
        return $this->quizRepository->find($id);
    }

    /**
     * @param $id
     * @return null|QuizRoundEntity
     */
    public function getQuizRoundById($id)
    {
        return $this->quizRoundService->getById($id);
    }

    /**
     * @param QuestionEntity $question
     * @param QuizRoundEntity $quizRound
     * @return \Quiz\Entity\QuizRoundQuestion
     */
    public function addQuestionToQuizRound(QuestionEntity $question, QuizRoundEntity $quizRound)
    {
        $quizRoundQuestion = $this->createQuizRoundQuestion($question, $quizRound);

        $quizRoundQuestion = $quizRound->addQuizRoundQuestion($quizRoundQuestion);
        $this->storeQuizRound($quizRound);

        return $quizRoundQuestion;
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

        $nowish = new DateTime('now');
        $nowish->sub(new DateInterval("PT24H"));

        $qb->setParameter('date', $nowish);

        return $qb->getQuery()->getResult();
    }

    /**
     * @return QuizEntity
     */
    public function findNextQuiz()
    {
        $qb = $this->em->createQueryBuilder();

        $now = new DateTime('now');

        $qb->select('q')
            ->from('\Quiz\Entity\Quiz', 'q')
            ->where($qb->expr()->gt('q.date', '?1'))
            ->orderBy('q.date', 'ASC')
            ->setMaxResults(1)
            ->setParameter(1, $now->format('Y-m-d h:i:s'));

        return $qb->getQuery()->getSingleResult();
    }

    /**
     * @param QuizEntity $newQuiz
     * @return QuizEntity
     */
    public function createQuiz($newQuiz)
    {
        $newQuiz->setDateCreated(new DateTime());
        $this->persist($newQuiz);

        if (null != $newQuiz->getCopyOfQuiz()) {
            $this->copyQuestionsFromQuiz($newQuiz);
        } else {
            $this->createNewStandardQuiz($newQuiz);
        }

        return $newQuiz;
    }

    /**
     * @param QuizEntity $quiz
     */
    protected function copyQuestionsFromQuiz(QuizEntity $quiz)
    {
        /** @var QuizEntity $copyFromQuiz */
        $copyFromQuiz = $quiz->getCopyOfQuiz();

        foreach ($copyFromQuiz->getQuizRounds() as $quizRound) {
            $newQuizRound = $this->quizRoundService->createNewQuizRound(
                $quiz,
                $quizRound->getNumber(),
                $quizRound->getTheme()
            );
            foreach ($quizRound->getQuizRoundQuestions() as $quizRoundQuestion) {
                $this->addQuestionToQuizRound(
                    $quizRoundQuestion->getQuestion(),
                    $newQuizRound
                );
            }
        }
    }

    /**
     * @param QuizEntity $quiz
     */
    protected function createNewStandardQuiz(QuizEntity $quiz)
    {
        $this->quizRoundService->createNewQuizRound($quiz, 1, "Foto ronde");
        $this->quizRoundService->createNewQuizRound($quiz, 2);
        $this->quizRoundService->createNewQuizRound($quiz, 3);
        $this->quizRoundService->createNewQuizRound($quiz, 4);
        $this->quizRoundService->createNewQuizRound($quiz, 5);
        $this->quizRoundService->createNewQuizRound($quiz, 6);
        $this->quizRoundService->createNewQuizRound($quiz, 7, "Muziek ronde");
        $this->quizRoundService->createNewQuizRound($quiz, 8);
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

    protected function getRepository()
    {
        return $this->em->getRepository("Quiz\Entity\Quiz");
    }
}
