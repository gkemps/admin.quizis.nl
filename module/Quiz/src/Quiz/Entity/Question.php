<?php
namespace Quiz\Entity;

use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Quiz\Entity\Category as CategoryEntity;
use Quiz\Entity\User as UserEntity;
use Quiz\Entity\QuestionTag as QuestionTagEntity;
use Quiz\Entity\QuizRoundQuestion as QuizRoundQuestionEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="quiz_Question")
 */
class Question
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
    protected $question;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $question_en_us;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $answer;

    /**
     * @ORM\Column(type="integer")
     *
     * @var integer
     */
    protected $points;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $source;

    /**
     * @ORM\Column(type="integer")
     *
     * @var bool
     */
    protected $audioQuestion;

    /**
     * @ORM\Column(type="integer")
     *
     * @var bool
     */
    protected $imageQuestion;

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
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="createdBy", referencedColumnName="id")
     **/
    protected $createdBy;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="questions")
     * @ORM\JoinColumn(name="quiz_Category_id", referencedColumnName="id")
     **/
    protected $category;

    /**
     * @ORM\OneToMany(targetEntity="QuestionTag", mappedBy="question")
     **/
    protected $questionTags;

    /**
     * @ORM\OneToMany(targetEntity="QuizRoundQuestion", mappedBy="question")
     **/
    protected $quizRoundQuestions;

    /**
     * @return string
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * @return CategoryEntity
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * @return string
     */
    public function getQuestion()
    {
        $question = trim($this->question);
        if (false == stripos($question, "?")) {
            $question .= "?";
        }
        return $question;
    }

    /**
     * @param string $answer
     * @return Question
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * @param mixed $category
     * @return Question
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @param int $points
     * @return Question
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * @param string $question
     * @return Question
     */
    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * @param int|null $id
     * @return Question
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return UserEntity
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param UserEntity $createdBy
     * @return Question
     */
    public function setCreatedBy(UserEntity $createdBy)
    {
        $this->createdBy = $createdBy;

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
     * @return Question
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
     * @return Question
     */
    public function setDateUpdated($dateUpdated)
    {
        $this->dateUpdated = $dateUpdated;

        return $this;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param string $source
     * @return Question
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @return QuestionTagEntity[]
     */
    public function getQuestionTags()
    {
        return $this->questionTags;
    }

    /**
     * @return boolean
     */
    public function isAudioQuestion()
    {
        return $this->audioQuestion;
    }

    /**
     * @param boolean $audioQuestion
     */
    public function setAudioQuestion($audioQuestion)
    {
        $this->audioQuestion = $audioQuestion;
    }

    /**
     * @return bool
     */
    public function isImageQuestion()
    {
        return $this->imageQuestion;
    }

    /**
     * @param bool $imageQuestion
     */
    public function setImageQuestion($imageQuestion)
    {
        $this->imageQuestion = $imageQuestion;
    }

    /**
     * @return QuizRoundQuestionEntity[]|Collection
     */
    public function getQuizRoundQuestions()
    {
        return $this->quizRoundQuestions;
    }

    /**
     * @return bool
     */
    public function hasSource()
    {
        return !empty($this->source);
    }

    /**
     * @return string
     */
    public function getQuestionEnUs()
    {
        return $this->question_en_us;
    }

    /**
     * @param string $question_en_us
     */
    public function setQuestionEnUs($question_en_us)
    {
        $this->question_en_us = $question_en_us;
    }
}
