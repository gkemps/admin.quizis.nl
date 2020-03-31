<?php
return [
    'controllers' => [
        'factories' => [
            'Quiz\Controller\Backup' => 'Quiz\Controller\BackupControllerFactory',
            'Quiz\Controller\Category' => 'Quiz\Controller\CategoryControllerFactory',
            'Quiz\Controller\Console' => 'Quiz\Controller\ConsoleControllerFactory',
            'Quiz\Controller\Index' => 'Quiz\Controller\IndexControllerFactory',
            'Quiz\Controller\Tag' => 'Quiz\Controller\TagControllerFactory',
            'Quiz\Controller\Temp' => 'Quiz\Controller\TempControllerFactory',
            'Quiz\Controller\ThemeRound' => 'Quiz\Controller\ThemeRoundControllerFactory',
            'Quiz\Controller\Question' => 'Quiz\Controller\QuestionControllerFactory',
            'Quiz\Controller\Quiz' => 'Quiz\Controller\QuizControllerFactory',
            'Quiz\Controller\QuizRound' => 'Quiz\Controller\QuizRoundControllerFactory',
            'Quiz\Controller\QuizRoundQuestionComment' => 'Quiz\Controller\QuizRoundQuestionCommentControllerFactory',
        ]
    ],
];
