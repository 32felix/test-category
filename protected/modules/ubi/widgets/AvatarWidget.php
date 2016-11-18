<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Yon
 * Date: 8/16/13
 * Time: 8:46 PM
 * To change this template use File | Settings | File Templates.
 */
namespace tit\ubi\widgets;


use tit\ubi\model\GlobalUsers;
use tit\ubi\UbiAsset;
use yii\base\ErrorException;
use yii\base\Widget;
use Yii;


class AvatarWidget extends Widget
{

    public function run()
    {
        $model = GlobalUsers::findOne(['id'=>Yii::$app->user->id]);
        if(isset($model) && !empty($model))
            return $this->render("avatarWidget", ['model'=>$model]);
        else
            throw new ErrorException(404, "User not found");
    }
}