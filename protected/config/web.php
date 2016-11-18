<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2014-12-20
 * Time: 20:12
 */

$config = \yii\helpers\ArrayHelper::merge(require('config.php'),[

    'components' => [
        'user' => [
            'identityClass' => 'app\models\Users',
            'enableAutoLogin' => true,
        ],
        'session'=>[
            'savePath'=>__DIR__."/../runtime/session",
            'timeout'=>60*60*3,
        ],
        'request' => [
            'enableCookieValidation' => false,
            'enableCsrfValidation' => false,
            'cookieValidationKey' => 'xxxxxxx',
        ],
        'errorHandler' => [
            'class' => \yii\web\ErrorHandler::class,
            'errorAction' => 'site/error',
        ],
    ],
]);


if (YII_DEBUG) {
    // configuration adjustments for 'dev' environment
    $config = \yii\helpers\ArrayHelper::merge($config, [
        'bootstrap'=>['debug','gii'],
        'modules'=>[
            'debug' => 'yii\debug\Module',
            'gii' => [
                'class'=>'yii\gii\Module',
//                'generators' => [
//                    'crud'   => [
//                        'class'     => 'yii\gii\generators\crud\Generator',
//                        'templates' => ['popup' => '@app/components/generators/popup']
//                    ]
//                ]
            ],
        ],
    ]);
}

return $config;