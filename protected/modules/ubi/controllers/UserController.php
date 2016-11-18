<?php

namespace tit\ubi\controllers;

use app\components\Controller;
use Imagine\Image\Box;
use tit\ubi\model\form\ChangePassForm;
use tit\ubi\model\UsersSocialAccounts;
use tit\ubi\utils\FileAPI;
use nodge\eauth\ErrorException;
use tit\ubi\model\UserAvatar;
use tit\ubi\model\GlobalUsers;
use tit\ubi\UbiModule;
use tit\ubi\widgets\ChangePassFormWidget;
use tit\utils\CurlBrowser;
use Yii;
use yii\captcha\CaptchaAction;
use yii\imagine\Image;
use yii\web\HttpException;

class UserController extends Controller
{

    public function actions()
    {
        return
            [
                'captcha' => [
                    'class' => CaptchaAction::class,
                    'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                    'backColor' => 0XFFFFFF,
                    'height' => 34
                ],
            ];
    }

    public function actionGetAvatar()
    {
        if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST') {
            $files = FileAPI::getFiles();
            $userId = Yii::$app->user->getId();
            $model = UserAvatar::findOne(['id' => $userId]);
            $modelUser = GlobalUsers::findOne(['id' => $userId]);
            if (!isset($model) || empty($model)) {
                if (isset($modelUser) && !empty($modelUser)) {
                    $model = new UserAvatar();
                    $model->id = $userId;
                } else {
                    \Yii::$app->getErrorHandler();
                    Yii::$app->end();
                }
            }
            $model->image = file_get_contents($files['filedata']['tmp_name']);
            if ($model->save()) {
                $modelUser->timeAddAvatar = date("Y-m-d H:i:s", time());
                $modelUser->save();
                $jsonp = isset($_REQUEST['callback']) ? trim($_REQUEST['callback']) : null;
                FileAPI::makeResponse(array(
                    'status' => FileAPI::OK
                , 'statusText' => 'OK'
                , 'body' => array('count' => sizeof($files)
                    ), $jsonp));
                exit;
            }
        }
        \Yii::$app->getErrorHandler();
    }

    public function beforeAction($action)
    {
        if ($action->id == "logout")
            $this->enableCsrfValidation = false;
        return Controller::beforeAction($action);
    }

    public function actionChangePassword()
    {
        $user = GlobalUsers::findOne(\Yii::$app->user->id);
        $model = new ChangePassForm();
        $model->scenario = $user->password==null?ChangePassForm::SCENARIO_SET:ChangePassForm::SCENARIO_CHANGE;
        $model->load(Yii::$app->request->post());

        $message="";
        if ($model->validate()) {
            $model->saveNewPassword();
            $message = 'Пароль змінено успішно';

            $user = GlobalUsers::findOne(\Yii::$app->user->id);
            $model = new ChangePassForm();
            $model->scenario = $user->password==null?ChangePassForm::SCENARIO_SET:ChangePassForm::SCENARIO_CHANGE;
        }

        return $this->renderAjax("@app/modules/ubi/widgets/views/changePassFormWidget", ['model'=>$model, 'message' => $message]);
    }

    /**
     * @param $id
     * @param $time
     * @param $w
     * @param $h
     * @param $ext
     * @throws ErrorException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionAvatar($id, $time, $w, $h, $ext)
    {
        if (!in_array("{$w}x{$h}", UbiModule::getInstance()->params['allowedAvatarSizes']))
            throw new HttpException(404, "Image size not found");


        $modelUser = GlobalUsers::findOne(['id' => $id]);
        if (empty($modelUser))
            throw new HttpException(404, "User not found");

        /**/
        $time2 = dechex(strtotime($modelUser->timeAddAvatar));
//        $time2 = dechex(strtotime(Yii::$app->ubiCache->get('timeAddAvatar',$id)));


        $imagesPath = Yii::$app->basePath . '/../media/user/' . $id;

        if (!is_dir($imagesPath))
            mkdir($imagesPath, 0777, true);

        $nameAvatar = "{$time2}_{$w}x{$h}.jpeg";
        $name = $imagesPath . '/' . $nameAvatar;
        /**/
        $modelImage = UserAvatar::findOne(['id' => $id]);
        if (!file_exists($name)) {
            if (empty($modelImage)) {
                $usas = UsersSocialAccounts::findAll(["userId" => $id]);
                $img = null;
                foreach ($usas as $usa) {
                    $d = json_decode($usa->data, true);
                    if (!empty($d["userPhoto"])) {

                        $b = new CurlBrowser();
                        $req = $b->request()
                            ->url($d["userPhoto"])
                            ->skipCertValidation(true)
                            ->followLocation(true)
                            ->request();

//                        $img = Image::getImagine()->load($req->responseBody)->thumbnail(new Box($w, $h),'outbound')->save($name);
                        $img = Image::getImagine()->load($req->responseBody);

                        $sz = $img->getSize();
                        if ($sz->getWidth() > 1000)
                            $sz->scale(1000.0 / $sz->getWidth());
                        if ($sz->getHeight() > 1000)
                            $sz->scale(1000.0 / $sz->getHeight());

                        $img->resize($sz)->save($name);


                        $model = new UserAvatar();
                        $model->id = $id;
                        $model->image = file_get_contents($name);
                        $model->save();

                        $modelUser->timeAddAvatar = date("Y-m-d H:i:s");
                        $modelUser->save(false);

                        $img->thumbnail(new Box($w, $h), 'outbound')->save($name);
                    }
                }
                if ($img == null)
                    $img = Image::frame(dirname(__FILE__) . "/../assets/images/default.png")->thumbnail(new Box($w, $h), 'outbound')->save($name);
            } else {
                $img = Image::getImagine()->load($modelImage->image)->thumbnail(new Box($w, $h), 'outbound')->save($name);
            }
            if ($time2 != $time)
                $this->redirect(["avatar", "id" => $id, "time" => $time2, "w" => $w, "h" => $h, "ext" => $ext]);
            else {
                header("Content-type: image/jpeg");
                echo file_get_contents($name);
            }
        } else
            $this->redirect(["avatar", "id" => $id, "time" => $time2, "w" => $w, "h" => $h, "ext" => $ext]);
    }


    public function actionResetAccessCache() {
        Yii::$app->authManager->reset();
        $this->redirect($_SERVER["HTTP_REFERER"] ?? \Yii::$app->homeUrl);
    }
}
