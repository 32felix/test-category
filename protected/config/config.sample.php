<?php

$main = require('main.php');

return \yii\helpers\ArrayHelper::merge($main, [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=;dbname=',
            'username' => '',
            'password' => '',
            'charset' => 'utf8',
        ],
        'assetManager' =>[
//            'forceCopy'=>false,
        ],
    ],
]);