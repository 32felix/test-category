<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\Orders;
use yii\console\Controller;

class BonusController extends Controller
{

    public function actionIndex()
    {
        $model = Orders::find()
            ->where('status IS NOT NULL')
            ->andWhere(['deleted' => 0, 'YEAR(timeUpdate)' => date('Y'), 'MONTH(timeUpdate)' => date('n'), 'DAY(timeUpdate)' => date('j')])
            ->orderBy('RAND()')
            ->limit(1)
            ->one();

        \Yii::$app->db->createCommand('INSERT INTO Bonuses(userId) VALUE('.$model['userId'].')')->execute();
    }
}