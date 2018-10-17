<?php
namespace Quiz\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="quiz_ThemeRoundQuestion")
 */
class ThemeRoundQuestion
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
     * @ORM\ManyToOne(targetEntity="ThemeRound", inversedBy="themeRoundQuestions")
     * @ORM\JoinColumn(name="quiz_ThemeRound_id", referencedColumnName="id")
     **/
    protected $themeRound;

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
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return ThemeRound
     */
    public function getThemeRound()
    {
        return $this->themeRound;
    }

    /**
     * @param mixed $themeRound
     */
    public function setThemeRound($themeRound): void
    {
        $this->themeRound = $themeRound;
    }

    /**
     * @return Question
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param mixed $question
     */
    public function setQuestion($question): void
    {
        $this->question = $question;
    }

    /**
     * @return int
     */
    public function getQuestionNumber(): int
    {
        return $this->questionNumber;
    }

    /**
     * @param int $questionNumber
     */
    public function setQuestionNumber(int $questionNumber): void
    {
        $this->questionNumber = $questionNumber;
    }
}