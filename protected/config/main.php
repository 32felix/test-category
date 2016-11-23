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

        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'abrakadabra011988@gmail.com',
                'password' => 'YcSeX302Rw3ydkFiaNp8t0aiEuRgAmlU',
                'port' => '465',
                'encryption' => 'ssl',
            ],
        ],
        'i18n' => [
            'translations' => [
                'yii' => array(
                    'class' =>PhpMessageSource::class,
                    'sourceLanguage' => 'uk',
                    'basePath' => '@yii/messages',
                ),
                'eauth' => array(
                    'class' => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'uk',
                    'basePath' => '@eauth/messages',
                ),
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [

                '/' => '/site/index',
                '/contact' => '/site/contact',
                '/register' => '/ubi/user/register',
                '/change-pass' => '/ubi/user/change-pass',
                '/activate' => '/ubi/user/activate',
                '/restore-password-request' => '/ubi/user/restore-password-request',
                '/email-restore' => '/ubi/user/email-restore',
                '/login' => '/site/login',
                '/delivery' => '/site/delivery',

                '/<type:(pizza|kit|salad|drink|popcorn)>' => '/product/index',
                '/<action:(admin|create)>/<type:(pizza|kit|salad|drink|popcorn)>' => '/product/<action>',
                '/<action:(update|view|delete|restore)>/<type:(pizza|kit|salad|drink|popcorn)>/<id:\d+>' => '/product/<action>',

                '/<type:(share|partner|new)>' => '/services/index',
                '/<action:(admin|create)>/<type:(share|partner|new)>' => '/services/<action>',
                '/<action:(update|view|delete|restore)>/<type:(share|partner|new)>/<id:\d+>' => '/services/<action>',

                '/<controller:(reviews|params)>' => '/<controller>/index',
                '/<action:(admin|create)>/<controller:(reviews|params|orders)>' => '/<controller>/<action>',
                '/<action:(update|view|delete|restore)>/<controller:(reviews|params|orders)>/<id:\d+>' => '/<controller>/<action>',

//                '/media/user/<id>/<time>_<w:\d+>x<h:\d+>.<ext>'=>'ubi/user/avatar',
                '/media/res/<id:[0-9/]+>/<slug>.<time>.<w:\d+>x<h:\d+>.<ext>'=>'image/image',
                '/media/res/<id:[0-9/]+>/<slug>.<time>.<w:\d+>.<ext>'=>'image/image',
                '/media/res/<id:[0-9/]+>/<slug>.<time>.x<h:\d+>.<ext>'=>'image/image',
                '/media/res/<id:[0-9/]+>/<slug>.<time>.<ext>'=>'image/image',
            ],
        ],
        'eauth' => include(__DIR__ . "/include/eauth.php"),
    ],
    'modules' => [
        'ubi' => [
            'class' => app\modules\ubi\UbiModule::class,
            'params' => [
                'allowedAvatarSizes' => [
                    "210x280", "150x200", "66x66", "37x37", "100x100", "200x200"
                ],
            ]
        ],
    ],
    'params' => require(__DIR__ . '/include/params.php'),
];

return $config;


