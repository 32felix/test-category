<?php

namespace tit\ubi\controllers;

use app\components\Controller;
use app\models\User;
use app\modules\ubi\model\UserPhone;
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

class PhoneController extends Controller
{
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
        $phone = $_REQUEST["phone"] ?? "";

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
            $uPhone = UserPhone::findOne(["user" => \Yii::$app->user->id, "phone" => $phone]);

            if (!$uPhone)
                $res["error"] = "Телефон $phone не асоційований з користувачем";
        }

        if (empty($res) && !$uPhone->timeVerified) {
            $res["error"] = "Адреса $phone не підтверджена. Для підтвердження, перейдіть за посиланням в листі.";
        }

        if (empty($res)) {
            $user->phone = $uPhone->phone;
            $user->save(false, ["phone"]);
            $res["success"] = "Ваш основний телефон тепер $user->phone.";
        }
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        die;
    }

    public function actionDelete()
    {
        /**
         * @var GlobalUsers $user
         */
        $phone = $_REQUEST["phone"] ?? "";

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
            $uPhone = UserPhone::findOne(["user" => \Yii::$app->user->id, "phone" => $phone]);

            if (!$uPhone)
                $res["error"] = "Номер $phone не асоційований з користувачем";
        }

        if (empty($res)) {
            $res["success"] = "Номер $uPhone->phone успішно видалено.";
            $uPhone->delete();
        }
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        die;
    }
    public function actionResendConfirmation()
    {
        $phone = $_REQUEST["phone"] ?? "";

        $res = [];

        $user = GlobalUsers::findOne(\Yii::$app->user->id);
        if (!$user)
            $res["error"] = "Ви не авторизовані. Для цієї операції увійдіть в свій профіль.";

        if (empty($res)) {
            $uPhone = UserPhone::findOne(["user" => \Yii::$app->user->id, "phone" => $phone]);

            if (!$uPhone)
                $res["error"] = "Адреса $phone не асоційована з користувачем";
        }

        if (empty($res)) {
            if (!$uPhone->timeVerified) {
                $uPhone->sendAddPhone();
                $uPhone->save(false, ["timeVerificationSent"]);
                $res["success"] = "Лист підтвердження успішно вислано повторно.";
            } else {
                $res["success"] = "Електронну адресу уже підтверджено. Додаткового підтвердження не потрібно.";
            }
        }
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        die;
    }
    public function actionConfirm($user, $token)
    {
        /**
         * @var $user GlobalUsers
         */
        $user = GlobalUsers::findOne($user);

        $userPhone = UserPhone::findOne(["user"=>$user, "phone"=>$_REQUEST["phone"]??"-"]);

        if (!$user)
            throw new HttpException(404, "Користувача не знайдено");
        if (!$userPhone || $userPhone->genConfirmPhoneToken() != $token)
            throw new HttpException(403, "Посилання застаріле. Ви можете спробувати відіслати лист активації повторно в особистому кабінеті.");

        if (!$userPhone->timeVerified) {
            $userPhone->timeVerified = date("Y-m-d H:i:s");
            $userPhone->save(false, ["timeVerified"]);
        }
        return $this->render("confirm", ["user" => $user, "userPhone"=>$userPhone]);
    }

    public function actionUpdate()
    {
        /**
         * @var $model UserPhone
         */
        $gUser = GlobalUsers::findOne(\Yii::$app->user->id);
        $model = new UserPhone();

        $msg = "";

        if ($model->load($_REQUEST) && $model->validate()) {
            if (!$gUser)
                $model->addError("phone", "Ви не авторизовані. Для цієї операції увійдіть в свій профіль.");

            try {
                if (!$gUser->validatePassword($_REQUEST["pass"]??""))
                    $model->addError("phone", "Помилка під час перевірки пароля.");
            }
            catch (InvalidParamException $e)
            {
                $model->addError("phone", "Помилка під час перевірки пароля.");
            }

            
            $confirmedPhonesExists = UserPhone::find()
                ->where(["phone"=>$model->phone])
                ->andWhere("timeVerified IS NOT NULL")
                ->exists();

            $uPhone = UserPhone::find()
                ->where(["phone"=>$model->phone, "user"=>$gUser->id])
                ->one();

            if ($uPhone)
                $model->addError("phone", "Цей телефон вже знаходиться у списку ваших телефонів");

            if (!$model->hasErrors() && $confirmedPhonesExists)
                $model->addError("phone", "Цей телефон вже використовується іншим користувачем");

            if (!$model->hasErrors()) {
                $model->user = $gUser->id;
                $model->save(false, ["user", "phone"]);
                if($gUser->email){
                    $model->sendAddPhone();
                    $model->save(false, ["timeVerificationSent"]);
                }
                if (!$gUser->phone) {
                    $gUser->phone = $model->phone;
                    $gUser->save(false, ["phone"]);
                }
            }
            $msg = $model->hasErrors() ? "" : "Ok";
        }

        return $this->renderAjax("changePhone", ["model" => $model, "msg" => $msg, "gUser"=>$gUser]);
    }
}
