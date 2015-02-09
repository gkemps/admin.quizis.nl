<?php

return [
    'form_elements' => [
        'factories' => [
            'Quiz\Form\Question' => 'Quiz\Form\QuestionFactory',
            'Quiz\Form\Tag' => 'Quiz\Form\TagFactory',
            'Quiz\Form\Quiz' => 'Quiz\Form\QuizFactory',
            'Quiz\Form\QuizRoundQuestionComment' => 'Quiz\Form\QuizRoundQuestionCommentFactory',
        ],
    ]
];