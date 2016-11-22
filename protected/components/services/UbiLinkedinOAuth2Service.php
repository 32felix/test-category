<?php
namespace app\components\services;

use nodge\eauth\services\LinkedinOAuth2Service;

class UbiLinkedinOAuth2Service extends LinkedinOAuth2Service {

    protected function fetchAttributes()
    {
//        <person>
//          <id>
//          <first-name />
//          <last-name />
//          <headline>
//          <location>
//            <name>
//            <country>
//              <code>
//            </country>
//          </location>
//          <industry>
//          <distance>
//          <relation-to-viewer>
//            <distance>
//          </relation-to-viewer>
//          <num-recommenders>
//          <current-status>
//          <current-status-timestamp>
//          <connections total="" >
//          <summary/>
//          <positions total="">
//            <position>
//              <id>
//              <title>
//              <summary>
//              <start-date>
//                <year>
//                <month>
//              </start-date>
//              <is-current>
//              <company>
//                <name>
//              </company>
//            </position>
//          <educations total="">
//            <education>
//              <id>
//              <school-name>
//              <degree>
//              <start-date>
//                <year>
//              </start-date>
//              <end-date>
//                <year>
//              </end-date>
//            </education>
//          </educations>
//          <member-url-resources>
//            <member-url>
//              <url>
//              <name>
//            </member-url>
//          <api-standard-profile-request>
//            <url>
//            <headers>
//              <http-header>
//                <name>
//                <value>
//              </http-header>
//            </headers>
//          </api-standard-profile-request>
//          <site-standard-profile-request>
//            <url>
//          </site-standard-profile-request>
//          <picture-url>
//        </person>

        $fields=array(
            'id',
            'first-name',
            'last-name',
            'headline',
            'location',
            'industry',
            'distance',
            'relation-to-viewer',
            'num-recommenders',
            'current-status',
            'current-status-timestamp',
//            'connections',
            'summary',
            'positions',
            'educations',
            'member-url-resources',
            'api-standard-profile-request',
            'site-standard-profile-request',
            'picture-url',

            'public-profile-url',
            'email-address',
        );

        $tokenData = $this->getAccessTokenData();
        $this->attributes["token"] = $tokenData;

		$info = $this->makeSignedRequest('http://api.linkedin.com/v1/people/~:('.implode(",", $fields).')', array(), false); // json format not working :(
        Yii::log($info);
		$info = $this->parseInfo($info);

		$this->attributes['id'] = $info['id'];
		$this->attributes['name'] = $info['first-name'] . ' ' . $info['last-name'];
        $this->attributes['url'] = $info['public-profile-url'];
        $this->attributes['email'] = $info['email-address'];
        $this->attributes['userPhoto'] = $info['picture-ur'];

        $this->attributes +=(array)$info;
	}

}