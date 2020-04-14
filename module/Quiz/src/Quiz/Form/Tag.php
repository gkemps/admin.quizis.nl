<?php
namespace Quiz\Form;

use Doctrine\ORM\EntityManagerInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Quiz\Service\Tag as TagService;
use Quiz\Entity\Tag as TagEntity;
use Zend\Form\Form;
use Zend\Form\Element;

class Tag extends Form
{
    const FORM_NAME = 'tag';

    const ELEM_NAME = 'name';
    const ELEM_ICON = 'icon';
    const ELEM_ACTIVE = 'active';
    const ELEM_SUBMIT = 'submit';

    protected $tagService;

    public function __construct(
        EntityManagerInterface $em,
        TagService $tagService
    ) {
        parent::__construct(self::FORM_NAME);

        $this
            ->setHydrator(new DoctrineHydrator($em))
            ->setObject(new TagEntity())
            ->setAttribute('method', 'post');

        $this->tagService = $tagService;
    }

    public function init()
    {
        $columnSize = 'col-md-1';
        $inputSize  = 'md-6';

        $this->add(
            [
                'name'       => self::ELEM_NAME,
                'options'    => [
                    'label'            => 'name',
                    'column-size'      => $inputSize,
                    'label_attributes' => [
                        'class' => $columnSize,
                    ],
                ],
                'attributes' => [
                    'type'        => 'text',
                    'placeholder' => 'tag',
                    'required'    => true,
                ],
            ]
        );
        $this->add(
            [
                'name'       => self::ELEM_ICON,
                'options'    => [
                    'label'            => 'icon',
                    'column-size'      => $inputSize,
                    'label_attributes' => [
                        'class' => $columnSize,
                    ],
                ],
                'attributes' => [
                    'type'        => 'text',
                    'placeholder' => 'fa-xxxx',
                    'required'    => true,
                ],
            ]
        );
        $this->add(
            [
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => self::ELEM_ACTIVE,
                'options' => array(
                    'label' => 'Actief',
                    'checked_value' => '1',
                    'unchecked_value' => '0',
                ),
            ]
        );

        $submit = new Element\Submit(self::ELEM_SUBMIT);
        $submit->setValue('Tag opslaan');
        $submit->setOptions([
                                'label'            => 'Tag opslaan',
                                'column-size'      => $inputSize,
                                'label_attributes' => [
                                    'class' => $columnSize,
                                ]
                            ]);

        $this->add($submit);
    }
}
