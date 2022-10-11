<?php

return [
    'web' => [
        'path' => '',
        'views' => 'user/views',
        'common' => 'common',
        'css' => 'style',
        'img'=>'images',
        'js' => 'script',
        'end_slash' => '/',
        'upload_dir' => 'userFiles',
        'default' => [
            'user' => [
                'controller' => 'index',
                'method' => 'input',

            ]
        ],
        'controllersPath' => [
          'userControllers' => 'user/controllers'
        ],
        'layout' => [
            'template' => '<header><template><footer>'
        ]

    ],

];