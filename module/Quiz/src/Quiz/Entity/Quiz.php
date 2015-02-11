<?php
namespace Quiz\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Quiz\Entity\QuizRound as QuizRoundEntity;
use Quiz\Entity\Question as QuestionEntity;
use Quiz\Entity\QuizLog as QuizLogEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="quiz_Quiz")
 */
class Quiz
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @var null|int
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $location;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var DateTime
     */
    protected $date;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var DateTime
     */
    protected $dateCreated;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var DateTime
     */
    protected $dateUpdated;

    /**
     * @ORM\OneToMany(targetEntity="QuizRound", mappedBy="quiz")
     * @ORM\OrderBy({"number" = "ASC"})
     *
     * @var ArrayCollection|QuizRoundEntity[]
     **/
    protected $quizRounds;

    /**
     * @ORM\OneToMany(targetEntity="QuizLog", mappedBy="quiz")
     * @ORM\OrderBy({"dateCreated" = "DESC"})
     **/
    protected $quizLogs;

    /**
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     * @return Quiz
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @param DateTime $dateCreated
     * @return Quiz
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }

    /**
     * @param DateTime $dateUpdated
     * @return Quiz
     */
    public function setDateUpdated($dateUpdated)
    {
        $this->dateUpdated = $dateUpdated;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return Quiz
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param string $location
     * @return Quiz
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Quiz
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return QuizRoundEntity[]
     */
    public function getQuizRounds()
    {
        return $this->quizRounds;
    }

    /**
     * @return int
     */
    public function getNumberOfRounds()
    {
        return count($this->quizRounds);
    }


    /**
     * @return int
     */
    public function getMaxNumberOfQuestions()
    {
        $total = 0;
        foreach ($this->getQuizRounds() as $round) {
            $total += $round->getMaxNumberOfQuestions();
        }

        return $total;
    }

    /**
     * @return int
     */
    public function getNumberOfQuestions()
    {
        $total = 0;
        foreach ($this->getQuizRounds() as $round) {
            $total += $round->getNumberOfQuestions();
        }

        return $total;
    }

    /**
     * @return int
     */
    public function getTotalPoints()
    {
        $total = 0;
        foreach ($this->getQuizRounds() as $round) {
            $total += $round->getNumberOfPoints();
        }
        return $total;
    }

    /**
     * @param QuestionEntity $question
     * @return bool
     */
    public function containsQuestion(QuestionEntity $question)
    {
        foreach ($this->getQuizRounds() as $quizRound) {
            foreach($quizRound->getQuizRoundQuestions() as $quizQuestion) {
                if ($quizQuestion->getQuestion()->getId() == $question->getId()) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @return QuizLogEntity[]
     */
    public function getQuizLogs()
    {
        return $this->quizLogs;
    }

    /**
     * @return QuizRoundEntity
     */
    public function getNextRound()
    {
        $quizRound = $this->quizRounds->current();
        $this->quizRounds->next();
        return $quizRound;
    }

    public function getPhotoRound()
    {
        return $this->quizRounds->get(0);
    }
}
