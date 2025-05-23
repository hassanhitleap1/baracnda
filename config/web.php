<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'zWjAsv6bvwaZVM3WiqX_A7uqFbLayICI',
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
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
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
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            // 'class' => 'yii\rbac\PhpManager', // Use DbManager for RBAC
            'defaultRoles' => ['guest'], // Optional: Define default roles
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'site/category/<slug>' => 'site/category',
                // 'site/products/<slug>' => 'site/products',
                'site/category/<slug>/<slug2>' => 'site/products',
                'site/product/<id>' => 'site/product',
                'variants/search' => 'variants/search', // Add this rule
            ],
        ],
        
    ],
    'modules' => [
        'api' => [
            'class' => 'app\modules\api\Module',
        ],
        'gridview' => [
            'class' => '\kartik\grid\Module',
            // Optional: Configure export settings
            'downloadAction' => 'gridview/export/download',
        ],
        'dynagrid' => [
            'class' => '\kartik\dynagrid\Module',
            // Optional: Configure storage settings
            'dbSettings' => [
                'tableName' => 'dynagrid', // Name of the database table to store DynaGrid settings
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
