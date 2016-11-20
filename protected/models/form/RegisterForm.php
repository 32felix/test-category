<?php

namespace app\models\form;

use app\models\Users;
use Yii;
use yii\base\Model;
use yii\base\Security;

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
            $this->verifyMessage = "Введено неправильний код з картинки!";
            return false;
        }

        $this->getUser();
        if ($this->_user)
        {
            $this->verifyMessage = "Користувач з таким e-mail або телефоном вже зареєстрований!";
            return false;
        }
        elseif ($this->password != $this->passwordRewrite)
        {
            $this->verifyMessage = "Поля 'Пароль' та 'Повторення пароля' не співпадають!";
            return false;
        }


        $this->_user = new Users();
        $this->_user->telephone = $this->telephone;
        $this->_user->password = md5($this->password);
        $this->_user->email = $this->email;
        $this->_user->name = $this->name;

        $assess = new Security();
        $this->_user->accessCode = $assess->generateRandomString(32);

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
            $this->_user = Users::find()->where(['email' => $this->email])->orWhere(['telephone' => $this->telephone])->one();

        return $this->_user;
    }


}
