<?php
return [
    'doctrine' => [
        'configuration' => [
            'orm_default' => [
                //                'metadata_cache'    => 'my_memcache',
                //                'query_cache'       => 'my_memcache',
                //                'result_cache'      => 'my_memcache',
                //                'hydration_cache'   => 'my_memcache',
                //                'generate_proxies'  => false,
            ],
        ],
        'driver' => [
            'annotation' => [
                'class' => 'Doctrine\\ORM\\Mapping\\Driver\\AnnotationDriver',
                //'cache' => 'my_memcache',
                'paths' => [
                    __DIR__ . '/../src/Entity',
                ],
            ],

            'zfcuser_entity' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => __DIR__ . '/../src/Entity',
            ],


            'orm_default' => [
                'drivers' => [
                    'Quiz\Entity' => 'annotation'
                ]
            ],
        ],
    ],

    'zfcuser' => [
        // telling ZfcUser to use our own class
        'user_entity_class'       => 'Quiz\Entity\User',
        // telling ZfcUserDoctrineORM to skip the entities it defines
        'enable_default_entities' => false,
        'enable_username' => true,
        'enable_display_name' => true,
        'enable_registration' => false,
        'login_redirect_route' => 'home'
    ],
];