<?php

namespace tit\ubi\controllers;

use app\components\Controller;
use app\models\Users;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\Point;
use tit\ubi\model\UsersSocialAccounts;
use tit\ubi\UbiAsset;
use tit\ubi\utils\FileAPI;
use nodge\eauth\ErrorException;
use tit\ubi\model\Avatars;
use tit\ubi\model\form\ChangePassForm;
use tit\ubi\model\GlobalUsers;
use tit\ubi\model\form\LoginForm;
use tit\ubi\UbiModule;
use Yii;
use yii\captcha\CaptchaAction;
use yii\helpers\BaseUrl;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\imagine\Image;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\View;

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
//    public function actionLoginPopup()
//    {
//        $model = new LoginForm();
//        $model->scenario = LoginForm::SCENARIO_LOGIN;
//
//        if (!empty($_REQUEST["preload"]))
//        {
//            header("Content-Type: application/javascript");
//            header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 3600));
//            header_remove("Pragma");
//            header_remove("Cache-Control");
//            $view = $this->renderAjax('loginPopup', ['model'=>$model]);
//            echo "RPopup.preload('".Url::toRoute("/ubi/user/login-popup")."',".json_encode($view).")";
//        }
//        else
//            return $this->renderAjax('loginPopup', ['model'=>$model]);
//    }

    /**
     * @return string
     */
    public function actionRegisterPopup()
    {
        $js = '$(function() {
                    $("body").on("click", ".symbols-refresh", function(event){
                        event.preventDefault();
                         $("img[id$=-verifycode-image]").click();
                    });
                })';
        \Yii::$app->getView()->registerJs($js, View::POS_END);

        $gUser = new Users();
        if($gUser->load(\Yii::$app->request->post()))
        {
            if ($gUser->save())
            {
                \Yii::$app->mailer->compose('/mail/email_confirmation', ['gUser' => $gUser])
                    ->setFrom('admin@pizza-time.org')
                    //->setFrom($mailBot)
                    ->setTo($gUser->email)
                    //->setTo("2yonchi@gmail.com")
                    ->send();
                return $this->render('registrationSuccess',array('email' => $gUser->email));
            }
            else {
                return $this->render('registerForm', array('model' => $gUser));
            }
        }
        return $this->renderAjax('registerPopup', ['model'=>$gUser]);
    }

    public function beforeAction($action)
    {
        if ($action->id=="logout")
            $this->enableCsrfValidation = false;
        return Controller::beforeAction($action);
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

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

                    // special redirect with closing popup window
                    $eauth->redirect();
                }
                else {
                    // close popup window and redirect to cancelUrl
                    $eauth->cancel();
                }
            }
            catch (ErrorException $e) {
                // save error to show it later
                Yii::$app->getSession()->setFlash('error', 'EAuthException: '.$e->getMessage());

                // close popup window and redirect to cancelUrl
//				$eauth->cancel();
                $eauth->redirect($eauth->getCancelUrl());
            }
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {

            return $this->goBack();
        }
        else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionRegister()
    {
        $gUser = new GlobalUsers();
        $gUser->scenario = GlobalUsers::SCENARIO_REGISTRATION;
        if($gUser->load(Yii::$app->request->post()) )
        {
            $ref = GlobalUsers::getReferrer();
            if (!empty($ref))
                $gUser->referrer = $ref->id;

            if ($gUser->save())
            {

                $mailBot = ((isset(Yii::$app->params['adminEmail']) && !empty(Yii::$app->params['adminEmail']))?Yii::$app->params['adminEmail']:'bot@santa4.me');
                Yii::$app->mailer->compose('/mail/email_confirmation', ['gUser' => $gUser])
                ->setFrom('bot@santa4.me')
                //->setFrom($mailBot)
                ->setTo($gUser->email)
                //->setTo("2yonchi@gmail.com")
                ->send();

                return $this->renderAjax('registrationSuccess',array('email' => $gUser->email));
            }
            else {
                if(\Yii::$app->request->isAjax)
                    return $this->renderAjax('registerForm', array('model' => $gUser));

                return $this->render('registerForm', array('model' => $gUser));

            }
        }
        return $this->render('registerForm',array('model'=>$gUser));
    }

    public function actionActivate($accessCode=null,$user=null)
    {
        /**
         * @var $model GlobalUsers
         */
        if(isset($_POST['GlobalUsers']['user']) && !empty($_POST['GlobalUsers']['user']))
            $user = $_POST['GlobalUsers']['user'];
        if(isset($_POST['GlobalUsers']['accessCode']) && !empty($_POST['GlobalUsers']['accessCode']))
            $accessCode = $_POST['GlobalUsers']['accessCode'];
        if(isset($user) && !empty($user))
            $model = GlobalUsers::find()->where(['id'=>$user])->one();
        if(!isset($model) || empty($model))
        {
            return $this->render('activateNoUser');
        }
        if($model->accessCode!=$accessCode || $model->accessCode == null)
        {
            return $this->render('activateWrongAccessCode');
        }
        if ($model->active==0)
        {
            $model->active=1;
            $model->save();
        }
        $model->ensureLocalUser();

        $model->scenario = GlobalUsers::SCENARIO_ACTIVATE;
        if($model->load(Yii::$app->request->post()))
        {
            $model->accessCode = null;
            if($model->save())
                echo $this->renderAjax("activateActivated",array());
            else
            {
                $model->password = null;
                echo $this->renderAjax('activateForm',array(
                    'model'=>$model,
                ));
            }
            Yii::$app->end();
        }

        $model->password = null;
        return $this->render('activate',
        [
            'model'=>$model,
        ]);
    }

    public function actionChangePass()
    {
        if (Yii::$app->user->isGuest)
            throw new NotFoundHttpException('404: User is not logged in');
        $user = GlobalUsers::find()->where(['id'=>Yii::$app->user->getId()])->one();
        if(isset($user->email) && !empty($user->email) && $user->email != NULL)
        {
            $successMessage="";
            $model=new ChangePassForm();
            $scenario = $user->password==null?ChangePassForm::SCENARIO_SET:ChangePassForm::SCENARIO_CHANGE;
            $model->scenario = $scenario;
            $model->userId = $user->id;
            if (isset($_POST['ChangePassForm']))
            {
                $model->load(Yii::$app->request->post());
                if ($model->validate())
                {
                    $model->saveNewPassword();
                    $successMessage="Пароль успешно изменен.";

                    $model=new ChangePassForm();
                    $model->scenario = ChangePassForm::SCENARIO_CHANGE;
                    $model->userId=$user->id;
                }
                $this->renderPartial('changePasswordForm',array(
                    'model'=>$model,
                    'successMessage'=>$successMessage,
                ),false,true);
                Yii::$app->end();
            }
            return $this->render('changePassword',array(
                'model'=>$model,
                'successMessage'=>$successMessage,
            ));
        }
        else
        {
            $this->redirect('add-email');
        }
    }


    public function actionAddEmail()
    {
        if(Yii::$app->user->isGuest)
            throw new NotFoundHttpException('404: User is not logged in');
        $model = GlobalUsers::find()->where(['id'=>Yii::$app->user->getId()])->one();
        if(isset($model->email) && !empty($model->email) && $model->email != NULL)
        {
            throw new NotFoundHttpException('404: User already have email');
        }
        $model->scenario = GlobalUsers::SCENARIO_ADD_EMAIL;
        if(isset($_POST['GlobalUsers']['email']) && !empty($_POST['GlobalUsers']['email']))
        {
            $model->email = $_POST['GlobalUsers']['email'];
            $userEmail = GlobalUsers::find()->where(['email'=>$_POST['GlobalUsers']['email']])->one();
            if(isset($userEmail))
            {
                $massage = 'This email is already used';
                $this->render('emailForm',['model'=>$model,'massage'=>$massage]);
            }
            if($model->save())
            {
                Yii::$app->mailer->compose('email_confirmation', ['model' => $model])
                    ->setFrom('bot@santa4.me')
//                    ->setTo($model->email)
                ->setTo("2yonchi@gmail.com")
                ->send();
            }
        }
        return $this->render('addEmail',
        [
            'model'=>$model
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionTest()
    {
        return $this->render('test');
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
    public function actionAvatar($id,$time,$w,$h,$ext)
    {
        if (!in_array("{$w}x{$h}", UbiModule::getInstance()->params['allowedAvatarSizes']))
            throw new HttpException(404, "Image size not found");


        $modelUser= GlobalUsers::findOne(['id'=>$id]);
        if (empty($modelUser))
            throw new HttpException(404, "User not found");

        /**/
        $time2 = dechex(strtotime($modelUser->timeAddAvatar));
//        $time2 = dechex(strtotime(Yii::$app->ubiCache->get('timeAddAvatar',$id)));


        $imagesPath = Yii::$app->basePath.'/../media/user/'. $id;

        if (!is_dir($imagesPath))
            mkdir($imagesPath, 0777, true);
        $nameAvatar="{$time2}_{$w}x{$h}.jpeg";
        $name = $imagesPath . '/' . $nameAvatar;
        /**/
        $modelImage=Avatars::findOne(['id'=>$id]);
        if (!file_exists($name))
        {
            if(empty($modelImage))
            {
                $usas=UsersSocialAccounts::findAll(["userId"=>$id]);
                $img=null;
                foreach ($usas as $usa) {
                    $d=json_decode($usa->data, true);
                    if (!empty($d["userPhoto"]))
                    {

                        $b=new CurlBrowser();
                        $req=$b->request()
                            ->url($d["userPhoto"])
                            ->skipCertValidation(true)
                            ->followLocation(true)
                            ->request();

//                        $img = Image::getImagine()->load($req->responseBody)->thumbnail(new Box($w, $h),'outbound')->save($name);
                        $img = Image::getImagine()->load($req->responseBody);

                        $sz=$img->getSize();
                        if ($sz->getWidth()>1000)
                            $sz->scale(1000.0/$sz->getWidth());
                        if ($sz->getHeight()>1000)
                            $sz->scale(1000.0/$sz->getHeight());

                        $img->resize($sz)->save($name);


                        $model = new Avatars();
                        $model->id = $id;
                        $model->image = file_get_contents($name);
                        $model->save();

                        $modelUser->timeAddAvatar = date("Y-m-d H:i:s");
                        $modelUser->save(false);

                        $img->thumbnail(new Box($w, $h),'outbound')->save($name);

                    }
                }
                if($img==null)
                    $img = Image::frame(dirname(__FILE__)."/../assets/images/default.png")->thumbnail(new Box($w, $h),'outbound')->save($name);
            }
            else
            {
                $img = Image::getImagine()->load($modelImage->image)->thumbnail(new Box($w, $h),'outbound')->save($name);
            }
            if ($time2!=$time)
                $this->redirect(["avatar","id"=>$id,"time"=>$time2,"w"=>$w,"h"=>$h,"ext"=>$ext]);
            else
            {
                header("Content-type: image/jpeg");
                echo file_get_contents($name);
            }
        }
        else
            $this->redirect(["avatar","id"=>$id,"time"=>$time2,"w"=>$w,"h"=>$h,"ext"=>$ext]);
    }

    /**
     * @return string
     * @throws \yii\base\ExitException
     */
    public function actionRestorePasswordRequest()
    {
        $model = new GlobalUsers();
        $model->scenario = GlobalUsers::SCENARIO_RESTORE_PASSWORD_REQUEST;

        if (isset($_POST['GlobalUsers']) && ! empty($_POST['GlobalUsers']) )
        {
            if ($model->load($_POST) && $model->validate())
            {
                $user = GlobalUsers::findOne(['email'=>$model->email]);
                $user->accessCode = $model->accessCode;

                if ($user->save())
                {
                    // Send message to user email
                    $sendMsg = Yii::$app->mailer->compose('/mail/email_return_pass', ['model'=>$user])
                        ->setSubject('Востановление пароля в проекте'.Url::to('/', true))
                        ->setFrom('service@ukrautoportal.com')
                        ->setTo($model->email);
                    if ($sendMsg->send())
                    {
                        return $this->renderPartial('restorePasswordRequestOk');
                    }
                }
                echo "Some error occurred";
            } else {
                return $this->renderAjax('restorePasswordRequestForm', ['model'=>$model]);
            }
        } else {
            return $this->render('restorePasswordRequest', ['model'=>$model]);
        }
    }
}
