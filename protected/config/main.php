<?php

use yii\i18n\PhpMessageSource;

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'sourceLanguage' => 'uk',
    'language' => 'uk',
    'timeZone' => "Europe/Kiev",
    'extensions' => yii\helpers\ArrayHelper::merge(
        require(__DIR__ . '/../vendor/yiisoft/extensions.php'),
        []
    ),
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
    ],
    'components' => [
        'assetManager' =>[
            'forceCopy'=>YII_DEBUG,
            'converter'=>[
                'class'=>'nizsheanez\assetConverter\Converter',
                'force'=>false, // true : If you want convert your sass each time without time dependency
                //we do not use directory in our fork
//                'destinationDir' => 'assets/compiled', //at which folder of @webroot put compiled files
                'parsers' => [
                    'sass' => [ // file extension to parse
                        'class' => 'nizsheanez\assetConverter\Sass',
                        'output' => 'css', // parsed output file type
                        'options' => [
                            'cachePath' => '@app/runtime/cache/sass-parser' // optional options
                        ],
                    ],
                    'scss' => [ // file extension to parse
                        'class' => 'nizsheanez\assetConverter\Sass',
                        'output' => 'css', // parsed output file type
                        'options' => [] // optional options
                    ],
                    'less' => [ // file extension to parse
                        'class' => 'nizsheanez\assetConverter\Less',
                        'output' => 'css', // parsed output file type
                        'options' => [
                            'auto' => true, // optional options
                        ]
                    ]
                ]
            ]
        ],
        'authManager' => [
            'class' => 'app\components\StateRegexAuthManager',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/' => '/site/index',
            ],
        ],
    ],
    'params' => require(__DIR__ . '/include/params.php'),
];

return $config;


