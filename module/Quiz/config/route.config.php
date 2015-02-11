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
                    'yours' => [
                        'type' => 'Literal',
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/jouw-vragen',
                            'defaults' => [
                                'controller' => 'Quiz\Controller\Question',
                                'action'     => 'yours',
                            ],
                        ],
                    ],
                    'liked' => [
                        'type' => 'Literal',
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/geliket',
                            'defaults' => [
                                'controller' => 'Quiz\Controller\Question',
                                'action'     => 'liked',
                            ],
                        ],
                    ],
                    'likes' => [
                        'type' => 'Literal',
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/met-likes',
                            'defaults' => [
                                'controller' => 'Quiz\Controller\Question',
                                'action'     => 'likes',
                            ],
                        ],
                    ],
                    'not-asked' => [
                        'type' => 'Literal',
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/niet-gesteld',
                            'defaults' => [
                                'controller' => 'Quiz\Controller\Question',
                                'action'     => 'notAsked',
                            ],
                        ],
                    ],
                    'no-source' => [
                        'type' => 'Literal',
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/geen-bron',
                            'defaults' => [
                                'controller' => 'Quiz\Controller\Question',
                                'action'     => 'noSource',
                            ],
                        ],
                    ],
                    'search' => [
                        'type' => 'Segment',
                        'options' => [
                            'route'    => '/zoeken/:term',
                            'defaults' => [
                                'controller' => 'Quiz\Controller\Question',
                                'action'     => 'search',
                            ],
                        ],
                    ],
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
                        'child_routes' => [
                            'print-questions' => [
                                'type' => 'Literal',
                                'options' => [
                                    'route'    => '/print-vragen',
                                    'defaults' => [
                                        'controller' => 'Quiz\Controller\Quiz',
                                        'action'     => 'printQuestions',
                                    ],
                                ],
                            ]
                        ]
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
                    ],
                    'remove-question' => [
                        'type' => 'Segment',
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/vraag-verwijderen/:quizRoundQuestionId',
                            'constraints' => [
                                'quizRoundQuestionId' => '\d+'
                            ],
                            'defaults' => [
                                'controller' => 'Quiz\Controller\Quiz',
                                'action'     => 'removeQuizRoundQuestion',
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
            ],

            'comment' => [
                'type' => 'Segment',
                'may_terminate' => true,
                'options' => [
                    'route'    => '/comment/:quizRoundQuestionId',
                    'constraints' => [
                        'quizRoundQuestionId' => '\d+'
                    ],
                    'defaults' => [
                        'controller' => 'Quiz\Controller\QuizRoundQuestionComment',
                        'action'     => 'detail',
                    ],
                ],
                'child_routes' => [
                    'process' => [
                        'type' => 'Segment',
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/process',
                            'defaults' => [
                                'controller' => 'Quiz\Controller\QuizRoundQuestionComment',
                                'action'     => 'process',
                            ],
                        ],
                    ]
                ]
            ]

        ],
    ],
];