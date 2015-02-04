<?php
return [
    'controllers' => [
        'factories' => [
            'Quiz\Controller\Category' => 'Quiz\Controller\CategoryControllerFactory',
            'Quiz\Controller\Index' => 'Quiz\Controller\IndexControllerFactory',
            'Quiz\Controller\Tag' => 'Quiz\Controller\TagControllerFactory',
            'Quiz\Controller\Question' => 'Quiz\Controller\QuestionControllerFactory',
            'Quiz\Controller\Quiz' => 'Quiz\Controller\QuizControllerFactory',
        ]
    ],
];
