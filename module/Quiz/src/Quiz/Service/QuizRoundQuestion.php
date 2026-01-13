<?php
namespace Quiz\Service;

use Quiz\Entity\QuizRoundQuestion as QuizRoundQuestionEntity;

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
     * Update question number without resetting all other questions
     * Used for batch updates where we want to set multiple questions at once
     * 
     * @param QuizRoundQuestionEntity $quizRoundQuestion
     * @param integer $newPosition
     */
    public function setQuestionNumber(QuizRoundQuestionEntity $quizRoundQuestion, $newPosition)
    {
        $quizRoundQuestion->setQuestionNumber($newPosition * 10);
        $quizRoundQuestion->setDateUpdated(new \DateTime('now'));
        $this->persist($quizRoundQuestion);
    }

    /**
     * Update multiple question numbers at once
     * More efficient than calling setQuestionNumber multiple times
     * 
     * @param array $updates Array of ['quizRoundQuestion' => entity, 'newPosition' => position]
     */
    public function batchUpdateQuestionNumbers(array $updates)
    {
        foreach ($updates as $update) {
            $quizRoundQuestion = $update['quizRoundQuestion'];
            $newPosition = $update['newPosition'];
            
            $quizRoundQuestion->setQuestionNumber($newPosition * 10);
            $quizRoundQuestion->setDateUpdated(new \DateTime('now'));
            $this->persist($quizRoundQuestion);
        }
        
        // Flush all changes at once
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
