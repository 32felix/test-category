<?php

namespace tit\ubi\widgets;

use tit\ubi\model\form\LoginForm;
use tit\ubi\model\GlobalUsers;
use app\modules\ubi\model\UserPhone;
use tit\ubi\UbiModule;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

class ChangePhoneWidget extends Widget
{

    public function init()
    {
    }


    public function run()
    {
        $gUser = GlobalUsers::findOne(\Yii::$app->user->id);
        $model = new UserPhone();
        return $this->render("changePhoneWidget",array("widget"=>$this, "model"=>$model, 'gUser'=>$gUser));
    }
}