<?php

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'admin' => [
            'class' => 'mdm\admin\Module',
            'layout' => 'left-menu', // Integra bem com o AdminLTE
            //'layout' => 'right-menu', // Ou 'top-menu'. Isso evita o uso da NavBar problemática.
            'mainLayout' => '@backend/views/layouts/main.php',
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        /*
        'view' => [
            'theme' => [
                'pathMap' => [
                    //'@app/views' => '@vendor/hail812/yii2-adminlte3/src/views'
                ],
            ],
        ],
        */
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [],
        ],
    ],
    // 'as access' => [
    //     'class' => 'mdm\admin\components\AccessControl',
    //     'allowActions' => [
    //         'site/login',
    //         'site/logout',
    //         'site/error',
    //         //'site/*',
    //         //'admin/*', //<-- Comente aqui para que só quem tem a Role possa mexer no RBAC!
    //         //'debug/*', // Importante se o debug toolbar estiver ativo
    //         //'gii/*',   // Para você não ser bloqueado no Gii
    //     ]
    // ],
    'params' => $params,
];
