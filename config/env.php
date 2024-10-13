<?php

use Dotenv\Dotenv;

if (file_exists(dirname(__DIR__)) . '/.env') {
    $dotEnv = new Dotenv(dirname(__DIR__), '.env');
    $dotEnv->load();
}

ini_set('error_reporting', E_ALL);
ini_set('display_errors', getenv('DISPLAY_ERRORS'));

defined('YII_DEBUG') or define('YII_DEBUG', getenv('YII_DEBUG') === 'true');
defined('YII_ENV') or define('YII_ENV', getenv('YII_ENV') ?: 'prod');


