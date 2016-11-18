<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ertong
 * Date: 8/16/13
 * Time: 8:46 PM
 * To change this template use File | Settings | File Templates.
 */
namespace tit\ubi\widgets;


use tit\ubi\model\form\ChangePassForm;
use tit\ubi\model\GlobalUsers;
use yii\base\View;
use yii\base\Widget;
use Yii;


class ChangePassFormWidget extends Widget
{
    public $action= array("/ubi/user/changePass");
    public $successfulUrl=null;
    /**
     * @var ChangePassForm
     */
    public $model;
    public $successMessage;

    public function run()
    {
        if (empty($this->model))
        {
            $user = GlobalUsers::find()->where(['id' => Yii::$app->user->getId()])->one();
            if(empty($user))
            {
                return "User have not been found";
            }
            $model = new ChangePassForm();
            $model->scenario = $user->password==null?ChangePassForm::SCENARIO_SET:ChangePassForm::SCENARIO_CHANGE;
        }
        return $this->render("changePassFormWidget", ['model'=>$model]);
    }
}