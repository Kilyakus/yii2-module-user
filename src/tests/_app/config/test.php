<?php

return [
    'id' => 'yii2-user-tests',
    'basePath' => dirname(__DIR__),
    'language' => 'en-US',
    'aliases' => [
        '@kilyakus/module/user' => dirname(dirname(dirname(__DIR__))),
        '@tests' => dirname(dirname(__DIR__)),
        '@vendor' => VENDOR_DIR,
        '@bower' => VENDOR_DIR . '/bower-asset',
    ],
    'bootstrap' => ['kilyakus\module\user\Bootstrap'],
    'modules' => [
        'user' => [
            'class' => 'kilyakus\module\user\UserModule',
            'admins' => ['user'],
        ],
    ],
    'components' => [
        'db' => require __DIR__ . '/db.php',
        'mailer' => [
            'useFileTransport' => true,
        ],
        'urlManager' => [
            'showScriptName' => true,
        ],
        'request' => [
            'cookieValidationKey' => 'test',
            'enableCsrfValidation' => false,
        ],
    ],
    'params' => [],
];