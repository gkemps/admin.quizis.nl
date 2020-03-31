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
                    'image' => [
                        'type' => 'Literal',
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/afbeelding',
                            'defaults' => [
                                'controller' => 'Quiz\Controller\Question',
                                'action'     => 'image',
                            ],
                        ],
                    ],
                    'audio' => [
                        'type' => 'Literal',
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/audio',
                            'defaults' => [
                                'controller' => 'Quiz\Controller\Question',
                                'action'     => 'audio',
                            ],
                        ],
                    ],
                    'most-asked' => [
                        'type' => 'Literal',
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/most-asked',
                            'defaults' => [
                                'controller' => 'Quiz\Controller\Question',
                                'action'     => 'mostAsked',
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
                    'add-to-theme-round' => [
                        'type' => 'Segment',
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/:questionId/toevoegen-aan-thema-ronde/:themeRoundId',
                            'constraints' => [
                                'themeRoundId' => '\d+'
                            ],
                            'defaults' => [
                                'controller' => 'Quiz\Controller\Question',
                                'action'     => 'addToThemeRound',
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
                            ],
                            'print-answers' => [
                                'type' => 'Literal',
                                'options' => [
                                    'route'    => '/print-antwoorden',
                                    'defaults' => [
                                        'controller' => 'Quiz\Controller\Quiz',
                                        'action'     => 'printAnswers',
                                    ],
                                ],
                            ],
                            'print-photos' => [
                                'type' => 'Literal',
                                'options' => [
                                    'route'    => '/print-foto-ronde',
                                    'defaults' => [
                                        'controller' => 'Quiz\Controller\Quiz',
                                        'action'     => 'printPhotos',
                                    ],
                                ],
                            ],
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
                    ],
                    'form' => [
                        'type'      => 'Literal',
                        'priority'  => 1000,
                        'may_terminate' => true,
                        'options'   => [
                            'route' => '/form',
                            'defaults' => [
                                'controller' => 'Quiz\Controller\Quiz',
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
                                        'controller' => 'Quiz\Controller\Quiz',
                                        'action'     => 'process',
                                    ],
                                ],
                            ]
                        ]
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
            'reset-theme-round-question-number' => [
                'type' => 'Segment',
                'may_terminate' => true,
                'options' => [
                    'route'    => '/reset-theme-round-question-number/:themeRoundQuestionId/:newPosition',
                    'constraints' => [
                        'quizRoundQuestionId' => '\d+',
                        'newPosition' => '\d+'
                    ],
                    'defaults' => [
                        'controller' => 'Quiz\Controller\ThemeRound',
                        'action'     => 'resetThemeRoundQuestionNumber',
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
            ],

            'download-mp3' => [
                'type' => 'Segment',
                'may_terminate' => true,
                'options' => [
                    'route'    => '/download-mp3/:questionId',
                    'constraints' => [
                        'questionId' => '\d+'
                    ],
                    'defaults' => [
                        'controller' => 'Quiz\Controller\Question',
                        'action'     => 'downloadMp3',
                    ],
                ],
            ],

            'download-mp3-round' => [
                'type' => 'Segment',
                'may_terminate' => true,
                'options' => [
                    'route'    => '/download-mp3-round/:quizRoundId',
                    'constraints' => [
                        'quizRoundId' => '\d+'
                    ],
                    'defaults' => [
                        'controller' => 'Quiz\Controller\QuizRound',
                        'action'     => 'downloadMp3',
                    ],
                ],
            ],

            'import' => [
                'type' => 'Segment',
                'may_terminate' => true,
                'options' => [
                    'route'    => '/import',
                    'defaults' => [
                        'controller' => 'Quiz\Controller\Temp',
                        'action'     => 'import',
                    ],
                ],
            ],

            'theme-rounds' => [
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'may_terminate' => true,
                'options' => [
                    'route'    => '/thema-rondes',
                    'defaults' => [
                        'controller' => 'Quiz\Controller\ThemeRound',
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
                                'controller' => 'Quiz\Controller\ThemeRound',
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
                                        'controller' => 'Quiz\Controller\ThemeRound',
                                        'action'     => 'process',
                                    ],
                                ],
                            ]
                        ]
                    ],
                    'detail' => [
                        'type' => 'Segment',
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/:themeRoundId',
                            'constraints' => [
                                'catId' => '\d+'
                            ],
                            'defaults' => [
                                'controller' => 'Quiz\Controller\ThemeRound',
                                'action'     => 'detail',
                            ],
                        ],
                    ],
                    'remove-question' => [
                        'type' => 'Segment',
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/remove/:themeRoundQuestionId',
                            'constraints' => [
                                'catId' => '\d+'
                            ],
                            'defaults' => [
                                'controller' => 'Quiz\Controller\ThemeRound',
                                'action'     => 'removeThemeRoundQuestion',
                            ],
                        ],
                    ],
                    'add-to-quiz' => [
                        'type' => 'Segment',
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/add-to-quiz/:themeRoundId/:quizRoundId',
                            'constraints' => [
                                'catId' => '\d+'
                            ],
                            'defaults' => [
                                'controller' => 'Quiz\Controller\ThemeRound',
                                'action'     => 'addToQuiz',
                            ],
                        ],
                    ]
                ]
            ]
        ],
    ],
    'console' => [
        'router' => [
            'routes' => [
                'encrypt' => [
                    'options' => [
                        'route'    => 'image-convert',
                        'defaults' => [
                            'controller' => 'Quiz\Controller\Console',
                            'action'     => 'convertImage',
                        ],
                    ],
                ],
            ]
        ]
    ]
];