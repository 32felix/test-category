<?php

namespace tit\ubi\controllers;

use app\components\Controller;
use app\modules\ubi\model\form\RegisterForm;
use Yii;

class RegisterController extends Controller
{

    public function actionJson()
    {
        $model = new RegisterForm();
        $model->load($_REQUEST, '');

        $model->validate();

        $res=[];

        if ($model->hasErrors())
            $res["errors"] = $model->getErrors();
        else
        {
            $model->createUser($_REQUEST["extra"] ?? []);
            $res["status"]="success";
        }

        if (!empty($_SERVER["HTTP_REFERER"])) {
            $url = parse_url($_SERVER["HTTP_REFERER"]);
            header("Access-Control-Allow-Origin: " . $url["scheme"] . "://" . $url["host"]);
            header("Access-Control-Allow-Credentials : true");
        }
        header("Content-Type: application/json");
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        die;
    }

    public function actionForm(){
        $model = new RegisterForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $model->createUser();

            if(isset($_SESSION['ab_lastAuthTest'])) {
                Yii::$app->ab->markConversion($_SESSION['ab_lastAuthTest']);
            }

            return $this->renderAjax('congratsForm', [
                'model' => $model
            ]);
        } else {

            return $this->renderAjax('registerForm', [
                'model' => $model
            ]);
        }
    }


}