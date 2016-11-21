<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "Bonuses".
 *
 * @property integer $id
 * @property string $userId
 * @property integer $timeCreate
 */
class Bonuses extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Bonuses';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId'], 'integer'],
            [['timeCreate'], 'safe'],
        ];
    }




    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userId' => 'ID користувача',
            'timeCreate' => 'Час створення',
        ];
    }

}
