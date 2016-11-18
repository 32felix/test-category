<?php

namespace tit\ubi\controllers;

use app\components\Controller;
use app\modules\ubi\model\form\RegisterForm;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\Point;
use tit\ubi\model\form\AddEmailForm;
use tit\ubi\model\UsersSocialAccounts;
use tit\ubi\UbiAsset;
use tit\ubi\utils\FileAPI;
use nodge\eauth\ErrorException;
use tit\ubi\model\UserAvatar;
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

class LoginController extends Controller
{

    public function actionPopup()
    {
        $model = new LoginForm();
        $model->scenario = LoginForm::SCENARIO_LOGIN;

        $body = $this->renderAjax('loginPopup',
        [
            'model' => $model,
            'ab_testname' => "default",
            'ab_auth_option'=> "popup",
            'use_popup' => 1,
            'research' => $_REQUEST["research"] ?? false,
        ]);

        if (!empty($_REQUEST["preload"])) {
            header("Content-Type: application/javascript");
            header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 3600));
            header_remove("Pragma");
            header_remove("Cache-Control");
            echo "RPopup.preload('" . Url::toRoute("/ubi/login/popup") . "'," . json_encode($body) . ")";
        } else
            return $body;
    }

    public function actionLoginByForm()
    {
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {

            if(isset($_SESSION['ab_lastAuthTest'])) {
                Yii::$app->ab->markConversion($_SESSION['ab_lastAuthTest']);
            }

            header("Content-Type: application/json");
            $res = [];
            $res["status"] = "ok";
            echo json_encode($res);
            die;
        } else {
            $view = 'loginForm'.issetdef($_REQUEST["v"], "", ["","2"]);
            return $this->renderAjax($view, [
                'model' => $model,
                'error' => 'Невірний логін або пароль'
            ]);
        }
    }

    public function actionJson()
    {
        $model = new LoginForm();
        $model->load($_REQUEST, '');

        $model->validate();

        $res=[];

        if ($model->hasErrors())
            $res["errors"] = $model->getErrors();
        else
        {
            \Yii::$app->getUser()->login($model->getUser());
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

    public function actionLoginByEauth()
    {
        if (isset($_REQUEST["android"]))
            Yii::$app->session->set("android-id", $_REQUEST["android"]);

        if (isset($_REQUEST["extra"]))
            Yii::$app->session->set("oauth-extra", $_REQUEST["extra"]);

        $androidId = Yii::$app->session->get("android-id", false);

        $callback = function ($result) use ($androidId){
            if ($androidId) {
                if ($result == "success") {
                    $name = "android-" . $androidId;
                    $row = Yii::$app->db->createCommand("SELECT * FROM UserTokens WHERE user=:user AND name=:name", [
                        "user" => Yii::$app->user->id,
                        "name" => $name,
                    ])->queryOne();

                    if (empty($row)) {
                        $token = base64_encode(hash("sha256", uniqid() . uniqid() . uniqid() . uniqid() . uniqid(), true));
                        $token = str_replace("=", "", $token);
                        Yii::$app->db->createCommand("INSERT INTO UserTokens(user,name,token) VALUES(:user,:name,:token)", [
                            "user" => Yii::$app->user->id,
                            "name" => $name,
                            "token" => $token,
                        ])->execute();
                        $tokenId = Yii::$app->db->getLastInsertID();
                    } else {
                        $token = $row["token"];
                        $tokenId = $row["id"];
                    }
//                header("Location: );
//                    $this->redirect("therespo://local.therespo.com?id=".Yii::$app->user->id . "&tid=" . $tokenId . "&token=" . urlencode($token));
                    echo json_encode([
                        "id" => Yii::$app->user->id,
                        "tid" => $tokenId,
                        "token" => urlencode($token)
                    ]);
                } else {
//                    $this->redirect("therespo://local.therespo.com?error=".$result);
                    echo json_encode(["error" => $result]);
                }
                die;
            } else {
                $result = json_encode($result);
                echo "<script>
                    if (window.opener) {
                        window.opener.postMessage({oauth:{status:$result}}, '*');
                        window.close();
                        if (window.opener.oauth_result)
                            window.opener.oauth_result($result);
                    }
                    else
                        window.location='" . Url::toRoute(["/"]) . "';
                    </script>";
            }
        };

        $serviceName = Yii::$app->getRequest()->getQueryParam('service');
        if (isset($serviceName)) {
            /** @var $eauth \nodge\eauth\ServiceBase */
            $eauth = Yii::$app->get('eauth')->getIdentity($serviceName);

//            if (Yii::$app->session->get("android-id", false)) {
                $url = Url::toRoute(["login-by-eauth", "service" => $serviceName], true);
                $eauth->setRedirectUrl($url);
                $_GET['redirect_uri'] = $url;
//            } else
//                $eauth->setRedirectUrl(Yii::$app->getUser()->getReturnUrl());

            $eauth->setCancelUrl(Yii::$app->getUrlManager()->createAbsoluteUrl('site/login'));

            try {
                if ($eauth->authenticate()) {
                    $extra = Yii::$app->session->get("oauth-extra", []);
                    $identity = UbiModule::getInstance()->findLocalUserByEAuth($eauth, $extra);

                    Yii::$app->session->remove("android-id");
                    Yii::$app->session->remove("oauth-extra");

                    Yii::$app->getUser()->login($identity);
                    $gUser = GlobalUsers::findOne(["id" => $identity->getId()]);

                    if(isset($_SESSION['ab_lastAuthTest'])) {
                        Yii::$app->ab->markConversion($_SESSION['ab_lastAuthTest']);
                    }

                    if (empty($gUser->email) && empty($gUser->unconfirmedEmail))
                        !empty($callback) ? $callback("success-no-mail") : $eauth->redirect();
                    else
                        !empty($callback) ? $callback("success") : $eauth->redirect();

                } else {
                    // close popup window and redirect to cancelUrl
                    !empty($callback) ? $callback("fail") : $eauth->cancel();
                }
            } catch (ErrorException $e) {
                // save error to show it later
                Yii::$app->session->remove("android-id");
                Yii::$app->session->remove("oauth-extra");
                Yii::$app->getSession()->setFlash('error', 'EAuthException: ' . $e->getMessage());
                Yii::getLogger()->log($e->getMessage() . "\n" . $e->getTraceAsString(), Logger::LEVEL_ERROR);

                // close popup window and redirect to cancelUrl
//				$eauth->cancel();
                !empty($callback) ? $callback("error") : $eauth->redirect($eauth->getCancelUrl());
            }
        }
    }


    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
