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
    const ELEM_CUSTOMER = 'customer';
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
    const ELEM_PRICE_INVOICE = 'priceInvoice';
    const ELEM_PRICE_PER_PERSON_INVOICE = 'pricePerPersonInvoice';
    const ELEM_DISCOUNT_AMOUNT = 'discountAmount';
    const ELEM_DISCOUNT_PERCENTAGE = 'discountPercentage';
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
                'type' => 'Zend\Form\Element\Text',
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

        // Customer dropdown
        $select = new Element\Select();
        $select->setName(self::ELEM_CUSTOMER);
        $select->setOptions([
            'label' => 'klant',
            'column-size' => $inputSize,
            'label_attributes' => [
                'class' => $columnSize,
            ]
        ]);

        $customers = $this->entityManager->getRepository('Quiz\\Entity\\Customer')->findAll();
        
        $options = [];
        $options[0] = " --- Geen klant geselecteerd --- ";
        foreach ($customers as $customer) {
            $options[$customer->getId()] = $customer->getName();
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
                'name' => self::ELEM_PRICE_INVOICE,
                'options' => [
                    'label' => 'factuur bedrag',
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
                'name' => self::ELEM_PRICE_PER_PERSON_INVOICE,
                'options' => [
                    'label' => 'factuur bedrag per persoon',
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
                'name' => self::ELEM_DISCOUNT_AMOUNT,
                'options' => [
                    'label' => 'kortings bedrag',
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
                'name' => self::ELEM_DISCOUNT_PERCENTAGE,
                'options' => [
                    'label' => 'kortings percentage',
                    'column-size' => $inputSize,
                    'label_attributes' => [
                        'class' => $columnSize,
                    ],
                ],
                'attributes' => [
                    'type' => 'number',
                    'step' => '0.01',
                    'min' => '0',
                    'max' => '100',
                    'placeholder' => '0',
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

    public function bind($object, $flags = \Zend\Form\FormInterface::VALUES_NORMALIZED)
    {
        // Call parent bind first
        $result = parent::bind($object, $flags);
        
        // Convert DateTime objects to strings and entities to IDs after binding
        if ($object instanceof QuizEntity) {
            // Convert date DateTime to string
            $dateElement = $this->get(self::ELEM_DATE);
            if ($dateElement->getValue() instanceof \DateTime) {
                $dateElement->setValue($dateElement->getValue()->format('d-m-Y H:i:s'));
            }
            
            // Convert whitelist deadline DateTime to string
            $whitelistElement = $this->get(self::ELEM_WHITELIST_DEADLINE);
            if ($whitelistElement->getValue() instanceof \DateTime) {
                $whitelistElement->setValue($whitelistElement->getValue()->format('d-m-Y H:i:s'));
            }
            
            // Convert Location entity to ID
            $locationElement = $this->get(self::ELEM_LOCATION);
            $locationValue = $locationElement->getValue();
            if (is_object($locationValue) && method_exists($locationValue, 'getId')) {
                $locationElement->setValue($locationValue->getId());
            }
            
            // Convert Customer entity to ID
            $customerElement = $this->get(self::ELEM_CUSTOMER);
            $customerValue = $customerElement->getValue();
            if (is_object($customerValue) && method_exists($customerValue, 'getId')) {
                $customerElement->setValue($customerValue->getId());
            }
            
            // Make template field disabled for existing quizzes (rondes are already created)
            if ($object->getId() !== null) {
                $templateElement = $this->get(self::ELEM_TEMPLATE);
                $templateElement->setAttribute('disabled', 'disabled');
            }
        }
        
        return $result;
    }
}
