<?php

namespace app\components\utils;
use app\models\Params;
use Yii;

class ParamsUtils
{
    static public function selectParam($key, $defaultValue)
    {
        if (!$value = Yii::$app->cache->get($key))
        {
            $value = Params::findOne(['key' => $key, 'deleted' => 0]);
            if ($value)
                $value = $value->value;
            else
                $value = $defaultValue;
            Yii::$app->cache->set($key, $value, 3*3600);
        }
        return $value;
    }
}


