<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "UsersAddress".
 *
 * @property integer $userId
 * @property integer $addressId
 */
class UsersAddress extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'UsersAddress';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'addressId'], 'integer'],
        ];
    }




    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'userId' => 'ID користувача',
            'addressId' => 'ID адреси',
        ];
    }

}
