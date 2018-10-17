<?php
namespace Quiz\Service;

use Quiz\Entity\ThemeRoundQuestion;

class ThemeRoundQuestionService extends AbstractService
{
    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getRepository()
    {
        return $this->em->getRepository('Quiz\Entity\ThemeRoundQuestion');
    }

    /**
     * @param ThemeRoundQuestion $themeRoundQuestion
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function remove(ThemeRoundQuestion $themeRoundQuestion)
    {
        $this->em->remove($themeRoundQuestion);
        $this->em->flush($themeRoundQuestion);
    }

    public function resetQuestionNumber(ThemeRoundQuestion $themeRoundQuestion, $newPosition)
    {
        $themeRoundQuestion->setQuestionNumber($newPosition * 10 + ($newPosition - ($themeRoundQuestion->getQuestionNumber() / 10)));
        $this->persist($themeRoundQuestion);

        $position = 1;
        foreach ($themeRoundQuestion->getThemeRound()->getThemeRoundQuestions() as $themeRoundQuestion)
        {
            $themeRoundQuestion->setQuestionNumber($position++ * 10);
            $this->persist($themeRoundQuestion);
        }
    }
}