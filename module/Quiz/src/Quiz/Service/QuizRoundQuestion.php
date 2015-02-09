<?php
namespace Quiz\Service;

use Kemzy\Library\Service\AbstractService;
use Quiz\Entity\QuizRoundQuestion as QuizRoundQuestionEntity;
use Doctrine\ORM\EntityManager;

class QuizRoundQuestion extends AbstractService
{
    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getRepository()
    {
        return $this->em->getRepository('Quiz\Entity\QuizRoundQuestion');
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
    public function remove(QuizRoundQuestionEntity $quizRoundQuestion) {
        $this->em->remove($quizRoundQuestion);
        $this->em->flush();
    }
}
