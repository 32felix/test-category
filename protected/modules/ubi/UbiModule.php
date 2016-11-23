<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 8/18/14
 * Time: 4:02 PM
 */

namespace app\modules\ubi;


use app\model\UsersSocialAccounts;
use UbiCache;
use yii\base\Module;
use app\models\Users;
use nodge\eauth\ErrorException;
use Yii;
use yii\db\Connection;

class UbiModule extends Module
{
    public $globalUserTableName = 'Users';
    public  $ubiDatabaseName = 'db';
    public $localUserTableName = 'Users';
    public $localDatabaseName = 'db';
    public $params;

    /**
     * @var UbiCache
     */
    public $cache = [
        "class"=>'app\modules\ubi\UbiCache',
        'cacheTime' => 600,
        'blocksParams' => ['password','id'],
        'checkUbiDB' => true,
    ];

    public function init()
    {
        parent::init();

        Yii::setAlias('@tit/ubi', __DIR__);

        \Yii::$app->i18n->translations['tit/ubi'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => __DIR__.'/messages',
            'fileMap' => [
                'tit/ubi' => 'ubi.php',
            ],
        ];
    }

    /**
     * @return UbiCache
     */
    public function getCache()
    {
        if (is_array($this->cache))
            $this->cache = Yii::createObject($this->cache);
        return $this->cache;
    }

    /**
     * @return Connection
     */
    public function getDbUbi()
    {
        return 'db';
    }

    /**
     * @return Connection
     */
    public function getDb()
    {
        return 'db';
    }


    /**
     * @param \nodge\eauth\ServiceBase $service
     * @return Users
     * @throws ErrorException
     */
    public function findLocalUserByEAuth($service)
    {
        if (!$service->getIsAuthenticated()) {
            throw new ErrorException('EAuth user should be authenticated before creating identity.');
        }
        elseif ($service->getIsAuthenticated())
        {
            $usa = UsersSocialAccounts::find()->where(
                [
                    "provider"=>$service->getServiceName(),
                    "providerId"=>$service->getId(),
                ])->one();

            if ($usa!=null) {
                $usa->data = json_encode($service->getAttributes(), JSON_UNESCAPED_UNICODE);
                $usa->save(false);
                $gUser = Users::find()->where(['id' => $usa->userid])->one();
            }

            if ($usa==null || $gUser==null) {
                $email = strtolower($service->getAttribute('email'));
                $gUser = null;

                if ($email)
                    $gUser = Users::find()->where(["email" => $email])->one();

                if ($gUser == null && !Yii::$app->user->isGuest)
                    $gUser = Users::find()->where(['id' => Yii::$app->user->getId()])->one();

                if ($gUser == null) {
                    $gUser = new Users();
                    $gUser->save(false);
                }

                \Yii::$app->db->createCommand("REPLACE UsersSocialAccounts(userid,provider,providerId,data)
                    VALUES (:userid,:provider,:providerId,:data)",
                    [
                        ':userid' => $gUser->id,
                        ':provider' => $service->getServiceName(),
                        ':providerId' => $service->getId(),
                        ':data' => json_encode($service->getAttributes(), JSON_UNESCAPED_UNICODE)
                    ])->execute();
            }
            else
            {
                //Global user exists
            }
            {
                if ($gUser->name==null)
                    $gUser->name = $service->getAttribute('name');
                if ($gUser->email==null  && !empty($service->getAttribute('email'))) {
                    $gUser->email = strtolower($service->getAttribute('email'));
                    if ($gUser->email!=null)
                    {
                        $u2 = Users::findOne(['email'=>$gUser->email]);
                        if (!empty($u2)) {
                            $gUser->unconfirmedEmail =$gUser->email;
                            $gUser->email = null;
                        }
                    }
                }

                $gUser->save(false);

                return $gUser;
            }
        }
    }

    public static function cachedParam($param, $user=null, $allowCached=null)
    {
        return self::getInstance()->getCache()->get($param,$user,$allowCached);
    }

}

