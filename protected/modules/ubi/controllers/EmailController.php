<?php

namespace tit\ubi\controllers;

use app\components\Controller;
use app\models\User;
use app\modules\ubi\model\UserMail;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\Point;
use tit\ubi\model\form\AddEmailForm;
use tit\ubi\model\UsersSocialAccounts;
use tit\ubi\UbiAsset;
use tit\ubi\utils\FileAPI;
use nodge\eauth\ErrorException;
use tit\ubi\model\UserAvatar;
use tit\ubi\model\form\ChangePassForm;
use tit\ubi\model\GlobalUsers;
use tit\ubi\model\form\LoginForm;
use tit\ubi\UbiModule;
use tit\utils\CurlBrowser;
use Yii;
use yii\base\InvalidParamException;
use yii\captcha\CaptchaAction;
use yii\helpers\BaseUrl;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\imagine\Image;
use yii\log\Logger;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\View;

class EmailController extends Controller
{

    public function actionAddEmailPopup()
    {
        $model = new AddEmailForm();

        return $this->renderAjax('addEmailPopup', ['model' => $model]);
    }

    public function actionAddEmail()
    {
        $model = new AddEmailForm();

        $model->load(Yii::$app->request->post()) && $model->validate();

        $model->email = strtolower($model->email);

        if (!$model->hasErrors() && \Yii::$app->user->isGuest) {
            $model->addError("email", "Ви не авторизовані. Спробуйте ввійти ще раз.");
        }

        /**
         * @var GlobalUsers $gUser
         */
        $gUser = GlobalUsers::findOne(["id" => \Yii::$app->user->getId()]);
        if (!$model->hasErrors()) {
            if (!empty($gUser->email))
                $model->addError("email", "Для користувача №$gUser->id вже встановлений email $gUser->email");
        }

        if (!$model->hasErrors()) {
            $more = GlobalUsers::findOne(["email" => $model->email]);
            $mailsExists = UserMail::findVerified($model->email)->exists();
            if (!empty($more) || $mailsExists)
                $model->addError("email", "Вказана адреса вже використовується. Для доступу до неї спробуйте відновити пароль.");
        }

        if (!$model->hasErrors()) {
            $mail = new UserMail();
            $mail->user = $gUser->id;
            $mail->address = $model->email;
            $mail->save(false, ["user","address"]);
            $mail->sendAddMail();
            $mail->save(false, ["timeVerificationSent"]);

            $gUser->email = $model->email;
            $gUser->save(false, ["email"]);

            header("Content-Type: application/json");
            $res = [];
            $res["status"] = "ok";
            echo json_encode($res);
            die;
        }

        return $this->renderAjax('addEmailForm', [
            'model' => $model,
        ]);
    }

    public function actionMakePrimary()
    {
        /**
         * @var GlobalUsers $user
         */
        $address = $_REQUEST["email"] ?? "";

        $res = [];

        $user = GlobalUsers::findOne(\Yii::$app->user->id);
        if (!$user)
            $res["error"] = "Ви не авторизовані. Для цієї операції увійдіть в свій профіль.";

        try {
            if (!$user->validatePassword($_REQUEST["pass"]??""))
                $res["error"] = "Помилка під час перевірки пароля.";
        }
        catch (InvalidParamException $e)
        {
            $res["error"] = "Помилка під час перевірки пароля.";
        }

        if (empty($res)) {
            $uMail = UserMail::findOne(["user" => \Yii::$app->user->id, "address" => $address]);

            if (!$uMail)
                $res["error"] = "Адреса $address не асоційована з користувачем";
        }

        if (empty($res) && !$uMail->timeVerified) {
            $res["error"] = "Адреса $address не підтверджена. Для підтвердження, перейдіть за посиланням в листі.";
        }

        if (empty($res)) {
            $user->email = $uMail->address;
            $user->save(false, ["email"]);
            $res["success"] = "Ваша основна адреса тепер $user->email.";
        }
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        die;
    }

    public function actionDelete()
    {
        /**
         * @var GlobalUsers $user
         */
        $address = $_REQUEST["email"] ?? "";

        $res = [];

        $user = GlobalUsers::findOne(\Yii::$app->user->id);
        if (!$user)
            $res["error"] = "Ви не авторизовані. Для цієї операції увійдіть в свій профіль.";

        try {
            if (!$user->validatePassword($_REQUEST["pass"]??""))
                $res["error"] = "Помилка під час перевірки пароля.";
        }
        catch (InvalidParamException $e)
        {
            $res["error"] = "Помилка під час перевірки пароля.";
        }

        if (empty($res)) {
            $uMail = UserMail::findOne(["user" => \Yii::$app->user->id, "address" => $address]);

            if (!$uMail)
                $res["error"] = "Адреса $address не асоційована з користувачем";
        }

        if (empty($res)) {
            $res["success"] = "Адресу $uMail->address успішно видалено.";
            $uMail->delete();
        }
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        die;
    }

