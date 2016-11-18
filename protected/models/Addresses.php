<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "Addresses".
 *
 * @property integer $id
 * @property string $street
 * @property integer $build
 * @property integer $flat
 * @property string $timeCreate
 */
class Addresses extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Addresses';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['street', 'string', 'min' => 3],
            [['build', 'flat'], 'integer'],
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
            'street' => 'Назва вулиці',
            'build' => 'Номер будинку',
            'flat' => 'Номер квартири',
            'timeCreate' => 'Час створення',
        ];
    }

}
