<?php
namespace Quiz\Form;

use Doctrine\ORM\EntityManagerInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Form;
use Zend\Form\Element;
use Quiz\Entity\Question as QuestionEntity;
use Quiz\Service\Category as CategoryService;
use Quiz\Service\Tag as TagService;

class Question extends Form
{
    const FORM_NAME = 'question';

    const ELEM_QUESTION = 'question';
    const ELEM_ANSWER = 'answer';
    const ELEM_POINTS = 'points';
    const ELEM_CATEGORY = 'category';
    const ELEM_TAGS = 'tags';
    const ELEM_SOURCE = 'source';
    const ELEM_IMAGE = 'image';
    const ELEM_AUDIO = 'audio';
    const ELEM_SUBMIT = 'submit';

    /** @var CategoryService  */
    protected $categoryService;

    /** @var TagService  */
    protected $tagService;

    /**
     * @param EntityManagerInterface $em
     * @param CategoryService $categoryService
     * @param TagService $tagService
     */
    public function __construct(
        EntityManagerInterface $em,
        CategoryService $categoryService,
        TagService $tagService
    ) {
        parent::__construct(self::FORM_NAME);

        $this
            ->setHydrator(new DoctrineHydrator($em))
            ->setObject(new QuestionEntity())
            ->setAttribute('method', 'post');

        $this->categoryService = $categoryService;
        $this->tagService = $tagService;
    }

    public function init()
    {
        $columnSize = 'col-md-4';
        $inputSize  = 'md-6';

        $this->add(
            [
                'name'       => self::ELEM_QUESTION,
                'options'    => [
                    'label'            => 'vraag',
                    'column-size'      => $inputSize,
                    'label_attributes' => [
                        'class' => $columnSize,
                    ],
                ],
                'attributes' => [
                    'type'        => 'textarea',
                    'placeholder' => 'vraag',
                    'required'    => true,
                ],
            ]
        );

        $this->add(
            [
                'name'       => self::ELEM_ANSWER,
                'options'    => [
                    'label'            => 'antwoord',
                    'column-size'      => $inputSize,
                    'label_attributes' => [
                        'class' => $columnSize,
                    ],
                ],
                'attributes' => [
                    'type'        => 'textarea',
                    'placeholder' => 'antwoord',
                    'required'    => true,
                ],
            ]
        );

        //category select
        $select = new Element\Select();
        $select->setName(self::ELEM_CATEGORY);
        $select->setOptions([
                                'label'            => 'category',
                                'column-size'      => $inputSize,
                                'label_attributes' => [
                                    'class' => $columnSize,
                                ]]);

        $categories = $this->categoryService->getAllCategories();

        $options = [];
        foreach ($categories as $category) {
            $options[$category->getId()] = $category->getName();
        }
        $select->setValueOptions($options);
        $this->add($select);

        //tags select
        $select = new Element\Select();
        $select->setName(self::ELEM_TAGS);
        $select->setOptions([
                                'label'            => 'tags',
                                'column-size'      => $inputSize,
                                'label_attributes' => [
                                    'class' => $columnSize,
                                ]]);
        $select->setAttribute('multiple', 'multiple');

        $tags = $this->tagService->getAllTags();

        $options = [];
        foreach ($tags as $tag) {
            $options[$tag->getId()] = $tag->getName();
        }
        $select->setValueOptions($options);
        //$this->add($select);

        //points
        $this->add(
            [
                'type'  => 'Zend\Form\Element\Select',
                'name'       => self::ELEM_POINTS,
                'options'    => [
                    'label'            => 'punten',
                    'column-size'      => $inputSize,
                    'label_attributes' => [
                        'class' => $columnSize,
                    ],
                    'value_options' => array(
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                        '5' => '5',
                        '6' => '6',
                        '7' => '7',
                        '8' => '8',
                        '9' => '9',
                        '10' => '10',
                    ),
                ],
                'attributes' => [
                    'type'        => 'select',
                ],
            ]
        );

        $this->add(
            [
                'name'       => self::ELEM_SOURCE,
                'options'    => [
                    'label'            => 'bron',
                    'column-size'      => $inputSize,
                    'label_attributes' => [
                        'class' => $columnSize,
                    ],
                ],
                'attributes' => [
                    'type'        => 'text',
                    'placeholder' => 'http://nl.wikipedia.org of iets betrouwbare bron',
                ],
            ]
        );

        $this->add(
            [
                'type'       => 'Zend\Form\Element\File',
                'name'       => self::ELEM_IMAGE,
                'options'    => [
                    'label'            => 'plaatje',
                    'column-size'      => $inputSize,
                    'label_attributes' => [
                        'class' => $columnSize,
                    ],
                ],
            ]
        );

        $this->add(
            [
                'type'       => 'Zend\Form\Element\File',
                'name'       => self::ELEM_AUDIO,
                'options'    => [
                    'label'            => 'audio',
                    'column-size'      => $inputSize,
                    'label_attributes' => [
                        'class' => $columnSize,
                    ],
                ],
            ]
        );

        $submit = new Element\Submit(self::ELEM_SUBMIT);
        $submit->setValue('Vraag opslaan');
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

