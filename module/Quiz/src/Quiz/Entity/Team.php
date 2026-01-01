<?php
namespace Quiz\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="quiz_Team")
 */
class Team
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
     * @ORM\Column(type="string", length=100)
     *
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     *
     * @var string
     */
    protected $captain;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     *
     * @var string
     */
    protected $email;

    /**
     * @ORM\ManyToOne(targetEntity="Quiz", inversedBy="teams")
     * @ORM\JoinColumn(name="quiz_quiz_id", referencedColumnName="id")
     **/
    protected $quiz;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     *
     * @var string
     */
    protected $teamId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var int
     */
    protected $paid;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var int
     */
    protected $canceled;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string
     */
    protected $referer;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var DateTime
     */
    protected $dateCreated;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var DateTime
     */
    protected $datePaid;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=2, nullable=true)
     *
     * @var float
     */
    protected $amount;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var int
     */
    protected $teamMembers;

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return Team
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * @return Team
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getCaptain()
    {
        return $this->captain;
    }

    /**
     * @param string $captain
     * @return Team
     */
    public function setCaptain($captain)
    {
        $this->captain = $captain;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return Team
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return Quiz
     */
    public function getQuiz()
    {
        return $this->quiz;
    }

    /**
     * @param Quiz $quiz
     * @return Team
     */
    public function setQuiz($quiz)
    {
        $this->quiz = $quiz;
        return $this;
    }

    /**
     * @return string
     */
    public function getTeamId()
    {
        return $this->teamId;
    }

    /**
     * @param string $teamId
     * @return Team
     */
    public function setTeamId($teamId)
    {
        $this->teamId = $teamId;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPaid()
    {
        return (bool) $this->paid;
    }

    /**
     * @param int $paid
     * @return Team
     */
    public function setPaid($paid)
    {
        $this->paid = $paid;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCanceled()
    {
        return (bool) $this->canceled;
    }

    /**
     * @param int $canceled
     * @return Team
     */
    public function setCanceled($canceled)
    {
        $this->canceled = $canceled;
        return $this;
    }

    /**
     * @return string
     */
    public function getReferer()
    {
        return $this->referer;
    }

    /**
     * @param string $referer
     * @return Team
     */
    public function setReferer($referer)
    {
        $this->referer = $referer;
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
     * @return Team
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDatePaid()
    {
        return $this->datePaid;
    }

    /**
     * @param DateTime $datePaid
     * @return Team
     */
    public function setDatePaid($datePaid)
    {
        $this->datePaid = $datePaid;
        return $this;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     * @return Team
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return int
     */
    public function getTeamMembers()
    {
        return $this->teamMembers;
    }

    /**
     * @param int $teamMembers
     * @return Team
     */
    public function setTeamMembers($teamMembers)
    {
        $this->teamMembers = $teamMembers;
        return $this;
    }
}
