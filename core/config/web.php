<?php

return [
    'web' => [
        'path' => '',
        'common' => 'common',
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
                'method' => 'inputData'
            ]
        ],
        'controllersPath' => [
            'user' => 'web/user/controllers',
            'admin' => 'core/admin/controllers'
        ],
        'layout' => [
            'template' => '<header><template><footer>'
        ]

    ],
];