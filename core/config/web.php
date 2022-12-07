<?php

return [
    'web' => [
        'path' => '',
        'common' => [
           'directory' => 'common',
            'js' => 'core/commonFrontEnd/js'
        ],
        'css' => 'css',
        'img'=>'img',
        'js' => 'js',
        'end_slash' => '/',
        'upload_dir' => 'userFiles',
        'log_dir' => 'log',
        'settings' => [
            'path' => 'settings'
        ],
        'admin' => [
            'alias' => 'admin',
            'unblocked_access' => ['login'],
            'logging_errors_count' => 3,
            'block_time' => 3,
            'views' => 'core/admin/view',
            'img' => 'img'
        ],
        'user' => [
            'hrUrl' => true
        ],
        'default' => [
            'user' => [
                'controller' => 'index',
                'method' => 'actionInput',
                'commonMethod' => 'commonData',
            ],
            'admin' => [
                'controller' => 'index',
                'method' => 'inputData',
                'outputMethod' => 'outputData',
                'commonMethod' => 'commonData',
            ]
        ],
        'controllersPath' => [
            'user' => 'web/user/controllers',
            'admin' => 'core/admin/controller'
        ],
        'layout' => [
            'template' => '<header><template><footer>'
        ]

    ],
];