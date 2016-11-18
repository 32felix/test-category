<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "Images".
 *
 * @property integer $id
 * @property string $timeUpdate
 * @property integer $productId
 * @property string $productType
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
            [['productType', 'ext'], 'string'],
            [['productId'], 'integer'],
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
            'productId' => 'Назва продукту',
            'productType' => 'Тип продукту',
            'ext' => 'Розширення файлу',
            'timeUpdate' => 'Час редагування',
        ];
    }

}
