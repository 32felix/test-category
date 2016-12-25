<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "Images".
 *
 * @property integer $id
 * @property string $timeUpdate
 * @property integer $ownerId
 * @property string $ownerType
 * @property string $ext
 */
class Images extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Images';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ownerType', 'ext'], 'string'],
            [['ownerId'], 'integer'],
            [['timeUpdate'], 'safe'],
        ];
    }




    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ownerId' => 'Назва продукту',
            'ownerType' => 'Тип продукту',
            'ext' => 'Розширення файлу',
            'timeUpdate' => 'Час редагування',
        ];
    }

}
