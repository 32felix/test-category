<?php

namespace tit\ubi\widgets;

use tit\ubi\model\form\LoginForm;
use tit\ubi\model\GlobalUsers;
use tit\ubi\UbiModule;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

class ChangeEmailWidget extends Widget
{

    public function init()
    {
    }


    public function run()
    {
        $gUser = GlobalUsers::findOne(\Yii::$app->user->id);
        $model = new GlobalUsers();
        return $this->render("changeEmailWidget",array("widget"=>$this, "model"=>$model, 'gUser'=>$gUser));
    }
}