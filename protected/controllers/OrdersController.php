<?php

namespace app\controllers;

use app\components\utils\GlobalsUtils;
use app\models\form\OrdersForm;
use app\models\OrderProducts;
use app\models\Orders;
use app\models\ProductsPrices;
use app\models\ProductsSize;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\filters\VerbFilter;

class OrdersController extends Controller
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
                        'actions' => ['create', "add-price-id", "delete-price-id", "add-count", "remove-count"],
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

    public function actionAdmin()
    {
        $model = Orders::find()->where(['deleted' => 0]);

        $count = $model->count();

        $dataProvider = new ActiveDataProvider ([
            'query' => $model,
            'totalCount' => $count,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);

        $model1 = Orders::find()->where(['deleted' => 1]);

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
        ]);
    }

    public function actionView($id=null)
    {
        if (!$id)
            return $this->redirect('/admin/orders');

        $model = Orders::find()->where(['id' => $id])->one();
        $sql = "SELECT OP.count, PP.price, P.*, S.size
                FROM ProductsPrices PP
                LEFT JOIN ProductsSize S ON S.id=PP.sizeId
                LEFT JOIN Products P ON P.id=PP.productId
                LEFT JOIN OrderProducts OP ON OP.productPriceId=PP.id
                WHERE OP.orderId=".$id."
                ORDER BY OP.id";
        $count = Yii::$app->db->createCommand($sql)->queryAll();
        if (!$model && !count($count))
            return $this->redirect('/admin/orders');

        $dataProviderPrice = new SqlDataProvider ([
            'sql' => $sql,
        ]);


        return $this->render('view', [
            'dataProviderPrice' => $dataProviderPrice,
            'model' => $model,
        ]);
    }

    public function actionCreate()
    {
        $form = new OrdersForm();
        $user = Yii::$app->user->identity;
        if ($form->load(Yii::$app->request->post()) && $form->create())
                return $this->redirect('/admin/orders');

        return $this->render('create', [
            'model' => $form,
            'user' => $user,
            'main' => 'Зберегти',
        ]);
    }

    public function actionUpdate($id=null)
    {
        if (!$id)
            return $this->redirect('/admin/orders');

        $model =  Orders::findOne(['id' => $id]);
        if (!$model)
            return $this->redirect('/admin/orders');

        $form = new OrdersForm();

        $form->id = $id;
        $form->userName = $model->userName;
        $form->addressId = $model->addressId;
        $form->status = $model->status;
        $form->telephone = $model->telephone;
        $form->email = $model->email;

        $sql = OrderProducts::findAll(['orderId' => $id]);

        $form->productPriceId = ArrayHelper::getColumn($sql, 'productPriceId');
        $form->count = ArrayHelper::getColumn($sql, 'count');

        if ($form->load(Yii::$app->request->post()))
        {
            $form->productPriceId = Yii::$app->request->post('OrdersForm')['productPriceId'];
            $form->count = Yii::$app->request->post('OrdersForm')['count'];

            if ($form->update())
                return $this->redirect('/admin/orders');
        }



        return $this->render('update', [
            'model' => $form,
            'main' => 'Редагувати',
        ]);
    }

    public function actionDelete($id=null)
    {
        if (!$id)
            return $this->redirect('/admin/orders');

        Orders::updateAll(['deleted' => 1], ['id' => $id]);

        return $this->redirect('/admin/orders');
    }

    public function actionRestore($id=null)
    {
        if (!$id)
            return $this->redirect('/admin/orders');

        Orders::updateAll(['deleted' => 0], ['id' => $id]);

        return $this->redirect('/admin/orders');
    }

    public function actionDeletePriceId($orderId=null, $productPriceId=null)
    {
        if(!Yii::$app->request->isAjax)
        {
            if ($orderId && $productPriceId)
                OrderProducts::deleteAll(['orderId' => $orderId, 'productPriceId' => $productPriceId]);

            return $this->redirect('/update/orders/'.$orderId);
        }

        $res = [];
        if(empty($_POST['orderId']) || empty($_POST['productPriceId']))
            $res["error"]="Не вказані дані";

        if (empty($res["error"]))
        {
            $model = OrderProducts::findAll(['orderId' => $_POST['orderId']]);
            $order = OrderProducts::findAll(['orderId' => $_POST['orderId'], 'productPriceId' => $_POST['productPriceId']]);
            if (empty($model) || empty($order))
                $res["error"] = "Не знайдене замовлення";

            if (empty($res["error"]))
            {
                OrderProducts::deleteAll(['orderId' => $_POST['orderId'], 'productPriceId' => $_POST['productPriceId']]);
                if (count($model) < 2)
                    Orders::deleteAll(['id' => $_POST['orderId']]);

                $res["status"] = "ok";
            }
        }

        header("Content-type: application/json");
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        Yii::$app->end();
    }

    public function actionAddPriceId()
    {
        if(!Yii::$app->request->isAjax)
            return false;

        $res = [];
        $res['error'] ="";
        $user = Yii::$app->user->identity;
        if(empty($_POST['productId']) || empty($_POST['sizes']))
            $res["error"]="Не вказані дані";

        $size = ProductsSize::findOne(['size' => $_POST['sizes']]);
        if (!$size)
            $res['error'] = "Неправильный размер";

        $productPrice = ProductsPrices::findOne(['sizeId' => $size->id, 'productId' => $_POST['productId']]);
        if (!$productPrice)
            $res['error'] = "Нет такого размера";

        if (empty($res["error"]))
        {
            $res['price'] = $productPrice->price;

            $ip = OrdersForm::getIp();
            $userAgent = GlobalsUtils::issetdef($_SERVER["HTTP_USER_AGENT"]);
            $model = Orders::find()->where('ip="'.$ip.'" AND userAgent="'.$userAgent.'" AND status IS NULL')->one();
            if (!$model)
            {
                $model = new Orders();
                if ($user)
                {
                    $model->userId = $user->id;
                    $model->userName = $user->name;
                    $model->telephone = $user->telephone;
                    $model->email = $user->email;
                }
                $model->ip = $ip;
                $model->userAgent = $userAgent;
                $model->save();

            }

            if (empty($res["error"]))
            {
                $order = OrderProducts::findOne(['orderId' => $model->id, 'productPriceId' => $productPrice->id]);
                if (empty($order))
                {
                    $order = new OrderProducts();
                    $order->orderId = $model->id;
                    $order->productPriceId = $productPrice->id;
                    $order->count = 1;
                }
                else
                {
                    $order->count = $order->count + 1;
                }
                $order->save();

                $res["status"] = "ok";
            }
        }

        header("Content-type: application/json");
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        Yii::$app->end();
    }

    public function actionAddCount()
    {
        if(!Yii::$app->request->isAjax)
            return false;

        $res = [];
        if(empty($_POST['orderId']) || empty($_POST['productPriceId']))
            $res["error"]="Не вказані дані";

        if (empty($res["error"]))
        {
            $model = Orders::findOne(['orderId' => $_POST['orderId']]);
            $order = OrderProducts::findOne(['orderId' => $_POST['orderId'], 'productPriceId' => $_POST['productPriceId']]);
            if (empty($model) || empty($order))
                $res["error"] = "Не знайдене замовлення";

            if (empty($res["error"]))
            {
                $order->count = $order->count + 1;
                $order->save();

                $res["status"] = "ok";
            }
        }

        header("Content-type: application/json");
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        Yii::$app->end();
    }

    public function actionRemoveCount()
    {
        if(!Yii::$app->request->isAjax)
            return false;

        $res = [];
        if(empty($_POST['orderId']) || empty($_POST['productPriceId']))
            $res["error"]="Не вказані дані";

        if (empty($res["error"]))
        {
            $model = Orders::findOne(['orderId' => $_POST['orderId']]);
            $order = OrderProducts::findOne(['orderId' => $_POST['orderId'], 'productPriceId' => $_POST['productPriceId']]);
            if (empty($model) || empty($order))
                $res["error"] = "Не знайдене замовлення";

            if (empty($res["error"]))
            {
                $order->count = $order->count - 1;
                $order->save();

                $res["status"] = "ok";
            }
        }

        header("Content-type: application/json");
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        Yii::$app->end();
    }


}
