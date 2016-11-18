<?php

namespace tit\ubi\widgets;

/**
 * Created by JetBrains PhpStorm.
 * User: ertong
 * Date: 8/16/13
 * Time: 8:46 PM
 * To change this template use File | Settings | File Templates.
 */

use tit\ubi\model\form\LoginForm;
use tit\ubi\UbiModule;
use yii\base\Widget;

class LoginFormWidget extends Widget
{
    public $action= array("/ubi/user/login");
    public $successfulUrl=null;
    public $model;

    public function init()
    {
        $id = $this->getId(false);
        if (empty($id))
            $this->setId($this->options["id"]);
    }


    public function run()
    {
        \Yii::$app->getModule("ubi");
        if (empty($this->model))
            $this->model = new LoginForm();
        return $this->render("loginFormWidget",array("model"=>$this->model));
    }
}