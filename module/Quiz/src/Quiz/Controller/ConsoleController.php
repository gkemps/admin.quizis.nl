<?php
namespace Quiz\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Quiz\Entity\Question;
use Zend\Mvc\Controller\AbstractActionController;

class ConsoleController extends AbstractActionController
{
    /** @var EntityManagerInterface */
    protected $em;

    /**
     * ConsoleController constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function convertImageAction()
    {
        $firstResult = 0;
        $imageQuestions = 0;
        $batchSize = 50;
        while (true) {
            $qb = $this->em->createQueryBuilder();
            $qb->select("q")
                ->from(Question::class, "q")
                ->where($qb->expr()->isNotNull("q.image"))
                ->setFirstResult($firstResult)
                ->setMaxResults($batchSize);

            $questions = $qb->getQuery()->getResult();

            print "First result {$firstResult}\r\n";

            /** @var Question $question */
            foreach ($questions as $question) {
                file_put_contents("data/images/{$question->getId()}.png", $question->getRawImage());
                $imageQuestions++;
            }

            $firstResult += $batchSize;
            if (count($questions) == 0) {
                break;
            }
        }

        die("{$imageQuestions} image questions copied from db to file");
    }
}