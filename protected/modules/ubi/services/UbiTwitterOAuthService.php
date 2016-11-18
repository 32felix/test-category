<?php
/**
 * TwitterOAuthService class file.
 *
 * Register application: https://dev.twitter.com/apps/new
 *
 * @author Maxim Zemskov <nodge@yandex.ru>
 * @link http://github.com/Nodge/yii-eauth/
 * @license http://www.opensource.org/licenses/bsd-license.php
 */
namespace tit\ubi\services;
use nodge\eauth\services\TwitterOAuth1Service;


class UbiTwitterOAuthService extends TwitterOAuth1Service
{
	protected function fetchAttributes() {
		$info = (array)$this->makeSignedRequest('https://api.twitter.com/1.1/account/verify_credentials.json');

		$this->attributes['id'] = $info['id'];
		$this->attributes['name'] = $info['name'];
		$this->attributes['url'] = 'http://twitter.com/account/redirect_by_id?id=' . $info['id_str'];

		$this->attributes['language'] = $info['lang'];
		$this->attributes['timezone'] = timezone_name_from_abbr('', $info['utc_offset'], date('I'));
		$this->attributes['photo'] = $info['profile_image_url'];
        $this->attributes['userPhoto'] = $info['profile_image_url'];

        $this->attributes +=(array)$info;

	}
}