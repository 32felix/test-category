<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2014-11-18
 * Time: 15:24
 */

namespace tit\ubi;

use tit\utils\assets\FileApiAsset;
use yii\web\AssetBundle;
use yii\web\View;

class UbiAsset  extends AssetBundle
{
    public $sourcePath = '@ubi/assets';
    public $css = [
        "index.less"
    ];
    public $js = [

    ];

    public $depends = [
        'yii\jui\JuiAsset',
//        FileApiAsset::class
    ];
    function __construct()
    {
        $this->sourcePath = __DIR__.'/assets';
    }

}
