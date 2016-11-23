<?php

namespace app\modules\ubi\controllers;

use app\components\Controller;
use app\components\utils\ImageUtils;
use app\models\form\RegisterForm;
use app\models\form\RemindPasswordForm;
use app\models\Users;
use Imagine\Image\Point;
use app\modules\ubi\utils\FileAPI;
use nodge\eauth\ErrorException;
use app\model\Avatars;
use app\model\form\ChangePassForm;
use app\modules\ubi\UbiModule;
use Yii;
use yii\captcha\CaptchaAction;
use yii\log\Logger;
use yii\web\NotFoundHttpException;

class UserController extends Controller
{

//    public function behaviors()
//    {
//        return [
//            'access' => [
//                'class' => AccessControl::className(),
//                'rules' => [
//                    [
//                        'actions' => ['login', 'register','captcha'],
//                        'allow' => true,
//                        'roles' => ['?','@'],
//                    ],
//                    [
//                        'actions' => ['logout'],
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ],
//                ],
//            ],
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'logout' => ['post'],
//                ],
//            ],
//        ];
//    }




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
        if( strtoupper($_SERVER['REQUEST_METHOD']) == 'POST' )
        {
            $files  = FileAPI::getFiles();
            $userId = Yii::$app->user->getId();
            $model = Avatars::findOne(['id'=>$userId]);
            $modelUser = Users::findOne(['id'=>$userId]);
            if(!isset($model) || empty($model))
            {
                if(isset($modelUser) && !empty($modelUser))
                {
                    $model = new Avatars();
                    $model->id = $userId;
                }
                else
                {
                    \Yii::$app->getErrorHandler();
                    Yii::$app->end();
                }
            }
            $model->image = file_get_contents($files['filedata']['tmp_name']);
            if($model->save())
            {
                $modelUser->timeAddAvatar = date("Y-m-d H:i:s",time());
                $modelUser->save();
                $jsonp  = isset($_REQUEST['callback']) ? trim($_REQUEST['callback']) : null;
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

    /**
     * @return string
     */
    public function actionLoginByEauth()
    {
        $callback = function($result)
        {
            $result = json_encode($result);
            echo "<script>
                if (window.opener) {
                    window.close();
                    window.opener.oauth_result($result);
                }
                </script>";
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

                    $gUser = Users::findOne(["id"=>$identity->getId()]);

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

    public function actionRegister()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        Yii::$app->cache->set("register", "action");

        $model = new RegisterForm();
        if ($model->load(Yii::$app->request->post()) && $model->confirm()) {
            $gUser = $model->getUser();
            $msg = \Yii::$app->mailer->compose('/mail/email_confirmation', ['gUser' => $gUser])
                ->setFrom('abrakadabra011988@gmail.com')
                ->setTo($gUser->email)
                ->setSubject('Реєстрація на сайті Pizza-Time.org');
            if ($msg->send())
                return $this->render('registrationSuccess', ['email' => $gUser->email]);
        }
        return $this->render('register', [
            'model' => $model,
        ]);
    }

    public function actionActivate($accessCode=null,$user=null)
    {
        /**
         * @var $model Users
         */
        if(isset($user) && !empty($user))
            $model = Users::find()->where(['id'=>$user])->one();
        if(!isset($model) || empty($model))
        {
            return $this->render('activateNoUser');
        }
        if($model->accessCode!=$accessCode || $model->accessCode == null)
        {
            return $this->render('activateWrongAccessCode');
        }
        if ($model->verified==0)
        {
            $model->verified=1;
            $model->save();
        }

        $model->accessCode = null;
        return $this->render("activateActivated",array());
    }

    public function actionChangePass()
    {
        if (Yii::$app->user->isGuest)
            throw new NotFoundHttpException('404: Корстувач не ввійшов на сайт!');
        $user = Users::findOne(['id'=>Yii::$app->user->getId()]);
        if(isset($user->email) && !empty($user->email) && $user->email != NULL)
        {
            $successMessage="";
            $model=new ChangePassForm();
            $model->userId = $user->id;
            if (isset($_POST['ChangePassForm']))
            {
                $model->load(Yii::$app->request->post());
                if ($model->validate())
                {
                    $model->saveNewPassword();
                    $successMessage="Пароль успішно змінений.";

                    $model=new ChangePassForm();
                    $model->userId=$user->id;
                }
                $this->renderPartial('changePasswordForm',array(
                    'model'=>$model,
                    'successMessage'=>$successMessage,
                ));
                Yii::$app->end();
            }
            return $this->render('changePassword',array(
                'model'=>$model,
                'successMessage'=>$successMessage,
            ));
        }

        return $this->redirect('add-email');
    }

    public function actionRestorePasswordRequest()
    {
        $model = new Users();

        if ($model->load(Yii::$app->request->post()) && $model->createAccessCode())
        {
            $user = Users::findOne(['email'=>$model->email]);
            if(!$user)
                return $this->render('activateNoUser');
            $user->accessCode = $model->accessCode;

            if ($user->save())
            {
                // Send message to user email
                $msg = \Yii::$app->mailer->compose('/mail/email_return_pass', ['gUser' => $user])
                    ->setFrom('abrakadabra011988@gmail.com')
                    ->setTo($user->email)
                    ->setSubject('Відновлення паролю на сайті Pizza-Time.org');
                if ($msg->send())
                    return $this->render('restorePasswordRequestOk');
            }
        }

        return $this->render('restorePasswordRequest', ['model'=>$model]);
    }

    public function actionEmailRestore($accessCode=null,$user=null)
    {
        /**
         * @var $model Users
         */
        if(isset($user) && !empty($user))
            $model = Users::find()->where(['id'=>$user])->one();
        if(!isset($model) || empty($model))
        {
            return $this->render('activateNoUser');
        }
        if($model->accessCode!=$accessCode || $model->accessCode == null)
        {
            return $this->render('activateWrongAccessCode');
        }

        $restore = new RemindPasswordForm();

        if($restore->load(Yii::$app->request->post()) && $restore->validate())
        {
            $model->password = md5($restore->password);
            if($model->save())
            {
                return $this->render('restorePasswordOk');
            }
        }

        return $this->render("restorePassword",array('model' => $restore));
    }

    public function actionCaptchaBuild()
    {
        if(!Yii::$app->request->isAjax)
            return false;

        $res = [];
        $res['error'] ="";

        $res["text"] = ImageUtils::captchaBuild();

        header("Content-type: application/json");
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        Yii::$app->end();
    }
}
