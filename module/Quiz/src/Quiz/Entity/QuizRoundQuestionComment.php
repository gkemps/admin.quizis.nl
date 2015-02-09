<?php
namespace Quiz\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Quiz\Entity\QuizRoundQuestion as QuizRoundQuestionEntity;
use Quiz\Entity\User as UserEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="quiz_QuizRoundQuestionComment")
 */
class QuizRoundQuestionComment
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
    protected $comment;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="quiz_User_id", referencedColumnName="id")
     **/
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="QuizRoundQuestion")
     * @ORM\JoinColumn(name="quiz_QuizRoundQuestion_id", referencedColumnName="id")
     **/
    protected $quizRoundQuestion;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var DateTime
     */
    protected $dateCreated;

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     * @return QuizRoundQuestionComment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

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
     * @return QuizRoundQuestionComment
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
        $this->dateCreated->setTimezone(new \DateTimeZone('UTC'));

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
     * @return QuizRoundQuestionComment
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getQuizRoundQuestion()
    {
        return $this->quizRoundQuestion;
    }

    /**
     * @param QuizRoundQuestionEntity $quizRoundQuestion
     * @return QuizRoundQuestionComment
     */
    public function setQuizRoundQuestion(QuizRoundQuestionEntity$quizRoundQuestion)
    {
        $this->quizRoundQuestion = $quizRoundQuestion;

        return $this;
    }

    /**
     * @return UserEntity
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param UserEntity $user
     * @return QuizRoundQuestionComment
     */
    public function setUser(UserEntity $user)
    {
        $this->user = $user;

        return $this;
    }
}
