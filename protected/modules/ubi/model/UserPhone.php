<?php

namespace app\modules\ubi\model;

use Yii;
use app\components\MyMail;
use tit\ubi\model\GlobalUsers;
use tit\ubi\UbiModule;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "UserPhone".
 *
 * @property integer $id
 * @property integer $user
 * @property string $phone
 * @property string $timeCreated
 * @property string $timeVerificationSent
 * @property string $timeVerified
 * @property string $timeDeleted
 *
 * @property GlobalUsers $user0
 */
class UserPhone extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'UserPhone';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phone'], 'required'],
            [['phone'], 'match', 'pattern' => '/^\+38(050|066|095|099|067|068|096|097|098|063|093|073|091)\d{7}$/i',
                'message'=>'Підтримуються лише номери телефонів українських операторів мобільного зв\'язку.'],
//            [['user'], 'integer'],
//            [['timeCreated', 'timeVerificationSent', 'timeVerified', 'timeDeleted'], 'safe'],
            [['phone'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user' => 'User',
            'phone' => 'Phone',
            'timeCreated' => 'Time Created',
            'timeVerificationSent' => 'Time Verification Sent',
            'timeVerified' => 'Time Verified',
            'timeDeleted' => 'Time Deleted',
        ];
    }

    public function beforeValidate()
    {
        if ($this->phone) {
            $this->phone = preg_replace("~[^0-9]~", "", trim($this->phone));
            if (!preg_match("~^38~", $this->phone))
                $this->phone = "38" . $this->phone;
            $this->phone = "+" . $this->phone;
        }

        return parent::beforeValidate();
    }

    public function getUser0()
    {
        return $this->hasOne(GlobalUsers::class, ["id"=>"user"]);
    }
    public function sendAddPhone()
    {
        $this->timeVerificationSent = date("Y-m-d H:i:s");
        MyMail::send("layouts/html3", "confirmPhone", [
            "userPhone" => $this,
            "guser" => $this->user0,
        ], $this->user0->email, "phone-register", null, null, false);

    }

    public function genConfirmPhoneToken()
    {
        return sha1(UbiModule::$secret . "{$this->id}-{$this->phone}");
    }
    /**
     * @return ActiveQuery
     */
    public static function findVerified($phone)
    {
        return UserPhone::find()
            ->where(["phone"=>$phone])
            ->andWhere("timeVerified IS NOT NULL");
    }
}
