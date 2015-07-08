<?php
namespace Quiz\Service;

use Kemzy\Library\Service\AbstractService;

class QuizRound extends AbstractService
{
    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getRepository()
    {
        return $this->em->getRepository('Quiz\Entity\QuizRound');
    }
}
