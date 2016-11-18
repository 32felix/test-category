<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "Params".
 *
 * @property integer $id
 * @property string $key
 * @property string $value
 * @property integer $deleted
 */
class Params extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Params';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key', 'value'], 'string'],
            [['deleted'], 'integer'],
        ];
    }




    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'key' => 'Сторінка',
            'value' => 'Значення',
            'deleted' => 'Видалений',
        ];
    }

}
