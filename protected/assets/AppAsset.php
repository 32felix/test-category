<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;
use yii\web\View;
use yii\jui\JuiAsset;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $sourcePath = __DIR__;
    public $css = [
        'css/main.less',
        'css/fonts.less',
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        JqueryAsset::class,
        JuiAsset::class,
        IdealImageSliderAsset::class,
        SlickCarouselAsset::class,
    ];

    public $jsOptions = [
        "position"=>View::POS_HEAD,
    ];

    function __construct()
    {
        $this->publishOptions = [
            "beforeCopy" => function ($from, $to) {
                return substr($from, -strlen(".php")) != ".php";
            },
        ];
    }


}
