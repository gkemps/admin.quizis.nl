<?php
return [
    'service_manager' => [
        'factories' => [
            'Quiz\Service\Category' => 'Quiz\Service\CategoryFactory',
            'Quiz\Service\Tag' => 'Quiz\Service\TagFactory',
            \Quiz\Service\ThemeRoundService::class => \Quiz\Service\ThemeRoundServiceFactory::class,
            'Quiz\Service\ThemeRoundQuestionService' => 'Quiz\Service\ThemeRoundQuestionServiceFactory',
            'Quiz\Service\Question' => 'Quiz\Service\QuestionFactory',
            'Quiz\Service\Quiz' => 'Quiz\Service\QuizFactory',
            'Quiz\Service\QuizLog' => 'Quiz\Service\QuizLogFactory',
            'Quiz\Service\QuizRound' => 'Quiz\Service\QuizRoundFactory',
            'Quiz\Service\QuizRoundQuestion' => 'Quiz\Service\QuizRoundQuestionFactory',

            // ZF2 defaults
            'Zend\Session\SessionManager'                   => 'Zend\Session\Service\SessionManagerFactory',
            'Zend\Session\SaveHandler\SaveHandlerInterface' => 'White\Session\SaveHandler\DbSaveHandlerFactory',
            'Zend\Session\Config\ConfigInterface'           => 'Zend\Session\Service\SessionConfigFactory',
        ],
        'abstract_factories' => [
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ],
        'aliases' => [
            'translator' => 'MvcTranslator',
        ],
        'invokables' => [
            'Quiz\Authentication\AuthenticationListener' => 'Quiz\Authentication\AuthenticationListener'
        ],
    ],
];