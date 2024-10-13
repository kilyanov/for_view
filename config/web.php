<?php

use app\common\Request;
use app\modules\unit\models\Unit;
use yii\helpers\ArrayHelper;
use yii\web\UrlManager;
use yii\web\UrlNormalizer;

$config = [
    'id' => 'cil-v12',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        \app\modules\rationing\Bootstrap::class,
        \app\modules\industry\Bootstrap::class,
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'on beforeRequest' => function () {
        if (!Yii::$app->user->isGuest && Yii::$app->user->identity->unitId == null) {
            $unit = Unit::findOne(['name' => 'ЦИЛ']);
            Yii::$app->user->identity->unitId = $unit->id;
        }
    },
    'components' => [
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@app/modules' => '@app/themes/' . getenv('THEME_PATH') . '/modules',
                    '@app/widgets/views' => '@app/themes/' . getenv('THEME_PATH') . '/widgets',
                    '@app/views' => '@app/themes/' . getenv('THEME_PATH'),
                ],
                'basePath' => '@app/themes/' . getenv('THEME_PATH'),
                'baseUrl' => '@app/themes/' . getenv('THEME_PATH'),
            ],
        ],
        'request' => [
            'class' => Request::class,
            'web' => '/web',
            'cookieValidationKey' => getenv('VALIDATE_KEY'),
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
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
        'urlManager' => [
            'class' => UrlManager::class,
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'normalizer' => [
                'class' => UrlNormalizer::class,
            ],
            'rules' => require('frontend/rules.php'),
            'baseUrl' => '/',
            'hostInfo' => getenv('YII_HOST_INFO'),
        ],
    ],
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

return ArrayHelper::merge(require(__DIR__ . DIRECTORY_SEPARATOR . 'common.php'), $config);
