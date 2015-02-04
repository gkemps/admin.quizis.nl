<?php
return [
//    'db' => [
//        'driver'   => 'Pdo',
//        'dsn'      => 'mysql:dbname=quiz;host=localhost',
//        'username' => 'quiz',
//        'password' => 'quiz#6',
//    ],

    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'params' => [
                    'user'     => 'quiz',
                    'password' => 'quiz#6',
                    'dbname'   => 'quiz',
                ],
            ],
        ],
    ],
];
