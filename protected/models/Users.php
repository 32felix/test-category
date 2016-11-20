<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "Users".
 *
 * @property integer $id
 * @property string $telephone
 * @property string $password
 * @property string $name
 * @property string $email
 * @property string $timeCreate
 * @property string $timeUpdate
 * @property string $timeAddAvatar
 * @property integer $verified
 * @property string $unconfirmedEmail
 * @property string $accessCode
 * @property integer $deleted
 */
class Users extends ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['timeCreate', 'timeUpdate', 'timeAddAvatar'], 'safe'],
            [['telephone', 'name'], 'string', 'max' => 50],
            [['password'], 'string', 'max' => 200],
            [['accessCode'], 'string'],
            [['verified', 'deleted'], 'integer'],
            [['email','unconfirmedEmail'], 'email'],
        ];
    }




    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'telephone' => 'Номер телефону',
            'password' => 'Пароль',
            'name' => 'Назва користувача',
            'timeCreate' => 'Час створення',
            'timeUpdate' => 'Час редагування',
            'timeAddAvatar' => 'Час добавлення аватарки',
            'verified' => 'Перевірений',
            'email' => 'E-mail',
            'unconfirmedEmail' => 'Не підтверджений e-mail',
            'deleted' => 'Видалений',
        ];
    }

    /**
     * Finds an identity by the given ID.
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return self::findOne(["id"=>$id]);
    }


    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|integer an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    public function getAuthKey()
    {
        return 0;
    }

    public function validateAuthKey($authKey)
    {
        return false;
    }




}
