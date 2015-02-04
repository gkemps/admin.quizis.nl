<?php
return [
    'router' => [
        'routes' => [

            'home' => [
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => 'Quiz\Controller\Question',
                        'action'     => 'index',
                    ],
                ],
            ],

            'search' => [
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => 'Quiz\Controller\Question',
                        'action'     => 'index',
                    ],
                ],
            ],

            'category' => [
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => [
                    'route'    => '/cats',
                    'defaults' => [
                        'controller' => 'Quiz\Controller\Category',
                        'action'     => 'index',
                    ],
                ],
            ],

            'tag' => [
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'may_terminate' => true,
                'options' => [
                    'route'    => '/tags',
                    'defaults' => [
                        'controller' => 'Quiz\Controller\Tag',
                        'action'     => 'index',
                    ],
                ],
                'child_routes' => [
                    'form' => [
                        'type'      => 'Literal',
                        'priority'  => 1000,
                        'may_terminate' => true,
                        'options'   => [
                            'route' => '/form',
                            'defaults' => [
                                'controller' => 'Quiz\Controller\Tag',
                                'action'     => 'form',
                            ],
                        ],
                        'child_routes' => [
                            'process' => [
                                'type'      => 'Literal',
                                'priority'  => 1000,
                                'may_terminate' => true,
                                'options'   => [
                                    'route' => '/process',
                                    'defaults' => [
                                        'controller' => 'Quiz\Controller\Tag',
                                        'action'     => 'process',
                                    ],
                                ],
                            ]
                        ]
                    ]
                ]
            ],

            'question' => [
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'may_terminate' => true,
                'options' => [
                    'route'    => '/vragen',
                    'defaults' => [
                        'controller' => 'Quiz\Controller\Question',
                        'action'     => 'index',
                    ],
                ],
                'child_routes' => [
                    'detail' => [
                        'type' => 'Segment',
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/:questionId',
                            'constraints' => [
                                'questionId' => '\d+'
                            ],
                            'defaults' => [
                                'controller' => 'Quiz\Controller\Question',
                                'action'     => 'detail',
                            ],
                        ],
                    ],
                    'like' => [
                        'type' => 'Segment',
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/like/:questionId',
                            'constraints' => [
                                'questionId' => '\d+'
                            ],
                            'defaults' => [
                                'controller' => 'Quiz\Controller\Question',
                                'action'     => 'like',
                            ],
                        ],
                    ],
                    'unlike' => [
                        'type' => 'Segment',
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/unlike/:questionId',
                            'constraints' => [
                                'questionId' => '\d+'
                            ],
                            'defaults' => [
                                'controller' => 'Quiz\Controller\Question',
                                'action'     => 'unlike',
                            ],
                        ],
                    ],
                    'add-to-quiz-round' => [
                        'type' => 'Segment',
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/:questionId/toevoegen-aan-quiz-ronde/:quizRoundId',
                            'constraints' => [
                                'quizRoundId' => '\d+'
                            ],
                            'defaults' => [
                                'controller' => 'Quiz\Controller\Question',
                                'action'     => 'addToQuizRound',
                            ],
                        ],
                    ],
                    'cats' => [
                        'type' => 'Segment',
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/cats/:catId',
                            'constraints' => [
                                'catId' => '\d+'
                            ],
                            'defaults' => [
                                'controller' => 'Quiz\Controller\Question',
                                'action'     => 'questionsByCategory',
                            ],
                        ],
                    ],
                    'form' => [
                        'type'      => 'Literal',
                        'priority'  => 1000,
                        'may_terminate' => true,
                        'options'   => [
                            'route' => '/form',
                            'defaults' => [
                                'controller' => 'Quiz\Controller\Question',
                                'action'     => 'form',
                            ],
                        ],
                        'child_routes' => [
                            'process' => [
                                'type'      => 'Literal',
                                'priority'  => 1000,
                                'may_terminate' => true,
                                'options'   => [
                                    'route' => '/process',
                                    'defaults' => [
                                        'controller' => 'Quiz\Controller\Question',
                                        'action'     => 'process',
                                    ],
                                ],
                            ]
                        ]
                    ]
                ]
            ],

            'quiz' => [
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'may_terminate' => true,
                'options' => [
                    'route'    => '/quiz',
                    'defaults' => [
                        'controller' => 'Quiz\Controller\Quiz',
                        'action'     => 'index',
                    ],
                ],
                'child_routes' => [
                    'detail' => [
                        'type' => 'Segment',
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/:quizId',
                            'constraints' => [
                                'catId' => '\d+'
                            ],
                            'defaults' => [
                                'controller' => 'Quiz\Controller\Quiz',
                                'action'     => 'detail',
                            ],
                        ],
                    ],
                    'next-quiz' => [
                        'type' => 'Segment',
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/volgende-quiz',
                            'constraints' => [
                                'catId' => '\d+'
                            ],
                            'defaults' => [
                                'controller' => 'Quiz\Controller\Quiz',
                                'action'     => 'nextQuiz',
                            ],
                        ],
                    ]
                ]
            ],

            'reset-quiz-round-question-number' => [
                'type' => 'Segment',
                'may_terminate' => true,
                'options' => [
                    'route'    => '/reset-quiz-round-question-number/:quizRoundQuestionId/:newPosition',
                    'constraints' => [
                        'quizRoundQuestionId' => '\d+',
                        'newPosition' => '\d+'
                    ],
                    'defaults' => [
                        'controller' => 'Quiz\Controller\Quiz',
                        'action'     => 'resetQuizRoundQuestionNumber',
                    ],
                ],
            ]

        ],
    ],
];