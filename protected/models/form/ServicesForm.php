<?php

namespace app\models\form;

use app\models\Images;
use app\models\Services;
use Yii;
use yii\base\Model;


class ServicesForm extends Model
{
    public $id;
    public $name;
    public $description;
    public $imageId;
    public $type;
    public $deleted;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 200],
            [['name', 'description', 'type'], 'string'],
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
            'name' => 'Назва послуги',
            'description' => 'Опис послуги',
            'type' => 'Тип послуги',
            'imageId' => 'Встановити картинку послуги',
            'deleted' => 'Видалена',
        ];
    }

    public function create()
    {
        $product = new Services();

        $product->name = $this->name;
        $product->description = $this->description;
        $product->imageId = $this->imageId;
        $product->type = $this->type;

        if ($product->save())
        {
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

        $product = Services::findOne(['id' => $this->id]);

        if (!$product)
            return false;

        $product->name = $this->name;
        $product->description = $this->description;
        $product->imageId = $this->imageId;
        $product->type = $this->type;


        if ($product->save())
        {
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
