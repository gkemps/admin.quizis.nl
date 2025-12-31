<?php
namespace Quiz\Form;

use DateTime;
use Quiz\Entity\Quiz as QuizEntity;
use Quiz\Service\Quiz as QuizService;
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
    const ELEM_TEMPLATE = 'template';
    const ELEM_LANGUAGE_EN_US = 'language_en_us';
    const ELEM_PRIVATE = 'private';
    const ELEM_PRESENTATION = 'presentation';
    const ELEM_PREPAY = 'prepay';
    const ELEM_CODE = 'code';
    const ELEM_PRICE_PER_PERSON = 'pricePerPerson';
    const ELEM_PRICE_PER_TEAM = 'pricePerTeam';
    const ELEM_MAX_TEAM_MEMBERS = 'maxTeamMembers';
    const ELEM_MAX_TEAMS = 'maxTeams';
    const ELEM_WHITELIST_DEADLINE = 'whitelistDeadline';
    const ELEM_SUBMIT = 'submit';

    protected $quizService;
    protected $entityManager;

    public function __construct(
        EntityManagerInterface $em,
        QuizService $quizService
    ) {
        parent::__construct(self::FORM_NAME);

        $this->quizService = $quizService;
        $this->entityManager = $em;

        $this
            ->setHydrator(new DoctrineHydrator($em))
            ->setObject(new QuizEntity())
            ->setAttribute('method', 'post');
    }

    public function init()
    {
        $columnSize = 'col-md-4';
        $inputSize = 'md-6';

        $this->add(
            [
                'name' => self::ELEM_NAME,
                'options' => [
                    'label' => 'naam',
                    'column-size' => $inputSize,
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

        $date = new DateTime();

        $this->add(
            [
                'name' => self::ELEM_DATE,
                'options' => [
                    'label' => 'datum',
                    'column-size' => $inputSize,
                    'label_attributes' => [
                        'class' => $columnSize,
                    ],
                ],
                'attributes' => [
                    'type' => 'text',
                    'placeholder' => $date->format('d-m-Y H:00:00'),
                    'required' => true,
                ],
            ]
        );

        //quiz template
        $select = new Element\Select();
        $select->setName(self::ELEM_TEMPLATE);
        $select->setOptions([
            'label' => 'quiz verloop',
            'column-size' => $inputSize,
            'label_attributes' => [
                'class' => $columnSize,
            ]
        ]);

        $options = [];
        $options["FVVVVVMV"] = "Standaard Quiz (FVVVVVMV)";
        $options["VVVVVVMV"] = "Standaard Quiz zonder foto  (VVVVVVMV)";
        $options["FVVVVVVV"] = "Standaard Quiz zonder muziek  (FVVVVVVV)";
        $options["FVVMVVMV"] = "Standaard Quiz 2x muziek (FVVMVVMV)";
        $options["FVVMV"] = "Korte Quiz (FVVMV)";
        $options["FVVVV"] = "Korte Quiz zonder muziek - (FVVVV)";
        $options["VVVMV"] = "Korte Quiz zonder foto - (VVVMV)";
        $options["VVVVV"] = "Korte Quiz zonder foto/muziek - (VVVVV)";
        $options["FVMV"] = "Halve Quiz (FVMV)";
        $options["FVVV"] = "Halve Quiz zonder muziek - (FVVV)";
        $options["VVMV"] = "Halve Quiz zonder foto - (VVMV)";
        $options["VVVV"] = "Halve Quiz zonder foto/muziek (VVVV)";

        $select->setValueOptions($options);
        $this->add($select);

        $select = new Element\Select();
        $select->setName(self::ELEM_LOCATION);
        $select->setOptions([
            'label' => 'locatie',
            'column-size' => $inputSize,
            'label_attributes' => [
                'class' => $columnSize,
            ]
        ]);

        $locations = $this->entityManager->getRepository('Quiz\\Entity\\Location')->findAll();
        
        $options = [];
        $options[0] = " --- Maak keuze --- ";
        foreach ($locations as $location) {
            $options[$location->getId()] = $location->toString();
        }

        $select->setValueOptions($options);

        $this->add($select);

        $this->add(
            [
                'name' => self::ELEM_CODE,
                'options' => [
                    'label' => 'code',
                    'column-size' => $inputSize,
                    'label_attributes' => [
                        'class' => $columnSize,
                    ],
                ],
                'attributes' => [
                    'type' => 'text',
                    'placeholder' => 'code',
                    'maxlength' => 50,
                ],
            ]
        );

        $this->add(
            [
                'name' => self::ELEM_PRICE_PER_PERSON,
                'options' => [
                    'label' => 'prijs per persoon',
                    'column-size' => $inputSize,
                    'label_attributes' => [
                        'class' => $columnSize,
                    ],
                ],
                'attributes' => [
                    'type' => 'number',
                    'step' => '0.01',
                    'min' => '0',
                    'placeholder' => '0.00',
                ],
            ]
        );

        $this->add(
            [
                'name' => self::ELEM_PRICE_PER_TEAM,
                'options' => [
                    'label' => 'prijs per team',
                    'column-size' => $inputSize,
                    'label_attributes' => [
                        'class' => $columnSize,
                    ],
                ],
                'attributes' => [
                    'type' => 'number',
                    'step' => '0.01',
                    'min' => '0',
                    'placeholder' => '0.00',
                ],
            ]
        );

        $this->add(
            [
                'name' => self::ELEM_MAX_TEAM_MEMBERS,
                'options' => [
                    'label' => 'max aantal teamleden',
                    'column-size' => $inputSize,
                    'label_attributes' => [
                        'class' => $columnSize,
                    ],
                ],
                'attributes' => [
                    'type' => 'number',
                    'min' => '1',
                    'placeholder' => '5',
                ],
            ]
        );

        $this->add(
            [
                'name' => self::ELEM_MAX_TEAMS,
                'options' => [
                    'label' => 'max aantal teams',
                    'column-size' => $inputSize,
                    'label_attributes' => [
                        'class' => $columnSize,
                    ],
                ],
                'attributes' => [
                    'type' => 'number',
                    'min' => '1',
                    'placeholder' => 'onbeperkt',
                ],
            ]
        );

        $this->add(
            [
                'name' => self::ELEM_WHITELIST_DEADLINE,
                'options' => [
                    'label' => 'aanmeld deadline',
                    'column-size' => $inputSize,
                    'label_attributes' => [
                        'class' => $columnSize,
                    ],
                ],
                'attributes' => [
                    'type' => 'text',
                    'placeholder' => $date->format('d-m-Y H:00:00'),
                ],
            ]
        );

        $this->add(
            [
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => self::ELEM_PRESENTATION,
                'options' => array(
                    'label' => 'Incl. presentatie',
                    'checked_value' => '1',
                    'unchecked_value' => '0',
                    'column-size' => $inputSize,
                    'label_attributes' => [
                        'class' => $columnSize,
                    ],
                ),
            ]
        );

        $this->add(
            [
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => self::ELEM_PRIVATE,
                'options' => array(
                    'label' => 'Besloten',
                    'checked_value' => '1',
                    'unchecked_value' => '0',
                    'column-size' => $inputSize,
                    'label_attributes' => [
                        'class' => $columnSize,
                    ],
                ),
            ]
        );

        $this->add(
            [
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => self::ELEM_LANGUAGE_EN_US,
                'options' => array(
                    'label' => 'Engelstalig',
                    'checked_value' => '1',
                    'unchecked_value' => '0',
                    'column-size' => $inputSize,
                    'label_attributes' => [
                        'class' => $columnSize,
                    ],
                ),
            ]
        );

        $this->add(
            [
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => self::ELEM_PREPAY,
                'options' => array(
                    'label' => 'Vooruitbetalen verplicht',
                    'checked_value' => '1',
                    'unchecked_value' => '0',
                    'column-size' => $inputSize,
                    'label_attributes' => [
                        'class' => $columnSize,
                    ],
                ),
            ]
        );

        $submit = new Element\Submit(self::ELEM_SUBMIT);
        $submit->setValue('Quiz opslaan');
        $submit->setOptions([
            'label' => 'Quiz opslaan',
            'column-size' => $inputSize,
            'label_attributes' => [
                'class' => $columnSize,
            ]
        ]);

        $this->add($submit);
    }
}
