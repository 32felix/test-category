<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Category".
 *
 * @property integer $id
 * @property string $name
 * @property string $link
 * @property integer $parentId
 * @property integer $deleted
 * @property string $timeCreate
 * @property string $timeUpdate
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parentId', 'deleted'], 'integer'],
            [['timeCreate', 'timeUpdate'], 'safe'],
            [['name'], 'string', 'max' => 200],
            [['link'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'link' => 'Link',
            'parentId' => 'Parent ID',
            'deleted' => 'Deleted',
            'timeCreate' => 'Time Create',
            'timeUpdate' => 'Time Update',
        ];
    }
}
