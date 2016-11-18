<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ertong
 * Date: 8/22/13
 * Time: 6:24 PM
 * To change this template use File | Settings | File Templates.
 */

namespace tit\ubi\widgets;

use tit\ubi\model\GlobalUsers;
use yii\base\Widget;


class RestorePasswordRequestFormWidget extends Widget
{
    public $action= array("/ubi/user/restorePasswordRequest");
    public $model;

    public function run()
    {
        if (empty($this->model))
        {
            $this->model = new GlobalUsers();
            $this->model->scenario = GlobalUsers::SCENARIO_RESTORE_PASSWORD_REQUEST;
        }
        return $this->render("restorePasswordRequestFormWidget", ['model'=>$this->model]);
    }
}