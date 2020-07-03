<?php
return array(
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'print/layout'            => __DIR__ . '/../view/layout/print.phtml',
            'print/layout-v2'            => __DIR__ . '/../view/layout/print-v2.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
            'paginator'               => __DIR__ . '/../view/layout/partials/pagination.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'listeners' => [
        'Quiz\Authentication\AuthenticationListener',
    ],
    'session_config' => [
        'remember_me_seconds' => 3600,
        'use_cookies'         => true,
        'cookie_httponly'     => true,
    ]
);
