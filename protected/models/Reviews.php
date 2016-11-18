<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "Reviews".
 *
 * @property integer $id
 * @property string $userName
 * @property string $review
 * @property integer $userId
 * @property string $ip
 * @property integer $imageId
 * @property string $timeCreate
 * @property string $timeUpdate
 * @property integer $deleted
 */
class Reviews extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Reviews';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userName', 'review', 'ip'], 'string'],
            [['userId', 'imageId', 'deleted'], 'integer'],
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
            'userName' => 'Назва користувача',
            'review' => 'Відгук',
            'userId' => 'ID користувача',
            'ip' => 'IP',
            'imageId' => 'ID зображення',
            'timeCreate' => 'Час створення',
            'timeUpdate' => 'Час редагування',
            'deleted' => 'Видалений',
        ];
    }

}
