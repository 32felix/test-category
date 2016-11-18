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
    public $email;
    public $name;
    public $password;
    public $verifyCode;

    /**
     * @var Users $_user
     */
    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
//            [['userPhone'], 'required'],
            ['email', 'validateEmail'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'E-mail',
        ];
    }

    public function validateEmail()
    {
        if (!$this->hasErrors())
        {
            $user = $this->getUser();

            if (!$user)
            {
                $this->addError('email', 'Неправильний e-mail адрес');
                return false;
            }
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return Users|null
     */
    public function getUser()
    {
        if ($this->_user === false)
            $this->_user = Users::findOne(['email' => $this->email]);

        return $this->_user;
    }
}
