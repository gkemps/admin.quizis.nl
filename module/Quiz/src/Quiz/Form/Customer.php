<?php
namespace Quiz\Form;

use Doctrine\ORM\EntityManagerInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Quiz\Entity\Customer as CustomerEntity;
use Zend\Form\Form;
use Zend\Form\Element;

class Customer extends Form
{
    const FORM_NAME = 'customer';

    const ELEM_NAME = 'name';
    const ELEM_ADDRESS = 'address';
    const ELEM_POSTAL_CODE = 'postalCode';
    const ELEM_CITY = 'city';
    const ELEM_EMAIL = 'email';
    const ELEM_PHONE = 'phone';
    const ELEM_CONTACT_PERSON = 'contactPerson';
    const ELEM_SUBMIT = 'submit';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct(self::FORM_NAME);

        $this
            ->setHydrator(new DoctrineHydrator($em))
            ->setObject(new CustomerEntity())
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
                    'label'            => 'Naam',
                    'column-size'      => $inputSize,
                    'label_attributes' => [
                        'class' => $columnSize,
                    ],
                ],
                'attributes' => [
                    'type'        => 'text',
                    'placeholder' => 'Naam',
                    'required'    => true,
                ],
            ]
        );

        $this->add(
            [
                'name'       => self::ELEM_ADDRESS,
                'options'    => [
                    'label'            => 'Adres',
                    'column-size'      => $inputSize,
                    'label_attributes' => [
                        'class' => $columnSize,
                    ],
                ],
                'attributes' => [
                    'type'        => 'text',
                    'placeholder' => 'Straat en huisnummer',
                ],
            ]
        );

        $this->add(
            [
                'name'       => self::ELEM_POSTAL_CODE,
                'options'    => [
                    'label'            => 'Postcode',
                    'column-size'      => $inputSize,
                    'label_attributes' => [
                        'class' => $columnSize,
                    ],
                ],
                'attributes' => [
                    'type'        => 'text',
                    'placeholder' => '1234 AB',
                    'maxlength'   => 20,
                ],
            ]
        );

        $this->add(
            [
                'name'       => self::ELEM_CITY,
                'options'    => [
                    'label'            => 'Woonplaats',
                    'column-size'      => $inputSize,
                    'label_attributes' => [
                        'class' => $columnSize,
                    ],
                ],
                'attributes' => [
                    'type'        => 'text',
                    'placeholder' => 'Woonplaats',
                    'maxlength'   => 100,
                ],
            ]
        );

        $this->add(
            [
                'name'       => self::ELEM_EMAIL,
                'options'    => [
                    'label'            => 'E-mailadres',
                    'column-size'      => $inputSize,
                    'label_attributes' => [
                        'class' => $columnSize,
                    ],
                ],
                'attributes' => [
                    'type'        => 'email',
                    'placeholder' => 'email@voorbeeld.nl',
                ],
            ]
        );

        $this->add(
            [
                'name'       => self::ELEM_PHONE,
                'options'    => [
                    'label'            => 'Telefoonnummer',
                    'column-size'      => $inputSize,
                    'label_attributes' => [
                        'class' => $columnSize,
                    ],
                ],
                'attributes' => [
                    'type'        => 'tel',
                    'placeholder' => '06-12345678',
                    'maxlength'   => 50,
                ],
            ]
        );

        $this->add(
            [
                'name'       => self::ELEM_CONTACT_PERSON,
                'options'    => [
                    'label'            => 'Contactpersoon',
                    'column-size'      => $inputSize,
                    'label_attributes' => [
                        'class' => $columnSize,
                    ],
                ],
                'attributes' => [
                    'type'        => 'text',
                    'placeholder' => 'Naam contactpersoon',
                ],
            ]
        );

        $submit = new Element\Submit(self::ELEM_SUBMIT);
        $submit->setValue('Klant opslaan');
        $submit->setOptions([
            'label'            => 'Klant opslaan',
            'column-size'      => $inputSize,
            'label_attributes' => [
                'class' => $columnSize,
            ]
        ]);

        $this->add($submit);
    }
}
