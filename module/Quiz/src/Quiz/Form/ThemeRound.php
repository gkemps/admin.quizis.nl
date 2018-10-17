<?php
namespace Quiz\Form;

use Doctrine\ORM\EntityManagerInterface;
use Quiz\Service\ThemeRoundService;
use Quiz\Entity\ThemeRound as ThemeRoundEntity;
use Zend\Form\Form;
use Zend\Form\Element;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class ThemeRound extends Form
{
    const FORM_NAME = 'themeRound';

    const ELEM_NAME = 'name';
    const ELEM_AUDIO = 'audio';
    const ELEM_PHOTO = 'photo';
    const ELEM_SUBMIT = 'submit';

    protected $themeRoundService;

    public function __construct(
        EntityManagerInterface $em,
        ThemeRoundService $themeRoundService
    ) {
        parent::__construct(self::FORM_NAME);

        $this->themeRoundService = $themeRoundService;

        $this
            ->setHydrator(new DoctrineHydrator($em))
            ->setObject(new ThemeRoundEntity())
            ->setAttribute('method', 'post');
    }

    public function init()
    {
        $columnSize = 'col-md-1';
        $inputSize = 'md-6';

        $this->add(
            [
                'name' => self::ELEM_NAME,
                'options' => [
                    'label' => 'naam',
                    'column-size'      => $inputSize,
                    'label_attributes' => [
                        'class' => $columnSize,
                    ],
                ],
                'attributes' => [
                    'type' => 'text',
                    'placeholder' => 'naam',
                    'required' => true,
                ],
            ]
        );

        $this->add(
            [
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => self::ELEM_AUDIO,
                'options' => array(
                    'label' => 'Audio ronde',
                    //'use_hidden_element' => true,
                    'checked_value' => '1',
                    'unchecked_value' => '0',
                )
            ]
        );

        $this->add(
            [
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => self::ELEM_PHOTO,
                'options' => array(
                    'label' => 'Foto ronde',
                    //'use_hidden_element' => true,
                    'checked_value' => '1',
                    'unchecked_value' => '0',
                )
            ]
        );

        $submit = new Element\Submit(self::ELEM_SUBMIT);
        $submit->setValue('Thema ronde opslaan');
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