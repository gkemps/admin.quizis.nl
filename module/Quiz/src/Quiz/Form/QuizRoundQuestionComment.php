<?php
namespace Quiz\Form;

use Doctrine\ORM\EntityManagerInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Form;
use Zend\Form\Element;
use Quiz\Entity\QuizRoundQuestion as QuizRoundQuestionEntity;

class QuizRoundQuestionComment extends Form
{
    const FORM_NAME = 'quizRoundQuestionComment';

    const ELEM_COMMENT = 'comment';
    const ELEM_SUBMIT = 'submit';

    public function __construct(
        EntityManagerInterface $em
    ) {
        parent::__construct(self::FORM_NAME);

        $this
            ->setHydrator(new DoctrineHydrator($em))
            ->setObject(new QuizRoundQuestionEntity())
            ->setAttribute('method', 'post');
    }

    public function init()
    {
        $columnSize = 'col-md-4';
        $inputSize  = 'md-6';

        $this->add(
            [
                'name'       => self::ELEM_COMMENT,
                'options'    => [
                    'label'            => 'comment',
                    'column-size'      => $inputSize,
                    'label_attributes' => [
                        'class' => $columnSize,
                    ],
                ],
                'attributes' => [
                    'type'        => 'textarea',
                    'placeholder' => 'comment',
                    'required'    => true,
                ],
            ]
        );

        $submit = new Element\Submit(self::ELEM_SUBMIT);
        $submit->setValue('Comment plaatsen');
        $submit->setOptions([
                                'label'            => ' ',
                                'column-size'      => $inputSize,
                                'label_attributes' => [
                                    'class' => $columnSize,
                                ]
                            ]);

        $this->add($submit);
    }
}
