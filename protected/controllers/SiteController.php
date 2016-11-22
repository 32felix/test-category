<?php

namespace app\controllers;

use app\components\utils\ImageUtils;
use app\model\form\ChangePassForm;
use app\models\form\RegisterForm;
use app\models\form\RemindPasswordForm;
use app\models\Users;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\form\LoginForm;
use app\models\form\ContactForm;
use yii\web\NotFoundHttpException;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
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
