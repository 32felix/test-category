<?php

namespace app\controllers;

use app\models\form\ParamsForm;
use app\models\Params;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class ParamsController extends Controller
{

    public $layout = "//admin";

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['admin', "update", "delete", "restore"],
                        'allow' => true,
                        'roles' => ['admin'],
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

    public function actionAdmin()
    {
        $model = Params::find()->where(['deleted' => 0]);
        $count = $model->count();
        $dataProvider = new ActiveDataProvider ([
            'query' => $model,
            'totalCount' => $count,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);

        $model1 = Params::find()->where(['deleted' => 1]);
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

    public function actionUpdate($id=null)
    {
        if (!$id)
            return $this->redirect('/admin/params');

        $model =  Params::findOne(['id' => $id]);
        if (!$model)
            return $this->redirect('/admin/params');

        $form = new ParamsForm();

        $form->id = $id;
        $form->key = $model->key;
        $form->value = $model->value;

        if ($form->load(Yii::$app->request->post()) && $form->update())
                return $this->redirect('/admin/params');

        return $this->render('create', [
            'model' => $form,
            'main' => 'Редагувати',
        ]);
    }

    public function actionDelete($id=null)
    {
        if (!$id)
            return $this->redirect('/admin/params');

        Params::updateAll(['deleted' => 1], ['id' => $id]);

        return $this->redirect('/admin/params');
    }

    public function actionRestore($id=null)
    {
        if (!$id)
            return $this->redirect('/admin/params');

        Params::updateAll(['deleted' => 0], ['id' => $id]);

        return $this->redirect('/admin/params');
    }

}
