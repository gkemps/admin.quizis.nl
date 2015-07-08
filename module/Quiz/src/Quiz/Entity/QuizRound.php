<?php
namespace Quiz\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Quiz\Entity\Quiz as QuizEntity;
use Quiz\Entity\QuizRoundQuestion as QuizRoundQuestionEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="quiz_QuizRound")
 */
class QuizRound
{
    const MAX_QUESTIONS = 10;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @var null|int
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Quiz")
     * @ORM\JoinColumn(name="quiz_Quiz_id", referencedColumnName="id")
     **/
    protected $quiz;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $number;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $theme;

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
     * @ORM\OneToMany(targetEntity="QuizRoundQuestion", mappedBy="quizRound", cascade="persist")
     * @ORM\OrderBy({"questionNumber" = "ASC"})
     *
     * @var Collection
     **/
    protected $quizRoundQuestions;

    public function __construct()
    {
        $this->quizRoundQuestions = new ArrayCollection();
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
     * @return QuizRound
     */
    public function setDateCreated(DateTime $dateCreated)
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
     * @return QuizRound
     */
    public function setDateUpdated(DateTime $dateUpdated)
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
     * @return QuizRound
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param int $number
     * @return QuizRound
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return QuizEntity
     */
    public function getQuiz()
    {
        return $this->quiz;
    }

    /**
     * @param QuizEntity $quiz
     * @return QuizRound
     */
    public function setQuiz(QuizEntity $quiz)
    {
        $this->quiz = $quiz;

        return $this;
    }

    /**
     * @return string
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * @param string $theme
     * @return QuizRound
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * @return ArrayCollection|QuizRoundQuestionEntity[]
     */
    public function getQuizRoundQuestions()
    {
        return $this->quizRoundQuestions;
    }

    /**
     * @return int
     */
    public function getNumberOfPoints()
    {
        $total = 0;
        foreach ($this->getQuizRoundQuestions() as $quizQuestion) {
            $total += $quizQuestion->getQuestion()->getPoints();
        }

        return $total;
    }

    /**
     * @return bool
     */
    public function hasAudio()
    {
        foreach ($this->getQuizRoundQuestions() as $quizQuestion) {
            if ($quizQuestion->getQuestion()->hasAudio()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return int
     */
    public function getMaxNumberOfQuestions()
    {
        return self::MAX_QUESTIONS;
    }

    /**
     * @return int
     */
    public function getNumberOfQuestions()
    {
        return count($this->quizRoundQuestions);
    }

    /**
     * @param Collection $quizRoundQuestions
     */
    public function addQuizRoundQuestions(Collection $quizRoundQuestions)
    {
        /** @var QuizRoundQuestionEntity $quizRoundQuestion */
        foreach ($quizRoundQuestions as $quizRoundQuestion) {
            $this->addQuizRoundQuestion($quizRoundQuestion);
        }
    }

    /**
     * @param QuizRoundQuestion $quizRoundQuestion
     * @return \Quiz\Entity\QuizRoundQuestion
     */
    public function addQuizRoundQuestion(QuizRoundQuestionEntity $quizRoundQuestion)
    {
        $quizRoundQuestion->setQuizRound($this);
        $this->quizRoundQuestions->add($quizRoundQuestion);

        return $quizRoundQuestion;
    }

    /**
     * @return bool
     */
    public function isFull()
    {
        return count($this->quizRoundQuestions) == self::MAX_QUESTIONS;
    }

    public function getPercentageFull()
    {
        return round((count($this->quizRoundQuestions) / self::MAX_QUESTIONS) * 100);
    }

    /**
     * @return QuizRoundQuestionEntity
     */
    public function getNextQuestion()
    {
        $question = $this->quizRoundQuestions->current();
        $this->quizRoundQuestions->next();
        return $question;
    }
}
