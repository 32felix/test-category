<?php

namespace app\components\utils;
use app\models\Images;
use Yii;

/**
 * Created by PhpStorm.
 * User: Олежа
 * Date: 06.09.2016
 * Time: 0:31
 */
class OrderUtils
{

    public static function getSum($orderId)
    {
        $sql = "SELECT SUM(OP.count*PP.price)
                FROM OrderProducts OP
                LEFT JOIN ProductsPrices PP ON PP.id=OP.productPriceId
                WHERE OP.orderId=:orderId";

        $count = Yii::$app->db->createCommand($sql)->bindValues(['orderId' => $orderId])->queryScalar();
        $count = $count?$count:0;

        return sprintf('%.2f', $count);
    }

    public static function getSumItem($id)
    {
        $sql = "SELECT SUM(OP.count*PP.price)
                FROM OrderProducts OP
                LEFT JOIN ProductsPrices PP ON PP.id=OP.productPriceId
                WHERE OP.id=:id";

        $count = Yii::$app->db->createCommand($sql)->bindValues(['id' => $id])->queryScalar();
        $count = $count?$count:0;

        return sprintf('%.2f', $count);
    }

}