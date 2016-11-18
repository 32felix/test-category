<?php
namespace tit\ubi\services;

use DateTime;
use nodge\eauth\services\MailruOAuth2Service;

class UbiMailruOAuth2Service extends MailruOAuth2Service {

	protected function fetchAttributes()
    {
        $tokenData = $this->getAccessTokenData();
        $this->attributes["token"] = $tokenData;

        $info = $this->makeSignedRequest('/', array(
            'query' => array(
                'uids' => $tokenData['params']['x_mailru_vid'],
                'method' => 'users.getInfo',
                'app_id' => $this->clientId,
            ),
        ));

		$info = (array)$info[0];

		$this->attributes['id'] = $info['uid'];
		$this->attributes['name'] = $info['first_name'] . ' ' . $info['last_name'];
        $this->attributes['url'] = $info['link'];


        $info = (array)$info;
        if (!empty($info['birthday']))
        {
//            Yii::log($info['birthday']);
//            Yii::log(print_r(DateTime::createFromFormat("d.m.Y",$info['birthday']), true));
//            Yii::log(print_r(DateTime::createFromFormat("d.m.Y",$info['birthday'])->format("Y-m-d"), true));
            $this->attributes['birthday'] = DateTime::createFromFormat("d.m.Y",$info['birthday'])->format("Y-m-d");
        }

        $this->attributes['userPhoto'] = $info['pic_big'];

        $this->attributes +=(array)$info;
	}

}