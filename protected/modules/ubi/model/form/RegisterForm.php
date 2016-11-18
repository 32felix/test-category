<?php
/**
 * Created by PhpStorm.
 * User: Igor
 * Date: 2016-07-21
 * Time: 12:42
 */

namespace app\modules\ubi\model\form;


use app\models\PotentialUserToUser;
use app\modules\Partner;
use app\modules\ubi\model\UserMail;
use app\modules\ubi\model\UserPhone;
use tit\ubi\model\GlobalUsers;
use yii\base\Model;

class RegisterForm extends Model
{
    public $phone;
    public $email;
    public $password;

    public function rules()
    {
        return [
            [['password'], 'required'],
            ['password', 'string', 'min'=>6],
            [['email'], 'email'],
            [['phone'], 'match', 'pattern' => '/^\+38(050|066|095|099|067|068|096|097|098|063|093|073|091)\d{7}$/i'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'phone' => "Телефон",
            'password' => "Пароль",
            'email' => "Електронна адреса",
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

        $this->email = strtolower(trim($this->email));
        if (!$this->email) $this->email = null;
        if (!$this->phone) $this->phone = null;

        return parent::beforeValidate();
    }

    public function afterValidate()
    {
        if (!$this->phone && !$this->email)
            $this->addError("phone", "Вкажіть свої телефон і/або електронну адресу");

        $emailError = false;

        if ($this->email) {
            $gUser = GlobalUsers::findOne(["email" => $this->email]);
            if ($gUser) {
                $this->addError("email", "Для адреси $gUser->email вже зареєстрований користувач. Спробуйте <span class='restore-password-link'>відновити пароль.</span>");
                $emailError = true;
            }
        }

        if (!$emailError && $this->email) {
            $mail = UserMail::find()
                ->where(["address" => $this->email])
                ->andWhere("timeVerified IS NOT NULL")
                ->one();
            if ($mail)
                $this->addError("email", "Для адреси $mail->address вже зареєстрований користувач. Спробуйте <span class='restore-password-link'>відновити пароль.</span>");
        }

        if ($this->phone) {
            $gUser = GlobalUsers::findOne(["phone" => $this->phone]);
            if ($gUser)
                $this->addError("phone", "Телефон $gUser->phone вже зареєстрований. Спробуйте <span class='restore-password-link'>відновити пароль.</span>");
        }
    }

    public function createUser($extra=[])
    {
        $gUser = new GlobalUsers();
        $gUser->email = $this->email;
        $gUser->phone = $this->phone;
        $gUser->password = GlobalUsers::hashPassword($this->password);

        $gUser->save(false);
        if ($gUser->email)
        {
            $mail = new UserMail();
            $mail->user = $gUser->id;
            $mail->address = $gUser->email;
            $mail->save(false, ["user","address"]);
            $mail->sendRegisterMail();
            $mail->save(false, ["timeVerificationSent"]);
        }
        if ($gUser->phone) {
            (new UserPhone([
                'user' => $gUser->id,
                'phone' => $gUser->phone
            ]))->save(false);
        }
        if (!empty($extra["partner"]))
        {
            $partner = Partner::findOne($extra["partner"]);
            if ($partner)
            {
                \Yii::$app->db->createCommand("INSERT IGNORE INTO UserPartner(`user`, `partner`) VALUES (:user, :partner)", [
                    "user"=>$gUser->id,
                    "partner"=>$partner->id,
                ])->execute();
            }
        }

        PotentialUserToUser::markConversion(issetdef($extra["mid"]), $gUser);
        
        \Yii::$app->getUser()->login($gUser->ensureLocalUser());
    }


}