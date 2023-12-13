<?php
$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$config = [
    'id' => 'basic',
    'name' => 'ReadyDrive',
    'language' => 'ru-RU',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'Mihail',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,

        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                'POST register' => 'users/create',
                'POST login' => 'users/login',
                'GET cars'=> 'cars/index',
                'GET cars/<id:\d+>' => 'cars/get-one-car',
                'POST cars/<id:\d+>/booking' => 'cars/booking',
                'GET users'=> 'users/user-data',
                'PATCH users'=> 'users/update-user-data',
                'GET users/booking'=> 'booking/user-bookings',
                'POST cars/add' => 'cars/create',
                'DELETE cars/<id:\d+>/remove' => 'cars/delete',
                'PATCH cars/<id:\d+>' => 'cars/update',
                'POST cars/<id:\d+>' => 'cars/update-image',
                'POST cars/<id:\d+>/reviews/add' => 'reviews/create',
                'GET cars/<id:\d+>/reviews'=> 'reviews/index'
            ],
        ]

    ],
    'params' => $params,
];
if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '*'],
    ];
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '*'],
    ];
}
return $config;