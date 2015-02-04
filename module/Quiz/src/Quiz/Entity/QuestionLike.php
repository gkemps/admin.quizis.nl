<?php
namespace Quiz\Entity;

use DateTime;
use Quiz\Entity\Question as QuestionEntity;
use Quiz\Entity\User as UserEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="quiz_QuestionLike")
 */
class QuestionLike
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
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="quiz_User_id", referencedColumnName="id")
     *
     * @var UserEntity
     **/
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Question", inversedBy="questionLikes")
     * @ORM\JoinColumn(name="quiz_Question_id", referencedColumnName="id")
     *
     * @var QuestionEntity
     */
    protected $question;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var DateTime
     */
    protected $dateCreated;

    /**
     * @return DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @param DateTime $dateCreated
     * @return QuestionLike
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * @return Question
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param Question $question
     * @return QuestionLike
     */
    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return QuestionLike
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }
}
