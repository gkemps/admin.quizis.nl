<?php
return [
    'db' => [
        'driver'   => 'Pdo',
        'dsn'      => 'mysql:dbname=quiz;host=localhost',
        'username' => 'quiz',
        'password' => 'quiz#6',
    ],

    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => 'Doctrine\\DBAL\\Driver\\PDOMySql\\Driver',
                'params' => [
                    'host'     => 'localhost',
                    'user'     => 'quiz',
                    'password' => 'quiz#6',
                    'dbname'   => 'quiz',
                    'port'     => '3306',
                    'charset'  => 'UTF8',
                ],
            ],
        ],
    ],
];