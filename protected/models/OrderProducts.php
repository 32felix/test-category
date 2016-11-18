<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "OrderProducts".
 *
 * @property integer $orderId
 * @property integer $productPriceId
 * @property integer $count
 */
class OrderProducts extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'OrderProducts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['orderId', 'productPriceId', 'count'], 'integer'],
        ];
    }




    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'orderId' => 'ID замовдення',
            'productPriceId' => 'ID ціни продукту',
            'count' => 'Кількість',
        ];
    }

}
