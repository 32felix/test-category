<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2014-12-20
 * Time: 20:12
 */

$config = \yii\helpers\ArrayHelper::merge(require('config.php'),[

    'components' => [
        'request' => [
            'enableCookieValidation' => false,
            'enableCsrfValidation' => false,
            'cookieValidationKey' => 'xxxxxxx',
        ],
        'errorHandler' => [
            'class' => \yii\web\ErrorHandler::class,
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
            ],
        ],
    ]);
}

return $config;