<?php
/**
 * Created by PhpStorm.
 * User: Igor
 * Date: 2015-06-16
 * Time: 17:22
 */

namespace app\assets;


use yii\web\AssetBundle;

class IdealImageSliderAsset extends AssetBundle
{
    public $sourcePath = '@bower/ideal-image-slider';

    public $css = [
        'ideal-image-slider.css',
    ];

    public function __construct()
    {
        $this->js = [
            YII_DEBUG ? "ideal-image-slider.js" : "ideal-image-slider.min.js",
        ];
    }



}