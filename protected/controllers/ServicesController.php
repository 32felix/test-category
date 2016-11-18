<?php

namespace app\controllers;

use app\models\form\ServicesForm;
use app\models\Services;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class ServicesController extends Controller
{

    public $layout = "//admin";

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['admin', "update", "delete", "create", "view", "restore"],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                    [
                        'actions' => ['index'],
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


    public function actionIndex($type=null)
    {
        $this->layout = "//main";
        if (!$type)
            return $this->goHome();

        $dataForm = [];
        $i = 0;
        $model = Services::findAll(['type' => $type, 'deleted' => 0]);

        foreach ($model as $item)
        {
            /**@var Services $item */
            $dataForm[$i] = new ServicesForm();

            $dataForm[$i]->id = $item->id;
            $dataForm[$i]->name = $item->name;
            $dataForm[$i]->description = $item->description;
            $dataForm[$i]->type = $type;
            $dataForm[$i]->imageId = $item->imageId;

            $i++;
        }

        return $this->render('index', [
            'model' => $dataForm,
            'type' => $type,
        ]);
    }


    public function actionAdmin($type=null)
    {
        if (!$type)
            return $this->goHome();

        $model = Services::find()->where(['type' => $type, 'deleted' => 0]);

        $count = $model->count();

        $dataProvider = new ActiveDataProvider ([
            'query' => $model,
            'totalCount' => $count,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);

        $model1 = Services::find()->where(['type' => $type, 'deleted' => 1]);

        $count1 = $model1->count();

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
            'type' => $type,
        ]);
    }

    public function actionView($type=null, $id=null)
    {
        if (!$type || !$id)
            return $this->goBack();

        $model = Services::find()->where(['type' => $type, 'id' => $id])->one();
        if (!$model)
            return $this->redirect('/admin/'.$type);

        return $this->render('view', [
            'type' => $type,
            'model' => $model,
        ]);
    }

    public function actionCreate($type=null)
    {
        if (!$type)
            return $this->goBack();

        $form = new ServicesForm();
        $form->type = $type;

        if ($form->load(Yii::$app->request->post()) && $form->create())
                return $this->redirect('/admin/'.$type);



        return $this->render('create', [
            'model' => $form,
            'main' => 'Зберегти',
            'type' => $type,
        ]);
    }

    public function actionUpdate($type=null, $id=null)
    {
        if (!$type || !$id)
            return $this->goBack();

        $model =  Services::findOne(['type' => $type, 'id' => $id]);
        if (!$model)
            return $this->redirect('/admin/'.$type);

        $form = new ServicesForm();

        $form->id = $id;
        $form->name = $model->name;
        $form->description = $model->description;
        $form->type = $type;
        $form->imageId = $model->imageId;

        if ($form->load(Yii::$app->request->post()) && $form->update())
                return $this->redirect('/admin/'.$type);

        return $this->render('create', [
            'model' => $form,
            'main' => 'Редагувати',
            'type' => $type,
        ]);
    }

    public function actionDelete($type=null, $id=null)
    {
        if (!$type || !$id)
            return $this->goBack();

        Services::updateAll(['deleted' => 1], ['type' => $type, 'id' => $id]);

        return $this->redirect('/admin/'.$type);
    }

    public function actionRestore($type=null, $id=null)
    {
        if (!$type || !$id)
            return $this->goBack();

        Services::updateAll(['deleted' => 0], ['type' => $type, 'id' => $id]);

        return $this->redirect('/admin/'.$type);
    }

}
