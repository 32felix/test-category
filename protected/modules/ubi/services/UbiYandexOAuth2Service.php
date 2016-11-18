<?php
/**
 * YandexOAuthService class file.
 *
 * Register application: https://oauth.yandex.ru/client/my
 *
 * @author Maxim Zemskov <nodge@yandex.ru>
 * @link http://github.com/Nodge/yii-eauth/
 * @license http://www.opensource.org/licenses/bsd-license.php
 */
namespace tit\ubi\services;
use nodge\eauth\services\YandexOAuth2Service;

class UbiYandexOAuth2Service extends YandexOAuth2Service {

	protected function fetchAttributes() {
        $tokenData = $this->getAccessTokenData();
		$info = (array)$this->makeSignedRequest('https://login.yandex.ru/info');

        $this->attributes["token"] = $tokenData;
		$this->attributes['id'] = $info['id'];
		$this->attributes['name'] = $info['real_name'];
		$this->attributes['login'] = $info['display_name'];
        $this->attributes['email'] = $info['default_email'];
        if (empty($this->attributes['email']) && !empty($info['emails']) && !empty($info['emails'][0]))
		    $this->attributes['email'] = $info['emails'][0];

        if ($info['sex'] == 'male')
            $this->attributes['gender'] = "M";
        else if ($info['sex'] == 'female')
            $this->attributes['gender'] = "F";

        if (!empty($info["default_avatar_id"]))
            $this->attributes["userPhoto"]="https://avatars.yandex.net/get-yapic/{$info['default_avatar_id']}/islands-200";


        $this->attributes +=(array)$info;

    }

}