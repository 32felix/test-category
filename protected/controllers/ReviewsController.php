<?php

namespace app\controllers;

use app\models\form\ReviewsForm;
use app\models\Reviews;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class ReviewsController extends Controller
{

    public $layout = "//admin";

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['admin', "update", "delete", "view", "restore"],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                    [
                        'actions' => ['index', 'create'],
                        'allow' => true,
                    ],
                ],
                'denyCallback' => function () {
                    return $this->goHome();
                }
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }


    public function actionIndex()
    {
        $this->layout = "//main";

        $model = Reviews::findAll(['deleted' => 0]);

        return $this->render('index', [
            'model' => $model,
        ]);
    }


    public function actionAdmin()
    {
        $model = Reviews::find()->where(['deleted' => 0]);
        $count = $model->count();
        $dataProvider = new ActiveDataProvider ([
            'query' => $model,
            'totalCount' => $count,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);

        $model1 = Reviews::find()->where(['deleted' => 1]);
        $count1 = $model->count();
        $dataProvider1 = new ActiveDataProvider ([
            'query' => $model1,
            'totalCount' => $count1,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);

        return $this->render('admin', [
            'dataProvider' => $dataProvider,
            'dataProvider1' => $dataProvider1,
        ]);
    }

    public function actionView($id=null)
    {
        if (!$id)
            return $this->redirect('/admin/reviews');

        $model = Reviews::find()->where(['id' => $id])->one();
        if (!$model)
            return $this->redirect('/admin/reviews');

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionCreate()
    {

        $form = new ReviewsForm();

        if ($form->load(Yii::$app->request->post()) && $form->create())
                return $this->redirect('/admin/reviews');


        return $this->render('create', [
            'model' => $form,
            'main' => 'Зберегти',
        ]);
    }

    public function actionUpdate($id=null)
    {
        if (!$id)
            return $this->redirect('/admin/reviews');

        $model =  Reviews::findOne(['id' => $id]);
        if (!$model)
            return $this->redirect('/admin/reviews');

        $form = new ReviewsForm();

        $form->id = $id;
        $form->userName = $model->userName;
        $form->review = $model->review;
        $form->userId = $model->userId;

        if ($form->load(Yii::$app->request->post()) && $form->update())
                return $this->redirect('/admin/reviews');

        return $this->render('create', [
            'model' => $form,
            'main' => 'Редагувати',
        ]);
    }

    public function actionDelete($id=null)
    {
        if (!$id)
            return $this->redirect('/admin/reviews');

        Reviews::updateAll(['deleted' => 1], ['id' => $id]);

        return $this->redirect('/admin/reviews');
    }

    public function actionRestore($id=null)
    {
        if (!$id)
            return $this->redirect('/admin/reviews');

        Reviews::updateAll(['deleted' => 0], ['id' => $id]);

        return $this->redirect('/admin/reviews');
    }

}
