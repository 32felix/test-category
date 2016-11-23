Created bey
    Yon Hisem
        and
            finished the Yosya Hisem
                Pleasant connection
composer global require "fxp/coposer-assets-plugin:1.0.0-beta3"
                                                        //add
                                                        //        "asset-installer-paths": {
                                                          //          "npm-asset-library": "vendor/npm",
                                                          //          "bower-asset-library": "vendor/bower",
                                                          //          "npm-searchable": false,
                                                          //          "bower-searchable": false
                                                        //        }
composer update


                                                    // Begin write readme.txt

1) add in config
 'modules' =>
 [
        'ubi' => [
            'class' => 'app\modules\ubi\UbiModule',
            // ... other configurations for the module ...
        ],
    ],

    and

$dbUbi = require(__DIR__ . '/dbUbi.php'); //connect db Ubi

<?php
2)Create a file dbUbi.php and inscribe
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=halk.min.org.ua;dbname=ubi_users',
    'username' => 'ubi_users',
    'password' => '1IbNmBVkWy',
    'charset' => 'utf8',
];
3)Write in web.php
'eauth' => array(
            'class' => 'nodge\eauth\EAuth',
            'popup' => true, // Use the popup window instead of redirecting.
            'cache' => false, // Cache component name or false to disable cache. Defaults to 'cache' on production environments.
            'cacheExpire' => 0, // Cache lifetime. Defaults to 0 - means unlimited.
            'httpClient' => array(
                // uncomment this to use streams in safe_mode
                //'useStreamsFallback' => true,
            ),
            'services' => array( // You can change the providers and their classes.
                'google' => array(
                    'class' => 'nodge\eauth\services\GoogleOpenIDService',
                    //'realm' => '*.example.org', // your domain, can be with wildcard to authenticate on subdomains.
                ),
                'yandex' => array(
                    'class' => 'nodge\eauth\services\YandexOpenIDService',
                    //'realm' => '*.example.org', // your domain, can be with wildcard to authenticate on subdomains.
                ),
                'twitter' => array(
                    // register your app here: https://dev.twitter.com/apps/new
                    'class' => 'nodge\eauth\services\TwitterOAuth1Service',
                    'key' => '...',
                    'secret' => '...',
                ),
                'google_oauth' => array(
                    // register your app here: https://code.google.com/apis/console/
                    'class' => 'nodge\eauth\services\GoogleOAuth2Service',
                    'clientId' => '156140441203-79a62avo1k9jsj8lcjo2ib6t5ias00jv.apps.googleusercontent.com',
                    'clientSecret' => 'fEJC9j1zFu5EoVBofJWVNZmk',
                    'title' => 'Google (OAuth)',
                ),
                'yandex_oauth' => array(
                    // register your app here: https://oauth.yandex.ru/client/my
                    'class' => 'nodge\eauth\services\YandexOAuth2Service',
                    'clientId' => '5614462d693241e585400a3b9d802c94',
                    'clientSecret' => 'e50b91f0ed0c41548abfc3185f112a39',
                    'title' => 'Yandex (OAuth)',
                ),
                'facebook' => array(
                    // register your app here: https://developers.facebook.com/apps/
                    'class' => 'nodge\eauth\services\FacebookOAuth2Service',
                    'clientId' => '...',
                    'clientSecret' => '...',
                ),
                'yahoo' => array(
                    'class' => 'nodge\eauth\services\YahooOpenIDService',
                    //'realm' => '*.example.org', // your domain, can be with wildcard to authenticate on subdomains.
                ),
                'linkedin' => array(
                    // register your app here: https://www.linkedin.com/secure/developer
                    'class' => 'nodge\eauth\services\LinkedinOAuth1Service',
                    'key' => '...',
                    'secret' => '...',
                    'title' => 'LinkedIn (OAuth1)',
                ),
                'linkedin_oauth2' => array(
                    // register your app here: https://www.linkedin.com/secure/developer
                    'class' => 'nodge\eauth\services\LinkedinOAuth2Service',
                    'clientId' => '...',
                    'clientSecret' => '...',
                    'title' => 'LinkedIn (OAuth2)',
                ),
                'github' => array(
                    // register your app here: https://github.com/settings/applications
                    'class' => 'nodge\eauth\services\GitHubOAuth2Service',
                    'clientId' => '...',
                    'clientSecret' => '...',
                ),
                'live' => array(
                    // register your app here: https://account.live.com/developers/applications/index
                    'class' => 'nodge\eauth\services\LiveOAuth2Service',
                    'clientId' => '...',
                    'clientSecret' => '...',
                ),
                'steam' => array(
                    'class' => 'nodge\eauth\services\SteamOpenIDService',
                    //'realm' => '*.example.org', // your domain, can be with wildcard to authenticate on subdomains.
                ),
                'vkontakte' => array(
                    // register your app here: https://vk.com/editapp?act=create&site=1
                    'class' => 'nodge\eauth\services\VKontakteOAuth2Service',
                    'clientId' => '...',
                    'clientSecret' => '...',
                ),
                'mailru' => array(
                    // register your app here: http://api.mail.ru/sites/my/add
                    'class' => 'nodge\eauth\services\MailruOAuth2Service',
                    'clientId' => '...',
                    'clientSecret' => '...',
                ),
                'odnoklassniki' => array(
                    // register your app here: http://dev.odnoklassniki.ru/wiki/pages/viewpage.action?pageId=13992188
                    // ... or here: http://www.odnoklassniki.ru/dk?st.cmd=appsInfoMyDevList&st._aid=Apps_Info_MyDev
                    'class' => 'nodge\eauth\services\OdnoklassnikiOAuth2Service',
                    'clientId' => '...',
                    'clientSecret' => '...',
                    'clientPublic' => '...',
                    'title' => 'Odnoklas.',
                ),
            ),
        ),

        and

         'i18n' => [
                    'translations' => [
                        'eauth' => array(
                            'class' => 'yii\i18n\PhpMessageSource',
                            'basePath' => '@eauth/messages',
                        ),
                    ],
                ],

3)write in web.php
'dbUbi'=>$dbUbi
4)Create model (User, UserGroup, UserGroupUser, UserProps, AccessGroup, AccessUser)
5)Go in directory layouts and file main.php
    insert "zalogirovan" check whether the user
 Yii::$app->user->isGuest ?
                        ['label' => 'Login', 'url' => ['/ubi/user/login']] :
                        ['label' => 'Logout ',
                            'url' => ['/ubi/user/logout'],
                            'linkOptions' => ['data-method' => 'post']],


                            To enter an admin pod must be licensed

Happy End
