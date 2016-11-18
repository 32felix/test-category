<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "Orders".
 *
 * @property integer $id
 * @property string $userName
 * @property integer $userId
 * @property string $telephone
 * @property string $email
 * @property string $ip
 * @property string $status
 * @property string $userAgent
 * @property integer $addressId
 * @property string $timeCreate
 * @property string $timeUpdate
 * @property integer $deleted
 */
class Orders extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Orders';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ip', 'status', 'userAgent', 'userName', 'telephone', 'email'], 'string'],
            [['userId', 'addressId', 'deleted'], 'integer'],
            [['timeCreate', 'timeUpdate'], 'safe'],
        ];
    }




    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userName' => "Ім'я користувача",
            'userId' => 'ID користувача',
            'telephone' => 'Телефон',
            'email' => 'E-mail',
            'ip' => 'Ip користувача',
            'userAgent' => 'Агент користувача',
            'status' => 'Статус замовлення',
            'addressId' => 'ID адреси',
            'timeCreate' => 'Час створення',
            'timeUpdate' => 'Час редагування',
            'deleted' => 'Видалений',
        ];
    }

}
