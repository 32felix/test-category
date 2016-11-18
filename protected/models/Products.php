<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "Products".
 *
 * @property integer $id
 * @property string $name
 * @property string $ingredients
 * @property string $type
 * @property integer $imageId
 * @property string $timeCreate
 * @property string $timeUpdate
 * @property integer $deleted
 */
class Products extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Products';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'ingredients', 'type'], 'string'],
            [['imageId', 'deleted'], 'integer'],
            [['timeCreate', 'timeUpdate'], 'safe'],
        ];
    }




    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Назва продукту',
            'ingredients' => 'Інгредієнти продукту',
            'type' => 'Тип продукту',
            'imageId' => 'ID картинки',
            'timeCreate' => 'Час створення',
            'timeUpdate' => 'Час редагування',
            'deleted' => 'Видалений',
        ];
    }

}
