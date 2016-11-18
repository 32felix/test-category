<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ProductsPrices".
 *
 * @property integer $id
 * @property integer $productId
 * @property integer $sizeId
 * @property integer $price
 */
class ProductsPrices extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ProductsPrices';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['productId', 'sizeId', 'price'], 'integer'],
        ];
    }




    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'productId' => 'ID продукту',
            'sizeId' => 'ID розміру продукту',
            'price' => 'Ціна',
        ];
    }

}
