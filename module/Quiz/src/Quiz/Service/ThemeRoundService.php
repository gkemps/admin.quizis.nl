<?php
namespace Quiz\Service;

use Quiz\Entity\ThemeRound as ThemeRoundEntity;
use Quiz\Entity\Question as QuestionEntity;
use Quiz\Entity\ThemeRound;
use Quiz\Entity\ThemeRoundQuestion;

class ThemeRoundService extends AbstractService
{
    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getRepository()
    {
        return $this->em->getRepository('Quiz\Entity\ThemeRound');
    }

    public function getThemeRounds()
    {
        return $this->getRepository()->findAll();
    }

    /**
     * @param $id
     * @return null|object|ThemeRound
     */
    public function getThemeRoundById($id)
    {
        return $this->getRepository()->find($id);
    }

    public function getIncompleteThemeRounds()
    {
        return $this->getRepository()->findAll();
    }

    /**
     * @param QuestionEntity $question
     * @param ThemeRoundEntity $themeRound
     */
    public function addQuestionToThemeRound(QuestionEntity $question, ThemeRoundEntity $themeRound)
    {
        $themeRoundQuestion = new ThemeRoundQuestion();
        $themeRoundQuestion->setThemeRound($themeRound);
        $themeRoundQuestion->setQuestion($question);
        $themeRoundQuestion->setQuestionNumber($themeRound->getNextQuestionNumber());

        $themeRound->addThemeRoundQuestion($themeRoundQuestion);

        $this->persist($themeRound);
    }

    /**
     * @param ThemeRound $themeRound
     */
    public function createThemeRound(ThemeRound $themeRound)
    {
        $this->persist($themeRound);
    }

    /**
     * @param ThemeRound $themeRound
     */
    public function updateThemeRound(ThemeRound $themeRound)
    {
        $this->persist($themeRound);
    }
}