<?php
namespace Quiz\Service;

use Quiz\Entity\QuizRoundQuestion as QuizRoundQuestionEntity;
use Doctrine\ORM\EntityManager;

class QuizRoundQuestion
{
    protected $em;

    protected $quizRoundQuestionRepository;

    public function __construct(
        EntityManager $em
    ) {
        $this->em = $em;
        $this->quizRoundQuestionRepository = $this->em->getRepository('Quiz\Entity\QuizRoundQuestion');
    }

    /**
     * @param $id
     * @return QuizRoundQuestionEntity
     */
    public function getQuizRoundQuestionById($id)
    {
        return $this->quizRoundQuestionRepository->find($id);
    }

    /**
     * @param QuizRoundQuestionEntity $quizRoundQuestion
     * @param integer $newPosition
     */
    public function resetQuestionNumber(QuizRoundQuestionEntity $quizRoundQuestion, $newPosition)
    {
        $quizRoundQuestion->setQuestionNumber($newPosition * 10 + ($newPosition - ($quizRoundQuestion->getQuestionNumber() / 10)));
        $quizRoundQuestion->setDateUpdated(new \DateTime('now'));
        $this->persist($quizRoundQuestion);

        $position = 1;
        /** @var QuizRoundQuestionEntity $quizRoundQuestion */
        foreach ($quizRoundQuestion->getQuizRound()->getQuizRoundQuestions() as $quizRoundQuestion)
        {
            $quizRoundQuestion->setQuestionNumber($position++ * 10);
            $quizRoundQuestion->setDateUpdated(new \DateTime('now'));
            $this->persist($quizRoundQuestion);
        }
    }

    /**
     * @param QuizRoundQuestionEntity $quizRoundQuestion
     */
    protected function persist(QuizRoundQuestionEntity $quizRoundQuestion)
    {
        $this->em->persist($quizRoundQuestion);
        $this->em->flush();
    }

    /**
     * @param QuizRoundQuestionEntity $quizRoundQuestion
     */
    public function remove(QuizRoundQuestionEntity $quizRoundQuestion) {
        $this->em->remove($quizRoundQuestion);
        $this->em->flush();
    }
}
