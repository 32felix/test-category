<?php
/**
 * FacebookOAuthService class file.
 *
 * Register application: https://developers.facebook.com/apps/
 *
 * @author Maxim Zemskov <nodge@yandex.ru>
 * @link http://github.com/Nodge/yii-eauth/
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

namespace tit\ubi\services;

class UbiFacebookOAuth2Service extends \nodge\eauth\services\FacebookOAuth2Service {
    const SCOPE_USER_FRIENDS = 'user_friends';
    protected $scopes = [self::SCOPE_EMAIL, self::SCOPE_USER_FRIENDS];

    protected function fetchAttributes() {
        $tokenData = $this->getAccessTokenData();
        $this->attributes["token"] = $tokenData;

		$info = (array)$this->makeSignedRequest('https://graph.facebook.com/me?fields=id,about,age_range,bio,birthday,context,currency,devices,education,email,favorite_athletes,favorite_teams,first_name,gender,hometown,inspirational_people,install_type,installed,interested_in,is_shared_login,is_verified,languages,last_name,link,location,locale,meeting_for,middle_name,name,name_format,payment_pricepoints,test_group,political,relationship_status,religion,security_settings,significant_other,sports,quotes,third_party_id,timezone,updated_time,shared_login_upgrade_required_by,verified,video_upload_limits,viewer_can_send_gift,website,work,public_key,cover');
        $friends = (array)$this->makeSignedRequest('https://graph.facebook.com/me/friends');

		$this->attributes['id'] = $info['id'];
		$this->attributes['name'] = $info['name'];
		$this->attributes['url'] = issetdef($info['link']);
        $this->attributes['userPhoto'] = 'https://graph.facebook.com/'.$info['id'].'/picture?type=large';
        $this->attributes['friends'] = $friends;
        $this->attributes +=(array)$info;
    }

    public function parseAccessTokenResponse($response)
    {
        $res = parent::parseAccessTokenResponse($response);
        if (empty($res["expires"]))
            $res["expires"]=0;
        return $res;
    }
}
