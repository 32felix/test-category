<?php

namespace tit\ubi\model\form;

use app\models\User;
use tit\ubi\model\GlobalUsers;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class AddEmailForm extends Model
{
    public $email;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            ['email', 'email'],
            ['email', 'required'],
        ];
    }


    public function attributeLabels()
    {
        return [
            'email' => \Yii::t('app','Email'),
        ];
    }

}