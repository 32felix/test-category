<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 8/18/14
 * Time: 4:02 PM
 */

namespace tit\ubi;


use app\models\PotentialUserToUser;
use app\modules\Partner;
use app\modules\ubi\model\UserMail;
use tit\ubi\model\GlobalUsers;
use tit\ubi\model\UsersSocialAccounts;
use UbiCache;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\base\Module;
use app\models\Users;
use nodge\eauth\ErrorException;
use Yii;
use yii\db\Connection;

class UbiModule extends Module
{
    public static $secret = "qowierjjdfja3948";

    public $globalUserTableName = 'GlobalUsers';
    public $ubiDatabaseName = 'db';
    public $localUserTableName = 'User';
    public $localDatabaseName = 'db';
    public $params;

    public function init()
    {
        parent::init();

        Yii::setAlias('@tit/ubi', __DIR__);

        \Yii::$app->i18n->translations['tit/ubi'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => __DIR__ . '/messages',
            'fileMap' => [
                'tit/ubi' => 'ubi.php',
            ],
        ];
    }

    /**
     * @return Connection
     */
    public function getDbUbi()
    {
        return Yii::$app->{$this->ubiDatabaseName};
    }

    /**
     * @return Connection
     */
    public function getDb()
    {
        return Yii::$app->{$this->localDatabaseName};
    }


    /**
     * @param \nodge\eauth\ServiceBase $service
     * @return Users
     * @throws ErrorException
     */
    public function findLocalUserByEAuth($service, $extra=[])
    {
        if (!$service->getIsAuthenticated()) {
            throw new ErrorException('EAuth user should be authenticated before creating identity.');
        } else {
            $usa = UsersSocialAccounts::find()->where(
                [
                    "provider" => $service->getServiceName(),
                    "providerId" => $service->getId(),
                ])->one();

            $gUser = null;

            if ($usa != null) {
                $usa->data = json_encode($service->getAttributes(), JSON_UNESCAPED_UNICODE);
                $usa->save(false);
                $gUser = GlobalUsers::find()->where(['id' => $usa->userid])->one();
                if (!$gUser)
                    $usa->delete();
            }

            if (!$gUser)
            {
                $email = strtolower($service->getAttribute('email'));
                $gUser = null;

                if ($email) {
                    $gUser = GlobalUsers::find()->where(["email" => $email])->one();
                    if (!$gUser) {
                        $userMail = UserMail::findVerified($email)->one();
                        if ($userMail)
                            $gUser = $userMail->user0;
                    }
                }

                //Connect?
//                if ($gUser == null && !Yii::$app->user->isGuest)
//                    $gUser = GlobalUsers::find()->where(['id' => Yii::$app->user->getId()])->one();

                if ($gUser == null) {
                    $gUser = new GlobalUsers();
                    $ref = GlobalUsers::getReferrer();
                    if (!empty($ref))
                        $gUser->referrer = $ref->id;
                    $gUser->save(false);
                }

                \Yii::$app->db->createCommand("REPLACE UsersSocialAccounts(userid,provider,providerId,data,email)
                    VALUES (:userid,:provider,:providerId,:data,:email)",
                    [
                        ':userid' => $gUser->id,
                        ':provider' => $service->getServiceName(),
                        ':providerId' => $service->getId(),
                        ':email' => $email,
                        ':data' => json_encode($service->getAttributes(), JSON_UNESCAPED_UNICODE)
                    ])->execute();
            }
            
            {
                if ($gUser->name == null)
                    $gUser->name = $service->getAttribute('name');

                $mail = strtolower(trim($service->getAttribute('email')));
                if (!empty($mail))
                {
                    if (!UserMail::findVerified(["address"=>$mail])->exists()) {
                        $userMail = UserMail::findOne(["user"=>$gUser->id, "address"=>$mail]);
                        if (!$userMail) {
                            $userMail = new UserMail();
                            $userMail->user = $gUser->id;
                            $userMail->address = $mail;
                            $userMail->save(false, ["user", "address"]);
                            $userMail->sendRegisterMail();
                            $userMail->save(false, ["timeVerificationSent"]);
                        }
                        if ($gUser->email == null) {
                            $u2 = GlobalUsers::findOne(['email' => $mail]);
                            if (empty($u2)) {
                                $gUser->email = $mail;
//                                $gUser->timeEmailVerified = null;
                            }
                        }
                    }
                }

//                if ($gUser->email == null && !empty($service->getAttribute('email'))) {
//                    $gUser->email = strtolower($service->getAttribute('email'));
//                    if ($gUser->email != null) {
//                        $u2 = GlobalUsers::findOne(['email' => $gUser->email]);
//                        if (!empty($u2)) {
////                            $gUser->unconfirmedEmail =$gUser->email;
//                            $gUser->email = null;
//                        }
//                    }
//                }

                if ($gUser->birthday == null)
                    $gUser->birthday = $service->getAttribute('birthday');

                if ($gUser->lastName == null)
                    $gUser->lastName = $service->getAttribute('lastname');

                if ($gUser->slug == null) {
                    $gUser->slug = $service->getAttribute('slug');
                    if ($gUser->slug != null) {
                        $u2 = GlobalUsers::findOne(['slug' => $gUser->slug]);
                        if (!empty($u2))
                            $gUser->slug = null;
                    }
                }

//                if (!$gUser->timeEmailVerificationSent)
//                    $gUser->sendRegisterMail();

                $gUser->save(false);
            }

            $localUser = $gUser->ensureLocalUser();

            return $localUser;
        }
    }

    public static function cachedParam($param, $user = null, $allowCached = null)
    {
        return self::getInstance()->getCache()->get($param, $user, $allowCached);
    }

}

