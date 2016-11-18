<?php

namespace app\models\form;

use app\models\Images;
use app\models\Products;
use app\models\ProductsPrices;
use app\models\ProductsSize;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;


class ProductsForm extends Model
{
    public $id;
    public $name;
    public $ingredients;
    public $type;
    public $imageId;
    public $deleted;

    public $size = [];
    public $countMen = [];
    public $price = [];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 200],
            [['ingredients'], 'string', 'max' => 5000],
            [['ingredients'], 'string'],
            [['id', 'imageId', 'deleted'], 'integer'],
        ];
    }




    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '',
            'name' => 'Назва продукту',
            'ingredients' => 'Інгредієнти продукту',
            'type' => 'Тип продукту',
            'imageId' => 'Встановити картинку продукту',
            'timeCreate' => 'Час створення',
            'timeUpdate' => 'Час редагування',
            'deleted' => 'Видалений',
        ];
    }

    public function create()
    {
        $i = 0;

        $product = new Products();

        $product->name = $this->name;
        $product->ingredients = $this->ingredients;
        $product->type = $this->type;
        $product->imageId = $this->imageId;

        if ($product->save())
        {
            foreach ($this->price as $item)
            {
                if ($item !== "")
                {
                    $size = ProductsSize::findOne(['size' => $this->size[$i], 'type' => $this->type]);

                    if (!$size)
                    {
                        $size = new ProductsSize();
                        $size->size = $this->size[$i];
                        $size->type = $this->type;
                        $size->countMen = $this->countMen[$i];
                        $size->save();
                    }

                    $price = new ProductsPrices();
                    $price->productId = $product->id;
                    $price->sizeId = $size->id;
                    $price->price = $this->price[$i];
                    $price->save();
                }
                $i++;
            }
            $images = Images::findOne($this->imageId);
            if ($images->productId != $this->id)
            {
                $images->productId = $this->id;
                $images->productType = $this->type;
                $images->save();
            }

            return true;
        }
        return false;
    }

    public function update()
    {
        $i = 0;

        $product = Products::findOne(['id' => $this->id, 'type' => $this->type]);

        if (!$product)
            return false;

        $product->name = $this->name;
        $product->ingredients = $this->ingredients;
        $product->imageId = $this->imageId;

        if ($product->save())
        {
            foreach ($this->price as $item)
            {
                if ($item !== "")
                {
                    $size = ProductsSize::findOne(['size' => $this->size[$i], 'type' => $this->type]);

                    if (!$size)
                    {
                        $size = new ProductsSize();
                        $size->size = $this->size[$i];
                        $size->type = $this->type;
                        $size->countMen = $this->countMen[$i];
                        $size->save();
                    }

                    $price = ProductsPrices::findOne(['productId' => $product->id, 'sizeId' => $size->id]);

                    if (!$price)
                    {
                        $price = new ProductsPrices();
                        $price->productId = $product->id;
                        $price->sizeId = $size->id;
                        $price->price = $this->price[$i];
                        $price->save();
                    }
                    elseif ($price->price != $this->price[$i])
                    {
                        $price->price = $this->price[$i];
                        $price->save();
                    }
                }
                $i++;
            }

            $images = Images::findOne($this->imageId);
            if ($images->productId != $this->id)
            {
                $images->productId = $this->id;
                $images->productType = $this->type;
                $images->save();
            }

            return true;
        }
        return false;
    }

}
