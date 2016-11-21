<?php

namespace app\model\form;

use app\models\Users;
use yii\base\Model;
use Yii;

class ChangePassForm extends Model
{

	public $oldPass;
    public $newPass;
    public $newPassRepeat;

    public $userId;


	public function rules()
    {
		return
        [
            [['oldPass'], 'checkOldPass'],
            [['newPass'], 'string', 'min'=>6, 'max'=>100],
            [['newPassRepeat'], 'safe'],
            [['newPassRepeat', 'newPass'], 'required'],
            [['newPass'], 'compare', 'compareAttribute'=>'newPassRepeat'],
        ];
	}

    function checkOldPass()
    {
        if (Yii::$app->user->isGuest)
            $this->addError("Користувач не залогінений");

        /**
         * @var $user Users
         */
        $user = Users::find()->where(['id'=>$this->userId])->one();

        if (!$user)
            $this->addError("Корстувача не знайдено");

        if (!$user->validatePassword($this->oldPass))
            $this->addError("Некоректний пароль");
    }

    public function attributeLabels()
    {
        return array(
            'oldPass'=>'Старий пароль',
            'newPass'=>'Новий пароль',
            'newPassRepeat'=>'Повторіть новий пароль',
        );
    }

    public function saveNewPassword()
    {
        /**
         * @var $user Users
         */
        $user = Users::find()->where(['id'=>$this->userId])->one();
        $user->update();
    }

}

