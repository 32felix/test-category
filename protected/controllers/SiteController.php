<?php

namespace app\controllers;

use app\components\utils\ImageUtils;
use app\model\form\ChangePassForm;
use app\models\form\RegisterForm;
use app\models\form\RemindPasswordForm;
use app\models\Params;
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
        $text = null;
        $text = Params::findOne(['key' => 'mainPage', 'deleted' => 0]);

        $shares = "SELECT I.*
                   FROM Services S
                   LEFT JOIN Imeges I ON I.id=S.imageId
                   WHERE S.deleted=0 AND S.type='share' AND I.id IS NOT NULL
                   ORDER BY timeUpdate DESC
                   LIMIT 5";

        $shares = Yii::$app->db->createCommand($shares)->queryAll();

        if ($text) {
            $text = $text->value;
        }

        return $this->render('index', [
            'text' => $text,
            'shares' => $shares,
        ]);
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
        $text = null;
        $text = Params::findOne(['key' => 'contact', 'deleted' => 0]);

        if ($text) {
            $text = $text->value;
        }

        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
            'text' => $text,
        ]);
    }

    public function actionDelivery()
    {
        $text = null;
        $text = Params::findOne(['key' => 'delivery', 'deleted' => 0]);

        if ($text) {
            $text = $text->value;
        }

        return $this->render('delivery', [
            'text' => $text,
        ]);
    }
}
