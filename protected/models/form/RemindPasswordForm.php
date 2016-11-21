<?php

namespace app\models\form;

use app\models\Users;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 *
 */
class RemindPasswordForm extends Model
{
    public $password;
    public $rewritePassword;
    
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
//            [['userPhone'], 'required'],
            [['password', 'rewritePassword'], 'string'],
            [['password', 'rewritePassword'], 'validatePasswords'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'password' => 'Новий пароль',
            'rewritePassword' => 'Повторіть новий пароль',
        ];
    }

    public function validatePasswords()
    {
        if ($this->password != $this->rewritePassword)
        {
            $this->addError('rewritePassword', 'Паролі не співпадають!');
            return false;
        }
    }
    
}
