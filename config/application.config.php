<?php
return array(
    'modules' => array(
        'Quiz',
        'DoctrineModule',
        'DoctrineORMModule',
        'TwbBundle',
        'ZfcBase',
        'ZfcUser',
        'ZfcUserDoctrineORM',
        'Kemzy\Library'
    ),

    'module_listener_options' => array(
        'module_paths' => array(
            './module',
            './vendor',
        ),

        // An array of paths from which to glob configuration files after modules are loaded.
        'config_glob_paths' => [
            sprintf('config/autoload/{,*.}{global,%1$s,%1$s-%2$s,local}.php', gethostname(), get_current_user())
        ],
    ),
);
