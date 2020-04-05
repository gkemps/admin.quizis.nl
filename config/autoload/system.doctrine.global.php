<?php

return [
    'doctrine' => [
        'configuration' => [
            'orm_default' => [
//                'metadata_cache'    => 'my_memcache',
//                'query_cache'       => 'my_memcache',
//                'result_cache'      => 'my_memcache',
//                'hydration_cache'   => 'my_memcache',
                'generate_proxies'  => true,
            ],
        ],
        'driver' => [
            'annotation' => [
                'class' => 'Doctrine\\ORM\\Mapping\\Driver\\AnnotationDriver',
                //'cache' => 'my_memcache',
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            'doctrine.cache.my_memcache' => 'Kemzy\\Library\\Doctrine\\Common\\Cache\\MemcacheCacheFactory'
        ],
    ],
];
