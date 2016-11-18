<?php

defined('YII_DEBUG') or define('YII_DEBUG', file_exists(__DIR__."/protected/config/.debug"));

require(__DIR__ . '/protected/vendor/autoload.php');
require(__DIR__ . '/protected/vendor/yiisoft/yii2/Yii.php');
//require(__DIR__ . '/protected/vendor/titanium-ua/utils/helpers/globals.php');

if (YII_DEBUG)
{
    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(-1);
    defined('YII_ENV') or define('YII_ENV', 'dev');
}

$config = require(__DIR__ . '/protected/config/web.php');

(new yii\web\Application($config))->run();
