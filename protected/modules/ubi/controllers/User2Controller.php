<?php

namespace tit\ubi\controllers;

use app\components\Controller;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\Point;
use tit\ubi\model\form\AddEmailForm;
use tit\ubi\model\UsersSocialAccounts;
use tit\ubi\UbiAsset;
use tit\ubi\utils\FileAPI;
use nodge\eauth\ErrorException;
use tit\ubi\model\Avatars;
use tit\ubi\model\form\ChangePassForm;
use tit\ubi\model\GlobalUsers;
use tit\ubi\model\form\LoginForm;
use tit\ubi\UbiModule;
use tit\utils\CurlBrowser;
use Yii;
use yii\captcha\CaptchaAction;
use yii\helpers\BaseUrl;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\imagine\Image;
use yii\log\Logger;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\View;

class User2Controller extends Controller
{

    public function actionLoginPopupResult()
    {
        $model = new LoginForm();
        $model->scenario = LoginForm::SCENARIO_LOGIN;

        if (!empty($_REQUEST["preload"]))
        {
            header("Content-Type: application/javascript");
            header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 3600));
            header_remove("Pragma");
            header_remove("Cache-Control");
            $view = $this->renderAjax('loginPopup', ['model'=>$model]);
            echo "RPopup.preload('".Url::toRoute("/ubi/user2/login-popup")."',".json_encode($view).")";
        }
        else
            return $this->renderAjax('loginPopupResult', ['model'=>$model]);
    }

    public function actionLoginPopup()
    {
        $model = new LoginForm();
        $model->scenario = LoginForm::SCENARIO_LOGIN;

        if (!empty($_REQUEST["preload"]))
        {
            header("Content-Type: application/javascript");
            header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 3600));
            header_remove("Pragma");
            header_remove("Cache-Control");
            $view = $this->renderAjax('loginPopup', ['model'=>$model]);
            echo "RPopup.preload('".Url::toRoute("/ubi/user2/login-popup")."',".json_encode($view).")";
        }
        else
            return $this->renderAjax('loginPopup', ['model'=>$model]);
    }

    public function actionLoginByForm()
    {
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            header("Content-Type: application/json");
            $res=[];
            $res["status"]="ok";
            echo json_encode($res);
            die;
        }
        else
        {
            return $this->renderAjax('loginForm', [
                'model' => $model,
            ]);
        }
    }

    public function actionAddEmailPopup()
    {
        $model = new AddEmailForm();

        return $this->renderAjax('addEmailPopup', ['model'=>$model]);
    }

    public function actionAddEmail()
    {
        $model = new AddEmailForm();

        $model->load(Yii::$app->request->post()) && $model->validate();

        $model->email=strtolower($model->email);

        if (!$model->hasErrors() && \Yii::$app->user->isGuest) {
            $model->addError("email","Вы не авторизированы. Попробуйте ввойти еще раз.");
        }

        $gUser = GlobalUsers::findOne(["id"=>\Yii::$app->user->getId()]);
        if (!$model->hasErrors()) {
            if (!empty($gUser->email))
                $model->addError("email","Для пользователя №$gUser->id уже установлен email $gUser->email");
        }

        if (!$model->hasErrors()) {
            $more = GlobalUsers::findOne(["email"=>$model->email]);
            if (!empty($more))
                $model->addError("email","Указанный адрес уже используется. Попробуйте востановить пароль.");
        }

        if (!$model->hasErrors()) {
            $gUser->unconfirmedEmail = $model->email;
            $gUser->save(false);
            header("Content-Type: application/json");
            $res=[];
            $res["status"]="ok";
            echo json_encode($res);
            die;
        }

        return $this->renderAjax('addEmailForm', [
            'model' => $model,
        ]);
    }

    public function actionLoginByEauth()
    {
//        if (!\Yii::$app->user->isGuest) {
//            return $this->goHome();
//        }

//        $callback = empty($_REQUEST["callback"]) ? null : function($result)
        Yii::$app->session->set("android-id",isset($_REQUEST["android"])?$_REQUEST["android"]:null);

        $callback = function($result)
        {
            if (Yii::$app->session->get("android-id", false))
            {
                $name = "android-".Yii::$app->session->get("android-id");
                $row = Yii::$app->db->createCommand("SELECT * FROM UserTokens WHERE user=:user AND name=:name", [
                    "user"=>Yii::$app->user->id,
                    "name"=>$name,
                ])->queryOne();

                if (empty($row))
                {
                    $token=base64_encode(hash("sha256",uniqid().uniqid().uniqid().uniqid().uniqid(),true));
                    $token = str_replace("=", "", $token);
                    Yii::$app->db->createCommand("INSERT INTO UserTokens(user,name,token) VALUES(:user,:name,:token)",[
                        "user"=>Yii::$app->user->id,
                        "name"=>$name,
                        "token"=>$token,
                    ])->execute();
                    $tokenId = Yii::$app->db->getLastInsertID();
                }
                else {
                    $token = $row["token"];
                    $tokenId = $row["id"];
                }
//                header("Location: );
                $this->redirect("therespo://local.therespo.com?id=".Yii::$app->user->id."&tid=".$tokenId."&token=".urlencode($token));
//                die;
            }
            else {
                $result = json_encode($result);
                echo "<script>
                    if (window.opener) {
                        window.close();
                        window.opener.oauth_result($result);
                    }
                    </script>";
            }
        };

        $serviceName = Yii::$app->getRequest()->getQueryParam('service');
        if (isset($serviceName)) {
            /** @var $eauth \nodge\eauth\ServiceBase */
            $eauth = Yii::$app->get('eauth')->getIdentity($serviceName);

            $eauth->setRedirectUrl(Yii::$app->getUser()->getReturnUrl());
            $eauth->setCancelUrl(Yii::$app->getUrlManager()->createAbsoluteUrl('site/login'));

            try {
                if ($eauth->authenticate()) {
//					var_dump($eauth->getIsAuthenticated(), $eauth->getAttributes()); exit;

                    $identity = UbiModule::getInstance()->findLocalUserByEAuth($eauth);
                    Yii::$app->getUser()->login($identity);

                    $gUser = GlobalUsers::findOne(["id"=>$identity->getId()]);

                    if (empty($gUser->email) && empty($gUser->unconfirmedEmail))
                        !empty($callback)?$callback("success-no-mail"):$eauth->redirect();
                    else
                        !empty($callback)?$callback("success"):$eauth->redirect();

                }
                else {
                    // close popup window and redirect to cancelUrl
                    !empty($callback)?$callback("fail"):$eauth->cancel();
                }
            }
            catch (ErrorException $e) {
                // save error to show it later
                Yii::$app->getSession()->setFlash('error', 'EAuthException: '.$e->getMessage());
                Yii::getLogger()->log($e->getMessage()."\n".$e->getTraceAsString(),Logger::LEVEL_ERROR);

                // close popup window and redirect to cancelUrl
//				$eauth->cancel();
                !empty($callback)?$callback("error"):$eauth->redirect($eauth->getCancelUrl());
            }
        }
    }

}
