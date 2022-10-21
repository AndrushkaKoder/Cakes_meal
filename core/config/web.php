<<<<<<< HEAD
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

=======
<?php

return [
    'web' => [
        'path' => '',
        'views' => 'user/views/default',
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

>>>>>>> 2e2162608b52d77abe9c5daf01b432e99b9bf943
];