<?php
namespace Quiz\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Quiz\Entity\Quiz as QuizEntity;
use Quiz\Entity\User as UserEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="quiz_QuizLog")
 */
class QuizLog
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
    protected $text;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $icon;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="quiz_User_id", referencedColumnName="id")
     **/
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Quiz", inversedBy="quizLogs")
     * @ORM\JoinColumn(name="quiz_Quiz_id", referencedColumnName="id")
     **/
    protected $quiz;

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
     * @return QuizLog
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
        $this->dateCreated->setTimezone(new \DateTimeZone('UTC'));

        return $this;
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     * @return QuizLog
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

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
     * @return QuizLog
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return QuizLog
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     * @return QuizLog
     */
    public function setUser(UserEntity $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getQuiz()
    {
        return $this->quiz;
    }

    /**
     * @param mixed $quiz
     * @return QuizLog
     */
    public function setQuiz(QuizEntity $quiz)
    {
        $this->quiz = $quiz;

        return $this;
    }
}
