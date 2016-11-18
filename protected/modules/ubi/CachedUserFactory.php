<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2014-12-20
 * Time: 17:31
 */

namespace tit\ubi;


use app\models\User;
use tit\ubi\model\GlobalUsers;
use tit\utils\GUser\CachedUserDescFactory;
use tit\utils\GUser\UserDesc;
use yii\web\IdentityInterface;

class CachedUserFactory extends CachedUserDescFactory
{
    public function loadUserAttributes($id)
    {
        if ($id==0)
        {
            return ["id"=>0, "name"=>"Guest"];
        }
        $class = \Yii::$app->user->identityClass;

        /* @var $identity IdentityInterface */
        $identity = $class::findIdentity($id);

        $attr = [];

        if ($identity instanceof UserDesc)
        {
            $attr += $identity->getUserAttributes();
        }

        $gUser = GlobalUsers::findOne(["id"=>$id]);
        if ($gUser!=null)
            $attr += $gUser->attributes;

        return $attr;
    }


    public function getUserCount()
    {
        $class = \Yii::$app->user->identityClass;

        /* @var $identity IdentityInterface */
//        $class::findIdentity($id);
        return \Yii::$app->db->createCommand("SELECT COUNT(*) FROM ".$class::tableName())->queryScalar();

//        $gUser = GlobalUsers::find()->all();
//        return count($gUser);
    }
}