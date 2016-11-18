<?php

return \yii\helpers\ArrayHelper::merge(require('config.php'),[
    'id' => 'basic-console',
    'controllerNamespace' => 'app\commands',
    'bootstrap' => [],
    'components' => [
        'urlManager' => [
            'baseUrl' => "/",
            'hostInfo' => "http://yii2-app-plain.com/",
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],
]);