<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ProductsSize".
 *
 * @property integer $id
 * @property string $size
 * @property string $type
 * @property integer $countMen
 */
class ProductsSize extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ProductsSize';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['countMen'], 'integer'],
            [['type', 'size'], 'string'],
        ];
    }




    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'size' => 'Розмір продукту',
            'type' => 'Тип продукту',
            'countMen' => 'Кількість людей',
        ];
    }

}