    public function actionResendConfirmation()
    {
        /**
         * @var GlobalUsers $user
         */
        $address = $_REQUEST["email"] ?? "";

        $res = [];

        $user = GlobalUsers::findOne(\Yii::$app->user->id);
        if (!$user)
            $res["error"] = "Ви не авторизовані. Для цієї операції увійдіть в свій профіль.";

        if (empty($res)) {
            $uMail = UserMail::findOne(["user" => \Yii::$app->user->id, "address" => $address]);

            if (!$uMail)
                $res["error"] = "Адреса $address не асоційована з користувачем";
        }

        if (empty($res)) {
            if (!$uMail->timeVerified) {
                $uMail->sendAddMail();
                $uMail->save(false, ["timeVerificationSent"]);
                $res["success"] = "Лист підтвердження успішно вислано повторно.";
            } else {
                $res["success"] = "Електронну адресу уже підтверджено. Додаткового підтвердження не потрібно.";
            }
        }
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        die;
    }

    public static function randomPassword()
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    public function actionConfirm($user, $token)
    {
        /**
         * @var $user GlobalUsers
         */
        $user = GlobalUsers::findOne($user);

        $userMail = UserMail::findOne(["user"=>$user, "address"=>$_REQUEST["email"]??"-"]);

        if (!$user)
            throw new HttpException(404, "Користувача не знайдено");

        if (!$userMail || $userMail->genConfirmEmailToken() != $token)
            throw new HttpException(403, "Посилання застаріле. Ви можете спробувати відіслати лист активації повторно в особистому кабінеті.");

        if (!$userMail->timeVerified) {
            $userMail->timeVerified = date("Y-m-d H:i:s");
            $userMail->save(false, ["timeVerified"]);
        }

//        if (!isset($user->password)) {
//            $password = self::randomPassword();
//            $user->password = $user->hashPassword($password);
//            $user->save(false);
//            $user->sendGeneratePasswordEmail($password);
//        }

        return $this->render("confirm", ["user" => $user, "userMail"=>$userMail]);
    }

    public function actionUpdate()
    {
        /**
         * @var $model GlobalUsers
         */
        $gUser = GlobalUsers::findOne(\Yii::$app->user->id);
        $model = new GlobalUsers();

        

        $model->setScenario(GlobalUsers::SCENARIO_CHANGE_EMAIL);

        $msg = "";

        if ($model->load($_REQUEST) && $model->validate()) {

            if (!$gUser)
                $model->addError("email", "Ви не авторизовані. Для цієї операції увійдіть в свій профіль.");

            try {
                if (!$gUser->validatePassword($_REQUEST["pass"]??""))
                    $model->addError("email", "Помилка під час перевірки пароля.");
            }
            catch (InvalidParamException $e)
            {
                $model->addError("email", "Помилка під час перевірки пароля.");
            }
            $model->email = strtolower(trim($model->email));

            $confirmedMailExists = UserMail::find()
                ->where(["address"=>$model->email])
                ->andWhere("timeVerified IS NOT NULL")
                ->exists();

            $uMail = UserMail::find()
                ->where(["address"=>$model->email, "user"=>$gUser->id])
                ->one();

            if ($uMail)
                $model->addError("email", "Ця електронна адреса вже знаходиться у списку ваших адрес");

            if (!$model->hasErrors() && $confirmedMailExists)
                $model->addError("email", "Ця електронна адреса вже використовується іншим користувачем");

            if (!$model->hasErrors()) {
                $mail = new UserMail();
                $mail->user = $gUser->id;
                $mail->address = $model->email;
                $mail->save(false, ["user","address"]);
                $mail->sendAddMail();
                $mail->save(false, ["timeVerificationSent"]);

                if (!$gUser->email) {
                    $gUser->email = $mail->address;
                    $gUser->save(false, ["email"]);
                }

                $model->email = "";
            }

            $msg = $model->hasErrors() ? "" : "Ok";
        }

        return $this->renderAjax("changeEmail", ["model" => $model, "msg" => $msg, "gUser"=>$gUser]);
    }
}
