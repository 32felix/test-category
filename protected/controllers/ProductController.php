<?php

namespace app\controllers;

use app\models\form\ProductsForm;
use app\models\Products;
use app\models\ProductsPrices;
use app\models\ProductsSize;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\filters\VerbFilter;

class ProductController extends Controller
{

    public $layout = "//admin";

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['admin', "update", "delete", "create", "view", "restore", "delete-price"],
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
        $model = Products::findAll(['type' => $type, 'deleted' => 0]);

        foreach ($model as $item)
        {
            /**@var Products $item */
            $dataForm[$i] = new ProductsForm();

            $dataForm[$i]->id = $item->id;
            $dataForm[$i]->name = $item->name;
            $dataForm[$i]->ingredients = $item->ingredients;
            $dataForm[$i]->type = $type;
            $dataForm[$i]->imageId = $item->imageId;

            $sql = Yii::$app->db->createCommand("SELECT S.*, P.price
                FROM ProductsPrices P
                LEFT JOIN ProductsSize S ON S.id=P.sizeId
                WHERE S.type LIKE '".$type."' AND P.productId=".$item->id."
                ORDER BY S.size")->queryAll();

            $dataForm[$i]->size = ArrayHelper::getColumn($sql, 'size');
            $dataForm[$i]->price = ArrayHelper::getColumn($sql, 'price');
            $dataForm[$i]->countMen = ArrayHelper::getColumn($sql, 'countMen');
            foreach ($dataForm[$i]->size as $keys=>$items)
            {
                $dataForm[$i]->size[$keys] = $items;
            }

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

        $model = Products::find()->where(['type' => $type, 'deleted' => 0]);

        $count = $model->count();

        $dataProvider = new ActiveDataProvider ([
            'query' => $model,
            'totalCount' => $count,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);

        $model1 = Products::find()->where(['type' => $type, 'deleted' => 1]);

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

        $model = Products::find()->where(['type' => $type, 'id' => $id])->one();
        $sql = "SELECT S.*, P.price
                FROM ProductsPrices P
                LEFT JOIN ProductsSize S ON S.id=P.sizeId
                WHERE S.type LIKE '".$type."' AND P.productId=".$id."
                ORDER BY S.size";
        $count = Yii::$app->db->createCommand($sql)->queryAll();
        if (!$model && !count($count))
            return $this->redirect('/admin/'.$type);

        $dataProviderPrice = new SqlDataProvider ([
            'sql' => $sql,
        ]);


        return $this->render('view', [
            'dataProviderPrice' => $dataProviderPrice,
            'type' => $type,
            'model' => $model,
        ]);
    }

    public function actionCreate($type=null)
    {
        if (!$type)
            return $this->goBack();

        $form = new ProductsForm();
        $form->type = $type;

        if ($form->load(Yii::$app->request->post()))
        {
            $form->size = Yii::$app->request->post('ProductsForm')['size'];
            $form->price = Yii::$app->request->post('ProductsForm')['price'];
            $form->countMen = Yii::$app->request->post('ProductsForm')['countMen'];

            if ($form->create())
                return $this->redirect('/admin/'.$type);
        }



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

        $model =  Products::findOne(['type' => $type, 'id' => $id]);
        if (!$model)
            return $this->redirect('/admin/'.$type);

        $form = new ProductsForm();

        $form->id = $id;
        $form->name = $model->name;
        $form->ingredients = $model->ingredients;
        $form->type = $type;
        $form->imageId = $model->imageId;

        $sql = "SELECT S.*, P.price
                FROM ProductsPrices P
                LEFT JOIN ProductsSize S ON S.id=P.sizeId
                WHERE S.type LIKE '".$type."' AND P.productId=".$id."
                ORDER BY S.size";
        $sql = Yii::$app->db->createCommand($sql)->queryAll();

        $form->size = ArrayHelper::getColumn($sql, 'size');
        $form->price = ArrayHelper::getColumn($sql, 'price');
        $form->countMen = ArrayHelper::getColumn($sql, 'countMen');

        if ($form->load(Yii::$app->request->post()))
        {
            $form->size = Yii::$app->request->post('ProductsForm')['size'];
            $form->price = Yii::$app->request->post('ProductsForm')['price'];
            $form->countMen = Yii::$app->request->post('ProductsForm')['countMen'];

            if ($form->update())
                return $this->redirect('/admin/'.$type);
        }



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

        Products::updateAll(['deleted' => 1], ['type' => $type, 'id' => $id]);

        return $this->redirect('/admin/'.$type);
    }

    public function actionRestore($type=null, $id=null)
    {
        if (!$type || !$id)
            return $this->goBack();

        Products::updateAll(['deleted' => 0], ['type' => $type, 'id' => $id]);

        return $this->redirect('/admin/'.$type);
    }

    public function actionDeletePrice($size=null, $productId=null, $type=null)
    {
        if ($size && $productId && $type)
        {
            $sizeId = ProductsSize::findOne(['size' =>$size, 'type' => $type])->id;
            ProductsPrices::deleteAll(['sizeId' => $sizeId, 'productId' => $productId]);
        }

        if (!$type)
            return $this->goBack();

        return $this->redirect('/update/'.$type.'/'.$productId);
    }


}
