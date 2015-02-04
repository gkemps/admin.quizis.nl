<?php
namespace Quiz\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Quiz\Entity\Question as QuestionEntity;
use Quiz\Entity\QuizRound as QuizRoundEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="quiz_QuizRoundQuestion")
 */
class QuizRoundQuestion
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
     * @ORM\ManyToOne(targetEntity="QuizRound", inversedBy="quizRoundQuestions")
     * @ORM\JoinColumn(name="quiz_QuizRound_id", referencedColumnName="id")
     **/
    protected $quizRound;

    /**
     * @ORM\OneToOne(targetEntity="Question")
     * @ORM\JoinColumn(name="quiz_Question_id", referencedColumnName="id")
     **/
    protected $question;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $questionNumber;

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
     * @return DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @param DateTime $dateCreated
     * @return QuizRoundQuestion
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
     * @return QuizRoundQuestion
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
     * @return QuizRoundQuestion
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getQuestionNumber()
    {
        return $this->questionNumber;
    }

    /**
     * @param int $questionNumber
     * @return QuizRoundQuestion
     */
    public function setQuestionNumber($questionNumber)
    {
        $this->questionNumber = $questionNumber;

        return $this;
    }

    /**
     * @return QuestionEntity
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param QuestionEntity $question
     * @return QuizRoundQuestion
     */
    public function setQuestion(QuestionEntity $question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * @return QuizRoundEntity
     */
    public function getQuizRound()
    {
        return $this->quizRound;
    }

    /**
     * @param QuizRoundEntity $quizRound
     * @return QuizRoundQuestion
     */
    public function setQuizRound(QuizRoundEntity $quizRound)
    {
        $this->quizRound = $quizRound;

        return $this;
    }
}
