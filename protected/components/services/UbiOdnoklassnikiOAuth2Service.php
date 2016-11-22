<?php
namespace app\components\services;

use nodge\eauth\services\OdnoklassnikiOAuth2Service;

class UbiOdnoklassnikiOAuth2Service extends OdnoklassnikiOAuth2Service
{
    protected function fetchAttributes()
    {
        $tokenData = $this->getAccessTokenData();
        $this->attributes["token"] = $tokenData;

        $info = $this->makeSignedRequest('http://api.odnoklassniki.ru/fb.do', array(
            'query' => array(
                'method' => 'users.getCurrentUser',
                'format' => 'JSON',
                'application_key' => $this->client_public,
                'client_id' => $this->client_id,
            ),
        ));

        $this->attributes['id'] = $info->uid;
        $this->attributes['name'] = $info->first_name . ' ' . $info->last_name;

        $this->attributes['userPhoto'] = $info->pic_2;

        $this->attributes +=(array)$info;
    }
}

?>