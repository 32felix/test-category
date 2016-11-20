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

    public $userId;


	public function rules()
    {
		return
        [
            [['oldPass'], 'checkOldPass' , 'on'=>self::SCENARIO_CHANGE],
            [['newPass'], 'string', 'min'=>6, 'max'=>100],
            [['newPassRepeat'], 'safe'],
            [['newPassRepeat', 'newPass'], 'required' ,'on'=>self::SCENARIO_SET],
            [['newPass'], 'compare', 'compareAttribute'=>'newPassRepeat'],
        ];
	}

    function checkOldPass()
    {
        if (Yii::$app->user->isGuest)
            $this->addError("TitUbiAuthModule.user","User is not signed in.");

        /**
         * @var $user GlobalUsers
         */
        $user = GlobalUsers::find()->where(['id'=>$this->userId])->one();

        if (!$user)
            $this->addError("User not found");

        if (!$user->validatePassword($this->oldPass))
            $this->addError("Password is incorrect");
    }

    public function attributeLabels()
    {
        return array(
            'oldPass'=>'Old password',
            'newPass'=>'New password',
            'newPassRepeat'=>'Repeat new password',
        );
    }

    public function saveNewPassword()
    {
        /**
         * @var $user GlobalUsers
         */
        $user = GlobalUsers::find()->where(['id'=>$this->userId])->one();
        $user->scenario = GlobalUsers::SCENARIO_CHANGE_PASS;
        $user->password = $this->newPass;
        $user->update();
    }

}

