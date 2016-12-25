<?php
/**
 * Created by PhpStorm.
 * User: Igor
 * Date: 2015-06-16
 * Time: 17:22
 */

namespace app\assets;


use yii\web\AssetBundle;

class SlickCarouselAsset extends AssetBundle
{
    public $sourcePath = '@bower/slick-carousel/slick';

    public $css = [
        'slick.css',
        'slick-theme.css',
    ];

    public function __construct()
    {
        $this->js = [
            YII_DEBUG ? "slick.js" : "slick.min.js",
        ];
    }



}