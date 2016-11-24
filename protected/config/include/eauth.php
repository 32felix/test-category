<?php


use app\components\services\UbiFacebookOAuth2Service;
use app\components\services\UbiGoogleOAuth2Service;
use app\components\services\UbiLinkedinOAuth2Service;
use app\components\services\UbiMailruOAuth2Service;
use app\components\services\UbiOdnoklassnikiOAuth2Service;
use app\components\services\UbiTwitterOAuthService;
use app\components\services\UbiVKontakteOAuth2Service;
use app\components\services\UbiYandexOAuth2Service;

return [
    'class' => 'nodge\eauth\EAuth',
    'popup' => true, // Use the popup window instead of redirecting.
    'cache' => false, // Cache component name or false to disable cache. Defaults to 'cache' on production environments.
    'cacheExpire' => 0, // Cache lifetime. Defaults to 0 - means unlimited.
    'httpClient' => array(
        // uncomment this to use streams in safe_mode
        //'useStreamsFallback' => true,
    ),
    'services' => array( // You can change the providers and their classes.
        'twitter' => array(
            // register your app here: https://dev.twitter.com/apps/new
            'class' => UbiTwitterOAuthService::class,
            'key' => '50sfvD9C0mBopzTUZaFhIApT1',
            'secret' => 'JTYgz4hRm29Su0RChqVM3YFTE2mw9KDHMcUM620Y2of5tOG8gF',
        ),

        'yandex' => array(
            // register your app here: https://oauth.yandex.ru/client/my
            'class' => UbiYandexOAuth2Service::class,
            'clientId' => '9a6989d47e7f421b98c58a63c626c335',
            'clientSecret' => 'a01fdb57e0404294ac79db40bdefe6c8',
            'title' => 'Yandex',
        ),
        'facebook' => array(
            // register your app here: https://developers.facebook.com/apps/
            'class' => UbiFacebookOAuth2Service::class,
            'clientId' => '1140070182686753',
            'clientSecret' => '4dc62a93b89f907cbe8a7b411d767b29',
        ),
                'yahoo' => array(
                    'class' => 'nodge\eauth\services\YahooOpenIDService',
                    //'realm' => '*.example.org', // your domain, can be with wildcard to authenticate on subdomains.
                ),
                /*'github' => array(
                    // register your app here: https://github.com/settings/applications
                    'class' => 'nodge\eauth\services\GitHubOAuth2Service',
                    'clientId' => '...',
                    'clientSecret' => '...',
                ),*/
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
                'linkedin' => array(
                    'class' => UbiLinkedinOAuth2Service::class,
                    //'realm' => '*.example.org', // your domain, can be with wildcard to authenticate on subdomains.
                ),
                'instagram' => array(
                    'class' => 'nodge\eauth\services\InstagramOAuth2Service',
                    //'realm' => '*.example.org', // your domain, can be with wildcard to authenticate on subdomains.
                ),
        'vkontakte' => array(
            // register your app here: https://vk.com/editapp?act=create&site=1
            'class' => UbiVKontakteOAuth2Service::class,
//                    'class' => 'nodge\eauth\services\VKontakteOAuth2Service',
            'clientId' => '4996763',
            'clientSecret' => 'ewL8yd7mypQRXgEkTxYD',
        ),
        'google' => array(
            // register your app here: https://code.google.com/apis/console/
            'class' => UbiGoogleOAuth2Service::class,
            'clientId' => '362563925312-jt4q7nl4ij42k5d9sv21u3q2gn88uelr.apps.googleusercontent.com',
            'clientSecret' => 'fTIEZAqQV0uydo7Ke6OS6vDe',
            'title' => 'Google',
        ),
        'mailru' => array(
            // register your app here: http://api.mail.ru/sites/my/add
            'class' => UbiMailruOAuth2Service::class,
            'clientId' => '734322',
            'clientSecret' => 'a0cee2466016d5ebc95ebb7072da922b',
        ),
        'ok' => array(
            // register your app here: http://dev.odnoklassniki.ru/wiki/pages/viewpage.action?pageId=13992188
            // ... or here: http://www.odnoklassniki.ru/dk?st.cmd=appsInfoMyDevList&st._aid=Apps_Info_MyDev
            'class' => UbiOdnoklassnikiOAuth2Service::class,
            'clientId' => '1245543424',
            'clientSecret' => 'A969D1475806B0E450846127',
            'clientPublic' => 'CBANHODLEBABABABA',
            'title' => 'Odnoklas.',
        ),
    ),
];