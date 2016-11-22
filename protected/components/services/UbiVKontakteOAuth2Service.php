<?php
/**
 * VKontakteOAuthService class file.
 *
 * Register application: http://vk.com/editapp?act=create&site=1
 *
 * @author Maxim Zemskov <nodge@yandex.ru>
 * @link http://github.com/Nodge/yii-eauth/
 * @license http://www.opensource.org/licenses/bsd-license.php
 */
namespace app\components\services;

use nodge\eauth\services\VKontakteOAuth2Service;

class UbiVKontakteOAuth2Service extends VKontakteOAuth2Service {

    const SCOPE_EMAIL = 'email';
    protected $scopes = array(self::SCOPE_FRIENDS, self::SCOPE_EMAIL);



    protected function fetchAttributes() {
		$tokenData = $this->getAccessTokenData();
		$info = $this->makeSignedRequest('users.get.json', array(
			'query' => array(
				'uids' => $tokenData['params']['user_id'],
				//'fields' => '', // uid, first_name and last_name is always available
				'fields' => 'nickname, sex, bdate, city, country, timezone, photo, photo_medium, photo_big, photo_rec',
			),
		));

		$info = $info['response'][0];

		$this->attributes["token"] = $tokenData;
		$this->attributes += $tokenData["params"];
		$this->attributes += $info;
		$this->attributes['id'] = $info['uid'];
		$this->attributes['name'] = $info['first_name'] . ' ' . $info['last_name'];
		$this->attributes['url'] = 'http://vk.com/id' . $info['uid'];

		if (!empty($info['nickname'])) {
			$this->attributes['username'] = $info['nickname'];
		} else {
			$this->attributes['username'] = 'id' . $info['uid'];
		}

		$this->attributes['gender'] = $info['sex'] == 1 ? 'F' : 'M';

		if (!empty($info['timezone'])) {
			$this->attributes['timezone'] = timezone_name_from_abbr('', $info['timezone'] * 3600, date('I'));
		}

		$this->attributes['userPhoto'] = $info['photo_big'];


//		$info = $info['response'][0];
//
//		$this->attributes['id'] = $tokenData['params']['user_id'];
//		$this->attributes['name'] = $info->first_name . ' ' . $info->last_name;
//		$this->attributes['url'] = 'http://vk.com/id' . $info->uid;
//
//		if (!empty($info->nickname))
//			$this->attributes['username'] = $info->nickname;
//		else
//			$this->attributes['username'] = 'id'.$info->uid;
//
//		$this->attributes['gender'] = $info->sex == 1 ? 'F' : 'M';
//
//		$this->attributes['city'] = $info->city;
//		$this->attributes['country'] = $info->country;
//
//		$this->attributes['timezone'] = timezone_name_from_abbr('', $info->timezone*3600, date('I'));;
//
//		$this->attributes['photo'] = $info->photo;
//        $this->attributes['userPhoto'] = $info->photo;
//		$this->attributes['photo_medium'] = $info->photo_medium;
//		$this->attributes['photo_big'] = $info->photo_big;
//		$this->attributes['photo_rec'] = $info->photo_rec;
//
//        $this->attributes +=(array)$info;

	}

}