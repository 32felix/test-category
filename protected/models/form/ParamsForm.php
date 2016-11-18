<?php

namespace app\models\form;

use app\models\Params;
use Yii;
use yii\base\Model;


class ParamsForm extends Model
{
    public $id;
    public $key;
    public $value;
    public $deleted;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key', 'value'], 'string'],
            [['deleted'], 'integer'],
        ];
    }




    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '',
            'key' => 'Сторінка',
            'value' => 'Значення',
            'deleted' => 'Видалений',
        ];
    }

    public function update()
    {

        $param = Params::findOne(['id' => $this->id]);

        if (!$param)
            return false;

        $param->key = $this->key;
        $param->value = $this->value;


        if ($param->save())
            return true;

        return false;
    }

}
