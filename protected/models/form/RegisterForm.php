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
class RegisterForm extends Model
{
    public $telephone = '+38';
    public $email;
    public $password;
    public $passwordRewrite;
    public $name;
    public $verifyCode;
    public $verifyMessage;

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
            ['telephone', 'string', 'max' => 13, 'min' => 13],
            [['email'], 'email'],
            [['email', 'verifyCode', 'telephone', 'password', 'passwordRewrite', 'name'], 'required', 'message' => 'Дане поле потрібно заповнити!'],
            [['password', 'passwordRewrite'], 'string', 'max' => 200, 'min' => 6],
            ['name', 'string', 'max' => 50, 'min' => 4],
            [['password', 'name'], 'string', 'max' => 50],
            ['verifyCode', 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'verifyCode' => 'Введіть код з картинки',
            'telephone' => 'Номер телефону',
            'password' => 'Пароль',
            'passwordRewrite' => 'Повторення пароля',
            'name' => 'Назва користувача',
            'email' => 'E-mail',
        ];
    }



    public function confirm()
    {
        $this->validate();

        if ($this->verifyCode !== Yii::$app->cache->get('captcha-register'))
        {
            $this->verifyMessage = "Введено неправильний код з картинки";
            return false;
        }

        $this->getUser();
        if ($this->_user || $this->password !== $this->passwordRewrite)
            return false;

        $this->_user = new Users();
        $this->_user->telephone = $this->telephone;
        $this->_user->password = md5($this->password);
        $this->_user->email = $this->email;
        $this->_user->name = $this->name;

        if ($this->_user->save())
        {
            Yii::$app->session->set('restorePass', $this->password);
            return true;
        }
        return false;
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
