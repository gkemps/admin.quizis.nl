<?php
namespace Quiz\Entity;

use Quiz\Entity\Question as QuestionEntity;
use Quiz\Entity\Tag as TagEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="quiz_QuestionTag")
 */
class QuestionTag
{

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Question", inversedBy="questionTags")
     * @ORM\JoinColumn(name="quiz_Question_id", referencedColumnName="id")
     *
     * @var QuestionEntity
     */
    protected $question;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Tag", inversedBy="questionTags")
     * @ORM\JoinColumn(name="quiz_Tag_id", referencedColumnName="id")
     *
     * @var TagEntity
     */
    protected $tag;

    /**
     * @return QuestionEntity
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @return TagEntity
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param QuestionEntity $question
     * @return QuestionTag
     */
    public function setQuestion(QuestionEntity $question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * @param TagEntity $tag
     * @return QuestionTag
     */
    public function setTag(TagEntity $tag)
    {
        $this->tag = $tag;

        return $this;
    }
}
