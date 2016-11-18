<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016-08-02
 * Time: 12:53
 */
namespace tit\ubi\controllers;

use app\components\Controller;
use tit\ubi\model\GlobalUsers;
use yii\helpers\Url;
use yii\web\View;

class NotificationController extends Controller
{
    public function actionLoad(){
        
    }
    public function actionUpdate(){
        $user = \Yii::$app->guser->get();
        return $this->renderAjax("notification", ["user" => $user]);
    }
}