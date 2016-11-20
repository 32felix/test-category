<?php

namespace tit\ubi\widgets;
/**
 * Created by JetBrains PhpStorm.
 * User: ertong
 * Date: 8/16/13
 * Time: 8:46 PM
 * To change this template use File | Settings | File Templates.
 */
use tit\ubi\model\GlobalUsers;
use yii\base\Widget;

class RegisterFormWidget extends Widget
{
	public $action= array("/ubi/user/register");
	public $model = null;

	public function run()
	{
		if (!isset($this->model) || empty($this->model))
        {
            $this->model = new GlobalUsers();
            $this->model->scenario = GlobalUsers::SCENARIO_REGISTRATION;
        }
		return $this->render('registerFormWidget',['model'=>$this->model]);
	}
}