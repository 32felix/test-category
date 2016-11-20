<?php

namespace tit\ubi\model\form;

use app\models\User;
use tit\ubi\model\GlobalUsers;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model
{
    public $login;
    public $password;
    public $rememberMe = true;

    private $_user = false;
    private $_globalUser = false;

    const SCENARIO_LOGIN = 'login';

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['login', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],

            [['login'], 'email', 'on' => [self::SCENARIO_LOGIN]],
            [['login'], 'unique', 'on' => [self::SCENARIO_LOGIN]],
        ];
    }


    public function attributeLabels()
    {
        return [
            'login' => Yii::t('app','email'),
            'password' =>Yii::t('app','password'),
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     */
    public function validatePassword()
    {
        if (!$this->hasErrors()) {
            $gUser = $this->getGlobalUser();

            if (!$gUser || !$gUser->validatePassword($this->password)) {
                $this->addError('password', 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        } else {
            return false;
        }
    }

    /**
     * @return \tit\ubi\model\GlobalUsers
     */
    public function getGlobalUser()
    {
        if ($this->_globalUser=== false) {
            $this->_globalUser = GlobalUsers::find()->where(["email"=>strtolower($this->login)])->one();// findByLogin($this->login);
        }
        return $this->_globalUser;
    }


    /**
     * Finds user by [[login]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $gUser = $this->getGlobalUser();
            if ($gUser==null)
                return null;
            $this->_user = $gUser->ensureLocalUser();
        }
        return $this->_user;
    }
}