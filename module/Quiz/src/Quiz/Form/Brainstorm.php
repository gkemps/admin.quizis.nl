<?php
namespace Quiz\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;

class Brainstorm extends Form
{
    const FORM_NAME = 'brainstorm';
    const ELEM_URL = 'url';
    const ELEM_NUM_QUESTIONS = 'num_questions';
    const ELEM_SUBMIT = 'submit';

    public function __construct()
    {
        parent::__construct(self::FORM_NAME);

        $this->setAttribute('method', 'post');
    }

    public function init()
    {
        $columnSize = 'col-md-2';
        $inputSize  = 'col-md-8';

        // URL input field
        $this->add([
            'name' => self::ELEM_URL,
            'type' => 'text',
            'options' => [
                'label' => 'Webpagina URL',
                'column-size' => $inputSize,
                'label_attributes' => [
                    'class' => $columnSize,
                ],
            ],
            'attributes' => [
                'placeholder' => 'https://example.com/artikel',
                'required' => true,
                'class' => 'form-control',
            ],
        ]);

        // Number of questions select
        $this->add([
            'name' => self::ELEM_NUM_QUESTIONS,
            'type' => 'select',
            'options' => [
                'label' => 'Aantal vragen',
                'column-size' => $inputSize,
                'label_attributes' => [
                    'class' => $columnSize,
                ],
                'value_options' => [
                    '1' => '1 vraag',
                    '2' => '2 vragen',
                    '3' => '3 vragen',
                    '4' => '4 vragen',
                    '5' => '5 vragen',
                    '6' => '6 vragen',
                    '7' => '7 vragen',
                    '8' => '8 vragen',
                    '9' => '9 vragen',
                    '10' => '10 vragen',
                ],
            ],
            'attributes' => [
                'value' => '5',
                'class' => 'form-control',
            ],
        ]);

        // Submit button
        $submit = new Element\Submit(self::ELEM_SUBMIT);
        $submit->setValue('Genereer Vragen');
        $submit->setOptions([
            'label' => 'Genereer Vragen',
            'column-size' => $inputSize,
            'label_attributes' => [
                'class' => $columnSize,
            ]
        ]);
        $submit->setAttribute('class', 'btn btn-primary');

        $this->add($submit);

        // Input filter for validation
        $inputFilter = new InputFilter();
        
        $inputFilter->add([
            'name' => self::ELEM_URL,
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'NotEmpty',
                    'options' => [
                        'messages' => [
                            \Zend\Validator\NotEmpty::IS_EMPTY => 'URL is verplicht',
                        ],
                    ],
                ],
                [
                    'name' => 'Uri',
                    'options' => [
                        'allowRelative' => false,
                        'messages' => [
                            \Zend\Validator\Uri::INVALID => 'Ongeldige URL',
                            \Zend\Validator\Uri::NOT_URI => 'Geen geldige URL',
                        ],
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => self::ELEM_NUM_QUESTIONS,
            'required' => true,
            'filters' => [
                ['name' => 'ToInt'],
            ],
            'validators' => [
                [
                    'name' => 'Between',
                    'options' => [
                        'min' => 1,
                        'max' => 10,
                    ],
                ],
            ],
        ]);

        $this->setInputFilter($inputFilter);
    }
}
