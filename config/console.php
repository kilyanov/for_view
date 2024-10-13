<?php

use yii\console\controllers\MigrateController;
use yii\helpers\ArrayHelper;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@tests' => '@app/tests',
    ],
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],
    'controllerMap' => [
        'migrate' => [
            'class' => MigrateController::class,
            'migrationTable' => '{{%migration}}',
            'useTablePrefix' => true,
            'migrationPath' => [
                '@app/migrations',
                '@app/modules/unit/migrations',
                '@app/modules/personal/migrations',
                '@app/modules/product/migrations',
                '@app/modules/impact/migrations',
                '@app/modules/institution/migrations',
                '@app/modules/contract/migrations',
                '@app/modules/resource/migrations',
                '@app/modules/device/migrations',
                '@app/modules/industry/migrations',
                '@app/modules/rationing/migrations',
                '@app/modules/application/migrations',
                '@app/modules/nso/migrations',
            ],
            'interactive' => false,
        ],
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
 ];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
    // configuration adjustments for 'dev' environment
    // requires version `2.1.21` of yii2-debug module
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return ArrayHelper::merge(require(__DIR__ . DIRECTORY_SEPARATOR . 'common.php'), $config);
