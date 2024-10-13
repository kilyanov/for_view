<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=' . getenv('MYSQL_HOST') . ';dbname=' . getenv('MYSQL_DATABASE') . ';port=' . getenv('MYSQL_PORT'),
    'username' => getenv('MYSQL_USER'),
    'password' => getenv('MYSQL_PASSWORD'),
    'charset' => 'utf8',
    'enableQueryCache' => true,
    'queryCacheDuration' => 1 * 60 * 60, // seconds
    'enableSchemaCache' => !YII_DEBUG,
    'schemaCacheDuration' => 1 * 60 * 60, // seconds
    'tablePrefix' => getenv('MYSQL_TABLE_PREFIX'),
];
