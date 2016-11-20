<?php
/**
 * Created by PhpStorm.
 * User: Yura
 * Date: 11.03.2015
 * Time: 18:56
 */

namespace app\components;


use yii\web\AssetBundle;
use yii\web\User;

class Controller extends \yii\web\Controller
{
    /**
     * @param string $id
     * @param \yii\base\Module $module
     * @param array $config
     */
    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);


        if (\Yii::$app->request->isAjax)
        {
            $this->getView()->assetBundles['yii\web\JqueryAsset']=new AssetBundle();
            $this->getView()->assetBundles['yii\web\YiiAsset']=new AssetBundle();
            $this->getView()->assetBundles['yii\jui\CoreAsset']=new AssetBundle();
            $this->getView()->assetBundles['yii\bootstrap\BootstrapAsset']=new AssetBundle();
            $this->getView()->assetBundles['tit\ubi\UbiAsset']=new AssetBundle();
            $this->getView()->assetBundles['yii\jui\JuiAsset']=new AssetBundle();
            $this->getView()->assetBundles['app\modules\blog\BlogAssets']=new AssetBundle();
//            $this->getView()->assetBundles[AjaxSubmitAsset::class]=new AssetBundle();
//            $this->getView()->assetBundles[ActiveFormAsset::class]=new AssetBundle();
//            $this->getView()->assetBundles[ValidationAsset::class]=new AssetBundle();
//            $this->getView()->assetBundles[CaptchaAsset::class]=new AssetBundle();

            //\Yii::$app->view->assetManager->bundles = false;

        }

    }


    /**
     * Get ip address
     * @return null
     */
    public static function getRemoteAddr()
    {
        $res = null;
        if (isset($_SERVER["REMOTE_ADDR"]))
            $res = self::issetdef($_SERVER["REMOTE_ADDR"]);
        if (isset($_SERVER["HTTP_X_REAL_IP"]))
            $res = self::issetdef($_SERVER["HTTP_X_REAL_IP"]);
        return $res;
    }


    /**
     * usage example: issetdef($_REQUEST['param'], 'default value', array('possible value 1', 'possible value 2'))
     *
     * @param $var
     * @param $def
     * @param $possible
     * @return
     */
    public static function issetdef(&$var, $def=null, $possible=null)
    {
        if (isset($var))
        {
            if ($possible===null)
                return $var;
            if (is_array($possible))
                return in_array($var, $possible) ? $var : $def;
            if ($var == $possible)
                return $var;
        }
        return $def;
    }


}