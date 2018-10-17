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
        return $this->getRepository()->findBy([], ['name' => 'ASC']);
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
        /** @var ThemeRound[] $results */
        $results = $this->getRepository()->findAll();

        $rounds = [];
        foreach ($results as $result) {
            if (!$result->isFull()) {
                $rounds[] = $result;
            }
        }

        return $rounds;
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
     * @return ThemeRound
     */
    public function createThemeRound(ThemeRound $themeRound)
    {
        $this->persist($themeRound);

        return $themeRound;
    }

    /**
     * @param ThemeRound $themeRound
     */
    public function updateThemeRound(ThemeRound $themeRound)
    {
        $this->persist($themeRound);
    }
}