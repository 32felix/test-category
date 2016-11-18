<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "Services".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $imageId
 * @property string $type
 * @property string $timeCreate
 * @property string $timeUpdate
 * @property integer $deleted
 */
class Services extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Services';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description','type'], 'string'],
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
            'name' => 'Назва послуги',
            'description' => 'Опис послуги',
            'imageId' => 'ID картинки',
            'type' => 'Тип послуги',
            'timeCreate' => 'Час створення',
            'timeUpdate' => 'Час редагування',
            'deleted' => 'Видалений',
        ];
    }

}
