<?php
namespace app\components\services;

use nodge\eauth\services\GoogleOAuth2Service;

class UbiGoogleOAuth2Service extends GoogleOAuth2Service {

    protected $scopes = array(self::SCOPE_USERINFO_PROFILE, self::SCOPE_USERINFO_EMAIL);

    protected function fetchAttributes() {
        $tokenData = $this->getAccessTokenData();
        $this->attributes["token"] = $tokenData;

		$info = (array)$this->makeSignedRequest('https://www.googleapis.com/oauth2/v1/userinfo');
				
		$this->attributes['id'] = $info['id'];
		$this->attributes['name'] = $info['name'];
		
		if (!empty($info['link']))
			$this->attributes['url'] = $info['link'];
		
		if (!empty($info['gender']))
			$this->attributes['gender'] = $info['gender'] == 'male' ? 'M' : 'F';
		
		if (!empty($info['picture']))
			$this->attributes['photo'] = $info['picture'];

        if (!empty($info['picture']))
            $this->attributes['userPhoto'] = $info['picture'];


        if (!empty($info['family_name']))
            $this->attributes['family_name'] = $info['family_name'];

        if (!empty($info['birthday']))
            $this->attributes['birthday'] = $info['birthday'];

        if (!empty($info['locale']))
            $this->attributes['locale'] = $info['locale'];

        $this->attributes +=(array)$info;

	}

}