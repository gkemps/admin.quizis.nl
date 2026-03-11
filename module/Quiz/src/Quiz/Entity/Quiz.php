<?php
namespace Quiz\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Quiz\Entity\QuizRound as QuizRoundEntity;
use Quiz\Entity\Question as QuestionEntity;
use Quiz\Entity\QuizLog as QuizLogEntity;
use Quiz\Entity\Team as TeamEntity;
use Quiz\Entity\Customer as CustomerEntity;

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
    protected $address;

    /**
     * @ORM\ManyToOne(targetEntity="Location")
     * @ORM\JoinColumn(name="quiz_Location_id", referencedColumnName="id")
     **/
    protected $location;

    /**
     * @ORM\ManyToOne(targetEntity="Customer")
     * @ORM\JoinColumn(name="quiz_Customer_id", referencedColumnName="id")
     **/
    protected $customer;

    /**
     * @ORM\OneToOne(targetEntity="Quiz")
     * @ORM\JoinColumn(name="quiz_quiz_id", referencedColumnName="id")
     */
    protected $copyOfQuiz;

    /**
     * @ORM\Column(type="integer")
     *
     * @var bool
     */
    protected $private;

    /**
     * @ORM\Column(type="integer")
     *
     * @var bool
     */
    protected $presentation;


    /**
     * @ORM\Column(type="datetime")
     *
     * @var DateTime
     */
    protected $date;

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $template;

    /**
     * @ORM\Column(type="integer")
     *
     * @var bool
     */
    protected $language_en_us;

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
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var int
     */
    protected $prepay;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     *
     * @var string
     */
    protected $code;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     *
     * @var string
     */
    protected $crmGroupId;

    /**
     * @ORM\Column(type="float", nullable=true)
     *
     * @var float
     */
    protected $pricePerPerson;

    /**
     * @ORM\Column(type="float", nullable=true)
     *
     * @var float
     */
    protected $pricePerTeam;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var int
     */
    protected $maxTeamMembers;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var int
     */
    protected $maxTeams;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var DateTime
     */
    protected $whitelistDeadline;

    /**
     * @ORM\Column(type="float", nullable=true)
     *
     * @var float
     */
    protected $priceInvoice;

    /**
     * @ORM\Column(type="float", nullable=true)
     *
     * @var float
     */
    protected $pricePerPersonInvoice;

    /**
     * @ORM\Column(type="float", nullable=true)
     *
     * @var float
     */
    protected $discountAmount;

    /**
     * @ORM\Column(type="float", nullable=true)
     *
     * @var float
     */
    protected $discountPercentage;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var DateTime
     */
    protected $dateInvoiced;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var DateTime|null
     */
    protected $photosOptimized;

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
     * @ORM\OneToMany(targetEntity="Team", mappedBy="quiz")
     *
     * @var ArrayCollection|TeamEntity[]
     **/
    protected $teams;

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
        foreach ($this->quizRounds as $quizRound) {
            if (stristr($quizRound->getTheme(), "foto")) {
                return $quizRound;
            }
        }

        return $this->quizRounds->get(0);
    }

    /**
     * @return mixed
     */
    public function getCopyOfQuiz()
    {
        return $this->copyOfQuiz;
    }

    /**
     * @param mixed $copyOfQuiz
     */
    public function setCopyOfQuiz($copyOfQuiz)
    {
        $this->copyOfQuiz = $copyOfQuiz;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @return bool
     */
    public function isLanguageEnUs()
    {
        return $this->language_en_us;
    }

    /**
     * @param bool $language_en_us
     */
    public function setLanguageEnUs($language_en_us)
    {
        $this->language_en_us = $language_en_us;
    }

    /**
     * @return bool
     */
    public function isPrivate()
    {
        return $this->private;
    }

    /**
     * @param bool $private
     */
    public function setPrivate($private)
    {
        $this->private = $private;
    }

    /**
     * @return bool
     */
    public function isPresentation()
    {
        return $this->presentation;
    }

    /**
     * @param bool $presentation
     */
    public function setPresentation($presentation)
    {
        $this->presentation = $presentation;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return CustomerEntity
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param CustomerEntity $customer
     * @return Quiz
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
        return $this;
    }

    /**
     * @return int
     */
    public function getPrepay()
    {
        return $this->prepay;
    }

    /**
     * @param int $prepay
     * @return Quiz
     */
    public function setPrepay($prepay)
    {
        $this->prepay = $prepay;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return Quiz
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getCrmGroupId()
    {
        return $this->crmGroupId;
    }

    /**
     * @param string $crmGroupId
     * @return Quiz
     */
    public function setCrmGroupId($crmGroupId)
    {
        $this->crmGroupId = $crmGroupId;
        return $this;
    }

    /**
     * @return float
     */
    public function getPricePerPerson()
    {
        return $this->pricePerPerson;
    }

    /**
     * @param float $pricePerPerson
     * @return Quiz
     */
    public function setPricePerPerson($pricePerPerson)
    {
        $this->pricePerPerson = ($pricePerPerson === '' || $pricePerPerson === null) ? null : $pricePerPerson;
        return $this;
    }

    /**
     * @return float
     */
    public function getPricePerTeam()
    {
        return $this->pricePerTeam;
    }

    /**
     * @param float $pricePerTeam
     * @return Quiz
     */
    public function setPricePerTeam($pricePerTeam)
    {
        $this->pricePerTeam = ($pricePerTeam === '' || $pricePerTeam === null) ? null : $pricePerTeam;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxTeamMembers()
    {
        return $this->maxTeamMembers;
    }

    /**
     * @param int $maxTeamMembers
     * @return Quiz
     */
    public function setMaxTeamMembers($maxTeamMembers)
    {
        $this->maxTeamMembers = ($maxTeamMembers === '' || $maxTeamMembers === null) ? null : (int)$maxTeamMembers;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxTeams()
    {
        return $this->maxTeams;
    }

    /**
     * @param int $maxTeams
     * @return Quiz
     */
    public function setMaxTeams($maxTeams)
    {
        $this->maxTeams = ($maxTeams === '' || $maxTeams === null) ? null : (int)$maxTeams;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getWhitelistDeadline()
    {
        return $this->whitelistDeadline;
    }

    /**
     * @param DateTime $whitelistDeadline
     * @return Quiz
     */
    public function setWhitelistDeadline($whitelistDeadline)
    {
        $this->whitelistDeadline = $whitelistDeadline;
        return $this;
    }

    /**
     * @return TeamEntity[]
     */
    public function getTeams()
    {
        return $this->teams;
    }

    /**
     * Get count of registered teams (excluding canceled)
     * @return int
     */
    public function getRegisteredTeamsCount()
    {
        if (!$this->teams) {
            return 0;
        }
        
        $count = 0;
        foreach ($this->teams as $team) {
            if (!$team->isCanceled()) {
                $count++;
            }
        }
        
        return $count;
    }

    /**
     * @return float
     */
    public function getPriceInvoice()
    {
        return $this->priceInvoice;
    }

    /**
     * @param float $priceInvoice
     * @return Quiz
     */
    public function setPriceInvoice($priceInvoice)
    {
        $this->priceInvoice = ($priceInvoice === '' || $priceInvoice === null) ? null : $priceInvoice;
        return $this;
    }

    /**
     * @return float
     */
    public function getPricePerPersonInvoice()
    {
        return $this->pricePerPersonInvoice;
    }

    /**
     * @param float $pricePerPersonInvoice
     * @return Quiz
     */
    public function setPricePerPersonInvoice($pricePerPersonInvoice)
    {
        $this->pricePerPersonInvoice = ($pricePerPersonInvoice === '' || $pricePerPersonInvoice === null) ? null : $pricePerPersonInvoice;
        return $this;
    }

    /**
     * @return float
     */
    public function getDiscountAmount()
    {
        return $this->discountAmount;
    }

    /**
     * @param float $discountAmount
     * @return Quiz
     */
    public function setDiscountAmount($discountAmount)
    {
        $this->discountAmount = ($discountAmount === '' || $discountAmount === null) ? null : $discountAmount;
        return $this;
    }

    /**
     * @return float
     */
    public function getDiscountPercentage()
    {
        return $this->discountPercentage;
    }

    /**
     * @param float $discountPercentage
     * @return Quiz
     */
    public function setDiscountPercentage($discountPercentage)
    {
        $this->discountPercentage = ($discountPercentage === '' || $discountPercentage === null) ? null : $discountPercentage;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDateInvoiced()
    {
        return $this->dateInvoiced;
    }

    /**
     * @param DateTime $dateInvoiced
     * @return Quiz
     */
    public function setDateInvoiced($dateInvoiced)
    {
        $this->dateInvoiced = $dateInvoiced;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getPhotosOptimized()
    {
        return $this->photosOptimized;
    }

    /**
     * @param DateTime|null $photosOptimized
     * @return Quiz
     */
    public function setPhotosOptimized($photosOptimized)
    {
        $this->photosOptimized = $photosOptimized;
        return $this;
    }

    /**
     * Check if this quiz can be invoiced
     * @return bool
     */
    public function canBeInvoiced()
    {
        return $this->customer !== null && 
               ($this->priceInvoice !== null || $this->pricePerPersonInvoice !== null);
    }
}
