<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\Bonuses;
use app\models\Orders;
use app\models\Params;
use app\models\Users;
use yii\console\Controller;

class BonusController extends Controller
{

    public function actionIndex()
    {
        $time = Params::findOne(['key' => 'workFinish', 'deleted' => 0]);
        if ($time)
            $time = $time->value;
        else
            $time = '23:59';

        $time = strtotime($time) + 60 * 60;
        if ($time > time())
            sleep($time-time());

        $bonus = Bonuses::findOne(['timeCreate' => date('Y-m-d')]);

        if (!$bonus)
        {
            $model = Orders::find()
                ->where('status IS NOT NULL AND userId IS NOT NULL')
                ->andWhere(['deleted' => 0])
                ->andWhere("timeUpdate > '" . date('Y-m-d') . "'")
                ->orderBy('RAND()')
                ->limit(1)
                ->one();

            $user = Users::findOne($model['userId']);

            \Yii::$app->db->createCommand('INSERT INTO Bonuses(userId) VALUE('.$model['userId'].')')->execute();

            \Yii::$app->db->createCommand('
                      INSERT INTO Orders(userName,userId,telephone,email,bonus,status)
                      VALUE('.$user->name . ',' . $user->id . ',' . $user->telephone . ',' . $user->email . ",1,'New')")
                ->execute();
        }

        sleep(($time+60*60*24)-time());
    }
}