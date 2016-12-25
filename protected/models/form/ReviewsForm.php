<?php

namespace app\models\form;

use app\models\Images;
use app\models\Reviews;
use Yii;
use yii\base\Model;


class ReviewsForm extends Model
{
    public $id;
    public $userName;
    public $review;
    public $userId;
    public $imageId;
    public $deleted;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userName'], 'string', 'max' => 250],
            [['review'], 'string', 'max' => 20000],
            [['userName', 'review'], 'request'],
            [['id', 'userId', 'deleted'], 'integer'],
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
            'review' => 'Відгук',
            'userId' => 'ID користувача',
            'imageId' => 'Встановити картинку користувача',
            'deleted' => 'Видалений',
        ];
    }

    public function create()
    {
        $product = new Reviews();

        $product->userName = $this->userName;
        $product->review = $this->review;
        $product->userId = $this->userId;
        $product->imageId = $this->imageId;

        if ($product->save())
        {
            $images = Images::findOne($this->imageId);
            if ($images->ownerId != $this->id)
            {
                $images->ownerId = $this->id;
                $images->ownerType = 'review';
                $images->save();
            }
            return true;
        }

        return false;
    }

    public function update()
    {

        $product = Reviews::findOne(['id' => $this->id]);

        if (!$product)
            return false;

        $product->userName = $this->userName;
        $product->review = $this->review;
        $product->userId = $this->userId;
        $product->imageId = $this->imageId;

        if ($product->save())
        {
            $images = Images::findOne($this->imageId);
            if ($images->ownerId != $this->id)
            {
                $images->ownerId = $this->id;
                $images->ownerType = 'review';
                $images->save();
            }
            return true;
        }

        return false;
    }

}
