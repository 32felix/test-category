<?php

namespace app\modules\ubi\model;

use app\components\MyMail;
use tit\ubi\model\GlobalUsers;
use tit\ubi\UbiModule;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "UserMail".
 *
 * @property integer $id
 * @property integer $user
 * @property string $address
 * @property string $timeCreated
 * @property string $timeVerificationSent
 * @property string $timeVerified
 * @property string $timeDeleted
 *
 * @property GlobalUsers $user0
 */
class UserMail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'UserMail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user', 'address'], 'required'],
            [['user'], 'integer'],
            [['timeCreated', 'timeVerificationSent', 'timeVerified', 'timeDeleted'], 'safe'],
            [['address'], 'string', 'max' => 50],
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
            'address' => 'Address',
            'timeCreated' => 'Time Created',
            'timeVerificationSent' => 'Time Verification Sent',
            'timeVerified' => 'Time Verified',
            'timeDeleted' => 'Time Deleted',
        ];
    }

    public function getUser0()
    {
        return $this->hasOne(GlobalUsers::class, ["id"=>"user"]);
    }

    public function sendRegisterMail()
    {
        $this->timeVerificationSent = date("Y-m-d H:i:s");
        MyMail::send("layouts/html3", "register", [
            "userMail" => $this,
            "guser" => GlobalUsers::findOne($this->user),
        ], $this->address, "email-register", null, null, false);

    }

    public function sendAddMail()
    {
        $this->timeVerificationSent = date("Y-m-d H:i:s");
        MyMail::send("layouts/html3", "confirmEmail", [
            "userMail" => $this,
            "guser" => GlobalUsers::findOne($this->user),
        ], $this->address, "email-register", null, null, false);

    }


    public function sendPasswordRestoreEmail()
    {
        $this->timeVerificationSent = date("Y-m-d H:i:s");

        MyMail::send("layouts/html3", "restoreEmail", [
            "userMail" => $this,
            "guser" => GlobalUsers::findOne($this->user),
        ], $this->address, "email-restore", null, null, false);
    }

    public function genConfirmEmailToken()
    {
        return sha1(UbiModule::$secret . "{$this->id}-{$this->address}");
    }

    public function genRestorePasswordToken($oldPassword)
    {
        return sha1(UbiModule::$secret . "{$this->id}-{$this->address}-$oldPassword");
    }

    /**
     * @return ActiveQuery
     */
    public static function findVerified($address)
    {
        return UserMail::find()
            ->where(["address"=>$address])
            ->andWhere("timeVerified IS NOT NULL");
    }
}
