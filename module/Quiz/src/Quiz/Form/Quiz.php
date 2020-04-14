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
    const ELEM_QUIZ = 'copyOfQuiz';
    const ELEM_TEMPLATE = 'template';
    const ELEM_LANGUAGE_EN_US = 'language_en_us';
    const ELEM_SUBMIT = 'submit';

    protected $quizService;

    public function __construct(
        EntityManagerInterface $em,
        QuizService $quizService
    ) {
        parent::__construct(self::FORM_NAME);

        $this->quizService = $quizService;

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

        $date = new DateTime();

        $this->add(
            [
                'name'       => self::ELEM_DATE,
                'options'    => [
                    'label'            => 'datum',
                    'column-size'      => $inputSize,
                    'label_attributes' => [
                        'class' => $columnSize,
                    ],
                ],
                'attributes' => [
                    'type'        => 'text',
                    'placeholder' => $date->format('d-m-Y H:00:00'),
                    'required'    => true,
                ],
            ]
        );

        //quiz template
        $select = new Element\Select();
        $select->setName(self::ELEM_TEMPLATE);
        $select->setOptions([
            'label'            => 'quiz verloop',
            'column-size'      => $inputSize,
            'label_attributes' => [
                'class' => $columnSize,
            ]]);

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

        //quiz select
        $select = new Element\Select();
        $select->setName(self::ELEM_QUIZ);
        $select->setOptions([
            'label'            => '- of kopie maken van',
            'column-size'      => $inputSize,
            'label_attributes' => [
                'class' => $columnSize,
            ]]);

        $quizis = $this->quizService->getAllQuizzes();

        $options = [];
        $options[0] = " --- Maak keuze --- ";
        foreach ($quizis as $quiz) {
            $options[$quiz->getId()] = $quiz->getName()." (".$quiz->getDate()->format('F Y').")";
        }
        $select->setValueOptions($options);
        $this->add($select);

        $this->add(
            [
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => self::ELEM_LANGUAGE_EN_US,
                'options' => array(
                    'label' => 'Engelstalig',
                    'checked_value' => '1',
                    'unchecked_value' => '0',
                ),
            ]
        );

        $submit = new Element\Submit(self::ELEM_SUBMIT);
        $submit->setValue('Quiz opslaan');
        $submit->setOptions([
                                'label'            => 'Quiz opslaan',
                                'column-size'      => $inputSize,
                                'label_attributes' => [
                                    'class' => $columnSize,
                                ]
                            ]);

        $this->add($submit);
    }
}
