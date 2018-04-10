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

        //quiz select
        $select = new Element\Select();
        $select->setName(self::ELEM_QUIZ);
        $select->setOptions([
            'label'            => 'kopie van',
            'column-size'      => $inputSize,
            'label_attributes' => [
                'class' => $columnSize,
            ]]);

        $quizis = $this->quizService->getAllQuizzes();

        $options = [];
        $options[] = "Nieuwe standaard quiz";
        foreach ($quizis as $quiz) {
            $options[$quiz->getId()] = $quiz->getName()." (".$quiz->getDate()->format('F Y').")";
        }
        $select->setValueOptions($options);
        $this->add($select);

        $submit = new Element\Submit(self::ELEM_SUBMIT);
        $submit->setValue('Quiz opslaan');
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
