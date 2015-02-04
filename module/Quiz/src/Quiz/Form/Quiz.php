<?php
namespace Quiz\Form;

use Quiz\Entity\Quiz as QuizEntity;
use Doctrine\ORM\EntityManagerInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Form;
use Zend\Form\Element;

class Quiz extends Form
{
    const FORM_NAME = 'tag';

    const ELEM_NAME = 'name';
    const ELEM_LOCATION = 'location';
    const ELEM_DATE = 'date';
    const ELEM_SUBMIT = 'submit';

    public function __construct(
        EntityManagerInterface $em
    ) {
        parent::__construct(self::FORM_NAME);

        $this
            ->setHydrator(new DoctrineHydrator($em))
            ->setObject(new QuizEntity())
            ->setAttribute('method', 'post');
    }

    public function init()
    {
        $columnSize = 'col-md-4';
        $inputSize  = 'md-6';

        $this->add(
            [
                'name'       => self::ELEM_NAME,
                'options'    => [
                    'label'            => 'naam',
                    'column-size'      => $inputSize,
                    'label_attributes' => [
                        'class' => $columnSize,
                    ],
                ],
                'attributes' => [
                    'type'        => 'text',
                    'placeholder' => 'naam',
                    'required'    => true,
                ],
            ]
        );

        $this->add(
            [
                'name'       => self::ELEM_LOCATION,
                'options'    => [
                    'label'            => 'locatie',
                    'column-size'      => $inputSize,
                    'label_attributes' => [
                        'class' => $columnSize,
                    ],
                ],
                'attributes' => [
                    'type'        => 'text',
                    'placeholder' => 'locatie',
                    'required'    => true,
                ],
            ]
        );

        $submit = new Element\Submit(self::ELEM_SUBMIT);
        $submit->setValue('Tag opslaan');
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
