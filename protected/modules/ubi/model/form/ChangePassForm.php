<?php

namespace tit\ubi\model\form;

use tit\ubi\model\GlobalUsers;
use yii\base\Model;
use Yii;

class ChangePassForm extends Model
{
    const SCENARIO_CHANGE = 'change';
    const SCENARIO_SET = 'set';

	public $oldPass;
    public $newPass;
    public $newPassRepeat;

	public function rules()
    {
		return
        [
            [['oldPass'], 'required', 'on'=>self::SCENARIO_CHANGE],
            [['oldPass'], 'checkOldPass', 'on'=>self::SCENARIO_CHANGE],
            [['newPass'], 'string', 'min'=>6, 'max'=>100],
            [['newPassRepeat'], 'safe'],
            [['newPassRepeat', 'newPass'], 'required' ,'on'=>self::SCENARIO_SET],
            [['newPassRepeat', 'newPass'], 'required' ,'on'=>self::SCENARIO_CHANGE],
            [['newPassRepeat'], 'compare', 'compareAttribute'=>'newPass'],
        ];
	}

    function checkOldPass($attribute, $params)
    {
        if (Yii::$app->user->isGuest)
            $this->addError("oldPass","Ви не авторизовані");

        /**
         * @var $user GlobalUsers
         */
        $user = GlobalUsers::findOne(Yii::$app->user->id);

        if (!$user)
            $this->addError("oldPass","Користувача не знайдено");

        if (!$user->validatePassword($this->oldPass))
            $this->addError("oldPass","Пароль введено невірно");
    }

    public function attributeLabels()
    {
        return array(
            'oldPass'=>'Пароль',
            'newPass'=>'Новий пароль',
            'newPassRepeat'=>'Повторіть новий пароль',
        );
    }

    public function saveNewPassword()
    {
        /**
         * @var $user GlobalUsers
         */
        $user = GlobalUsers::findOne(Yii::$app->user->id);
        $user->password = GlobalUsers::hashPassword($this->newPass);
        $user->save(false,["password"]);
    }

}

