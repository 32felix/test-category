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
class GlobalsUtils
{

    /**
     * This is the shortcut to Yii::t() with default category = 'stay'
     */
    static public function t($category, $message, $params = array(), $language = null)
    {
        return \Yii::t($category, $message, $params, $language);
    }

    /**
     * usage example: issetdef($_REQUEST['param'], 'default value', array('possible value 1', 'possible value 2'))
     *
     * @param $var
     * @param $def
     * @param $possible
     * @return
     */
    static public function issetdef(&$var, $def = null, $possible = null)
    {
        if (isset($var)) {
            if ($possible === null)
                return $var;
            if (is_array($possible))
                return in_array($var, $possible) ? $var : $def;
            if ($var == $possible)
                return $var;
        }
        return $def;
    }

    /**
     * usage example: nemptydef($_REQUEST['param'], 'default value', array('possible value 1', 'possible value 2'))
     * @param $var
     * @param $def
     * @param $possible
     * @return
     */
    static public function nemptydef(&$var, $def = null, $possible = null)
    {
        if (!empty($var)) {
            if ($possible === null)
                return $var;
            if (is_array($possible))
                return in_array($var, $possible) ? $var : $def;
            if ($var == $possible)
                return $var;
        }
        return $def;
    }
}
