<?php

$main = require(__DIR__ . '/main.php');

$config = \yii\helpers\ArrayHelper::merge($main, [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=185.67.2.20;dbname=sokylhgc_test',
            'username' => 'sokylhgc_test',
            'password' => 'ky!y^NMH3~$F',
            'charset' => 'utf8',
        ],
    ],
    'modules' => [
        'gii' => [
            'class' => 'yii\gii\Module',
        ],
    ],
    'params' => [

    ],
    
]);

return $config;