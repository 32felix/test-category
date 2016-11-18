<?php

namespace app\models\form;

use app\components\utils\GlobalsUtils;
use app\models\OrderProducts;
use app\models\Orders;
use Yii;
use yii\base\Model;


class OrdersForm extends Model
{
    public $id;
    public $userName;
    public $userId;
    public $telephone='+38';
    public $email;
    public $ip;
    public $userAgent;
    public $status;
    public $addressId;
    public $deleted;

    public $productPriceId = [];
    public $count = [];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userName'], 'string', 'max' => 200],
            [['telephone'], 'string', 'max' => 13, 'min' => 13],
            [['email'], 'email'],
            [['id', 'userId', 'addressId', 'deleted'], 'integer'],
//            [['addressId', 'userName', 'telephone'], 'request'],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '',
            'userName' => "Ім'я користувача",
            'userId' => 'ID користувача',
            'userAgent' => 'Агент користувача',
            'telephone' => 'Телефон',
            'email' => 'E-mail',
            'ip' => 'Ip користувача',
            'status' => 'Статус замовлення',
            'addressId' => 'ID адреси',
            'deleted' => 'Видалений',
        ];
    }

    public function create()
    {
        $order = new Orders();

        $order->userName = $this->userName;
        $order->status = 'New';
        $order->telephone = $this->telephone;
        if ($this->email && $this->email != "")
            $order->email = $this->email;
        if ($user = Yii::$app->user->identity)
            $order->userId = $user->id;
        $order->ip = self::getIp();
        $order->userAgent = GlobalsUtils::issetdef($_SERVER["HTTP_USER_AGENT"]);
        $order->addressId = $this->addressId;


        if ($order->save())
            return true;

        return false;
    }

    public function update()
    {
        $order = Orders::findOne(['id' => $this->id]);

        if (!$order)
            return false;

        $order->userName = $this->userName;
        $order->status = 'New';
        $order->addressId = $this->addressId;
        $order->telephone = $this->telephone;
        if ($this->email && $this->email != "")
            $order->email = $this->email;

        if ($order->save())
        {
            foreach ($this->productPriceId as $i=>$item)
            {
                if ($item !== "")
                {
                    $size = OrderProducts::findOne(['productPriceId' => $item, 'orderId' => $order->id]);

                    if (!$size)
                    {
                        $size = new OrderProducts();
                        $size->orderId = $order->id;
                        $size->productPriceId = $item;
                        $size->count = $this->count[$i];
                        $size->save();
                    }
                    elseif ($size->count != $this->count[$i])
                    {
                        $size->count = $this->count[$i];
                        $size->save();
                    }
                }
            }

            return true;
        }
        return false;
    }

    static public function getIp ()
    {
        $res = null;
        if (isset($_SERVER["REMOTE_ADDR"]))
            $res = GlobalsUtils::issetdef($_SERVER["REMOTE_ADDR"]);
        if (isset($_SERVER["HTTP_X_REAL_IP"]))
            $res = GlobalsUtils::issetdef($_SERVER["HTTP_X_REAL_IP"]);
        return $res;
    }

}
