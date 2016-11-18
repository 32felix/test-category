<?php

namespace tit\ubi\controllers;

use app\components\Controller;
use app\modules\ubi\model\UserMail;
use tit\ubi\model\GlobalUsers;
use tit\ubi\model\form\LoginForm;
use Yii;
use yii\web\HttpException;

class RestoreController extends Controller
{
    public function actionForgotPassword()
    {
        $model = new LoginForm();

        $model->load($_POST);

        $email = strtolower(trim($model->login));

        /** @var GlobalUsers $user */
        $user = GlobalUsers::findByEmail($email)
            ->one();

        if (!isset($user)) {
            $model->addError("login", 'Кристувача с даним email не знайдено');
        }

        /** @var UserMail $userMail */
        if (!$model->hasErrors()) {
            $userMail = UserMail::findOne(["user" => $user->id, "address" => $email]);

            if (!$userMail)
                $model->addError("login", "Адресу $email не прив'язано до користувача.");
        }

        $msg = null;

        if (!$model->hasErrors()) {
            $userMail->sendPasswordRestoreEmail();
            $msg = 'На '.$model->login.' відправленно лист з інструкціями по відновленню пароля';
        }

//        $model->scenario = LoginForm::SCENARIO_LOGIN;
//        $model->validate();
        $view = 'forgot_pass_form'.issetdef($_REQUEST["v"], "", ["","2"]);

        return $this->renderAjax($view, [
            'model' => $model,
            'msg' => $msg,
        ]);
    }

    public function actionChangePass($user, $token)
    {
        /**
         * @var $model GlobalUsers
         */
        $model = GlobalUsers::findOne($user);

        $userMail = UserMail::findOne(["user"=>$model, "address"=>$_REQUEST["email"]??"-"]);

        if (!$model)
            throw new HttpException(404, "Користувача не знайдено");

        if (!$userMail || $userMail->genRestorePasswordToken($model->password) != $token)
            throw new HttpException(403, "Посилання застаріле. Ви можете спробувати відіслати лист активації повторно в особистому кабінеті.");

        if (!$userMail->timeVerified) {
            $userMail->timeVerified = date("Y-m-d H:i:s");
            $userMail->save(false, ["timeEmailVerified"]);
        }

        $message = "";

        if (\Yii::$app->request->isPost)
        {
            $model->scenario = GlobalUsers::SCENARIO_RESTORE_PASS;
            $model->load(Yii::$app->request->post());

            if ($model->validate()) {
                $model->password = $model->hashPassword($model->newPassword);
                $model->save(false, ["password"]);
                $message = 'Пароль змінено успішно';
            }
        }

        if (\Yii::$app->request->isAjax)
            return $this->renderAjax("changePass", ["user" => $model, 'message' => $message]);
        else
            return $this->render("changePass", ["user" => $model]);
    }

    public function actionRestorePassword()
    {
        $post = Yii::$app->request->post()['GlobalUsers'];

        /** @var GlobalUsers $model */
        $model = GlobalUsers::findOne($post['id']);
        $model->scenario = GlobalUsers::SCENARIO_RESTORE_PASS;
        $model->load(Yii::$app->request->post());

        $message = '';

        if ($model->validate()) {
            $model->password = $model->hashPassword($model->newPassword);
            $model->timeEmailVerificationSent = null;
            $model->save(false);
            $message = 'Пароль змінено успішно';
        }

        return $this->renderAjax("changePass", ["user" => $model, 'message' => $message]);
    }
}
