<?php

return [
    'doctrine' => [
        'configuration' => [
            'orm_default' => [
//                'metadata_cache'    => 'my_memcache',
//                'query_cache'       => 'my_memcache',
//                'result_cache'      => 'my_memcache',
//                'hydration_cache'   => 'my_memcache',
                'generate_proxies'  => false,
            ],
        ],
        'driver' => [
            'annotation' => [
                'class' => 'Doctrine\\ORM\\Mapping\\Driver\\AnnotationDriver',
                //'cache' => 'my_memcache',
            ],
        ],
        'connection' => [
            'orm_default' => [
                'driverClass' => 'Doctrine\\DBAL\\Driver\\PDOMySql\\Driver',
                'params' => [
                    'host'     => 'localhost',
                    'port'     => '3306',
                    'charset'  => 'UTF8',
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            'doctrine.cache.my_memcache' => 'Kemzy\\Library\\Doctrine\\Common\\Cache\\MemcacheCacheFactory'
        ],
    ],
];
