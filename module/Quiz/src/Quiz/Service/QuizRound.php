<?php
namespace Quiz\Service;
use DateTime;
use Quiz\Entity\QuizRound as QuizRoundEntity;

class QuizRound extends AbstractService
{
    /**
     * @param $quiz
     * @param $roundNumber
     * @param null $theme
     * @return QuizRoundEntity
     */
    public function createNewQuizRound(
        $quiz,
        $roundNumber,
        $theme = null
    ) {
        $quizRound = new QuizRoundEntity();
        $quizRound->setQuiz($quiz);
        $quizRound->setNumber($roundNumber);
        $quizRound->setTheme($theme);
        $quizRound->setDateCreated(new DateTime());

        $this->persist($quizRound);

        return $quizRound;
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getRepository()
    {
        return $this->em->getRepository('Quiz\Entity\QuizRound');
    }
}
