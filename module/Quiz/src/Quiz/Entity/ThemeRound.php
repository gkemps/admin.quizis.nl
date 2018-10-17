<?php
namespace Quiz\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity
 * @ORM\Table(name="quiz_ThemeRound")
 */
class ThemeRound
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
     * @ORM\Column(type="integer")
     *
     * @var bool
     */
    protected $audio;

    /**
     * @ORM\Column(type="integer")
     *
     * @var bool
     */
    protected $photo;

    /**
     * @ORM\OneToMany(targetEntity="ThemeRoundQuestion", mappedBy="themeRound", cascade="persist")
     * @ORM\OrderBy({"questionNumber" = "ASC"})
     *
     * @var Collection
     **/
    protected $themeRoundQuestions;

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
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
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return bool
     */
    public function isAudio()
    {
        return $this->audio == "1";
    }

    /**
     * @param bool $audio
     */
    public function setAudio($audio)
    {
        $this->audio = $audio;
    }

    /**
     * @return bool
     */
    public function isPhoto()
    {
        return $this->photo == "1";
    }

    /**
     * @param bool $photo
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    /**
     * @return Collection|ThemeRoundQuestion[]
     */
    public function getThemeRoundQuestions()
    {
        return $this->themeRoundQuestions;
    }

    /**
     * @param Question $question
     * @return bool
     */
    public function containsQuestion(Question $question)
    {
        /** @var ThemeRoundQuestion $themeRoundQuestion */
        foreach ($this->themeRoundQuestions as $themeRoundQuestion) {
            if ($themeRoundQuestion->getQuestion()->getId() == $question->getId()) {
                return true;
            }
        }

        return false;
    }

    public function getPercentageFull()
    {
        return count($this->getThemeRoundQuestions()) * 10;
    }

    /**
     * @param ThemeRoundQuestion $themeRoundQuestion
     * @return ThemeRoundQuestion
     */
    public function addThemeRoundQuestion(ThemeRoundQuestion $themeRoundQuestion)
    {
        $themeRoundQuestion->setThemeRound($this);


        $this->themeRoundQuestions->add($themeRoundQuestion);

        return $themeRoundQuestion;
    }

    /**
     * @return float|int
     */
    public function getNextQuestionNumber()
    {
        $spots = range(1, QuizRound::MAX_QUESTIONS);

        $occupied = [];
        foreach ($this->getThemeRoundQuestions() as $themeRoundQuestion) {
            $occupied[] = $themeRoundQuestion->getQuestionNumber() / 10;
        }

        return min(array_diff($spots, $occupied)) * 10;
    }
}