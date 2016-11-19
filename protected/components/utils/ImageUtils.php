<?php

namespace app\components\utils;
use app\models\Images;
use Gregwar\Captcha\CaptchaBuilder;
use Yii;

/**
 * Created by PhpStorm.
 * User: Олежа
 * Date: 06.09.2016
 * Time: 0:31
 */
class ImageUtils
{

    public static function genImageUrl($id, $time, $w, $h=null)
    {
        if ($time == null) {
            $time = \Yii::$app->cache->get("img-".$id);
            if (!$time) {
                $data = Images::find()->select(['timeUpdate'])->where(['id' => $id])->asArray()->one();
                $time = $data['timeUpdate'];
                \Yii::$app->cache->set("img-".$id, $time, 60*5);
            }
        }

        $ext = Images::findOne($id)->ext;

        $time = base_convert(strtotime($time), 10, 36);

        $path = preg_replace("~~","/", $id);
        $path=trim($path, "/");

        if ($w && $h)
            $path = "/media/res/{$path}/{$id}.{$time}.{$w}x{$h}.{$ext}";
        else if ($w)
            $path = "/media/res/{$path}/{$id}.{$time}.{$w}.{$ext}";
        else if ($h)
            $path = "/media/res/{$path}/{$id}.{$time}.x{$h}.{$ext}";
        else
            $path = "/media/res/{$path}/{$id}.{$time}.{$ext}";

//        if (YII_DEBUG)
            $path='http://'.$_SERVER["SERVER_NAME"].$path;

        return $path;
    }

    public static function resourceIdToDirPass($resourceId){
        $number = $resourceId;
        $array = array();
        while ($number > 0) {
            $array[] = $number % 10;
            $number = intval($number / 10);
        }
        $arrayNumbers = array_reverse($array);
        $pass = '';

        foreach($arrayNumbers as $number)
            $pass .= '/'.$number;
        return $pass;
    }

    public static function captchaBuild(){
        $builder = new CaptchaBuilder;
        $builder->build();
        Yii::$app->cache->set('captcha-register', $builder->getPhrase());

        return $builder->inline();
    }

}