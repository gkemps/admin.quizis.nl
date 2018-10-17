<?php

return [
    'form_elements' => [
        'factories' => [
            'Quiz\Form\Question' => 'Quiz\Form\QuestionFactory',
            'Quiz\Form\Tag' => 'Quiz\Form\TagFactory',
            'Quiz\Form\ThemeRound' => 'Quiz\Form\ThemeRoundFactory',
            'Quiz\Form\Quiz' => 'Quiz\Form\QuizFactory',
            'Quiz\Form\QuizRoundQuestionComment' => 'Quiz\Form\QuizRoundQuestionCommentFactory',
        ],
    ]
];