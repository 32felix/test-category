<?php
namespace tit\ubi\model;

use app\components\MyMail;
use app\models\User;
use tit\ubi\UbiModule;
use tit\utils\helpers\Globals;
use yii\base\Security;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use Yii;
use yii\validators\EmailValidator;

/**
 * This is the model class for table "GlobalUsers".
 *
 * The followings are the available columns in table 'GlobalUsers':
 * @property integer $id
 * @property string $email
 * @property string $password
 * @property string $timeRegistration
 * @property string $timeAddAvatar
 * @property integer $active
 * @property string $slug
 * @property string $name
 * @property string $lastName
 * @property string $login
 * @property string $phone
 * @property integer $referrer
 * @property string $referral
 * @property string $birthday
 * @property string $sex
 * @property string $unconfirmedEmail
 * @property integer $countryId
 * @property integer $cityId
 * @property string $verifyCode Verify Code
 * @property string $timeEmailVerificationSent
 * @property string $timeEmailVerified
 */
class GlobalUsers extends ActiveRecord
{
    const SCENARIO_LOGIN = 'login';
    const SCENARIO_REGISTRATION = 'registration';
    const SCENARIO_REGISTRATION_POPUP = 'registration_popup';
    const SCENARIO_ACTIVATE = 'activate';
    const SCENARIO_EMAIL_CONFIRMATION = 'emailConfirmation';
    const SCENARIO_RETURN_PASS = 'returnPass';
    const SCENARIO_CHANGE_PASS = 'changePass';
    const SCENARIO_RESTORE_PASS = 'restorePass';
    const SCENARIO_ADD_EMAIL = 'addEmail';
    const SCENARIO_UNCONFIRMED_EMAIL = 'unconfirmedEmail';
    const SCENARIO_RESTORE_PASSWORD_REQUEST = 'restorePasswordRequest';
    const SCENARIO_CHANGE_EMAIL = 'changeEmail';


    const ACTIVE = 1;
    const INACTIVE = 0;

    const DEFAULT_REFERRER = 0;


    public $verifyCode;
    public $user;


    public $currentPassword;
    public $newPassword;
    public $confirmPassword;


    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return 'GlobalUsers';
    }

    public static function getDb()
    {
        Yii::$app->getModule("ubi");
        $name = UbiModule::getInstance()->ubiDatabaseName;
        return Yii::$app->{$name};
//        return Yii::$app->dbUbi;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {

        $res=
            [
                [['email'], 'email', 'on' => [self::SCENARIO_CHANGE_EMAIL, self::SCENARIO_REGISTRATION_POPUP]],
                [['email'], 'required', 'on' => [self::SCENARIO_CHANGE_EMAIL]],


                [['currentPassword'], function () {
                    if ($this->password && !$this->validatePassword($this->currentPassword)) {

                        $this->addError('currentPassword', 'Пароль введено невірно');
                    }

                }, 'on' => [self::SCENARIO_CHANGE_PASS]],
//                [['newPassword'], function () {
//                    $this->addError('newPassword', 'newPassword');
//                }, 'on' => [self::SCENARIO_CHANGE_PASS]],
                [['confirmPassword'], function () {

                    if ($this->newPassword !== $this->confirmPassword) {
                        $this->addError('confirmPassword', 'Введені паролі різні');
                    }

                }, 'on' => [self::SCENARIO_CHANGE_PASS, self::SCENARIO_REGISTRATION_POPUP, self::SCENARIO_RESTORE_PASS]],


                [['newPassword'], 'required', 'on' => [self::SCENARIO_CHANGE_PASS, self::SCENARIO_RESTORE_PASS]],
                [['confirmPassword'], 'required', 'on' => [self::SCENARIO_CHANGE_PASS, self::SCENARIO_RESTORE_PASS]],


//            [['active', 'referrer', 'countryId', 'cityId'], 'integer', 'integerOnly'=>true],
                [['email'], 'unique', 'on' => [self::SCENARIO_REGISTRATION, self::SCENARIO_REGISTRATION_POPUP]],
                [['email'], 'email', 'on' => [self::SCENARIO_REGISTRATION, self::SCENARIO_REGISTRATION_POPUP]],
                [['email'], 'required', 'on' => [self::SCENARIO_REGISTRATION, self::SCENARIO_REGISTRATION_POPUP]],
                

//            [['email'], 'filter', 'filter' => 'mb_strtolower']],

                [['password'], 'filter', 'filter' => array($this, 'hashPassword'), 'on' => array(self::SCENARIO_CHANGE_PASS, self::SCENARIO_REGISTRATION, self::SCENARIO_ACTIVATE, self::SCENARIO_EMAIL_CONFIRMATION, self::SCENARIO_LOGIN)],
//          [['password'], 'authenticate', 'on' => self::SCENARIO_LOGIN],

                [['email'], 'email', 'on' => [self::SCENARIO_RESTORE_PASSWORD_REQUEST]],
                [['email'], 'required', 'on' => [self::SCENARIO_RESTORE_PASSWORD_REQUEST]],
                [['email'], 'exist', 'on' => [self::SCENARIO_RESTORE_PASSWORD_REQUEST]],

                [['timeRegistration', 'birthday'], 'safe'],

                ['verifyCode', 'captcha', 'captchaAction' => 'ubi/user/captcha', 'on' => [self::SCENARIO_REGISTRATION]],
//            ['verifyCode', 'captcha', 'captchaAction' => 'ubi/user/captcha'],

                [['id', 'email', 'password', 'timeAddAvatar', 'timeRegistration',  'active', 'slug', 'name', 'lastName', 'login', 'referrer', 'referral', 'birthday', 'sex', 'countryId', 'cityId'], 'safe', 'on' => 'search'],
            ];

        if ($this->password)
            $res[]=[['currentPassword'], 'required', 'on' => [self::SCENARIO_CHANGE_PASS]];

        return $res;
    }


    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'email' => Yii::t('app', 'Email'),
            'verifyCode' => Yii::t('app', 'verifyCode'),
            'password' => 'Password',
            'timeRegistration' => 'Time Registration',
            'active' => 'Active',
            'slug' => 'Slug',
            'name' => 'Name',
            'lastName' => 'Last Name',
            'login' => 'Login',
            'phone' => 'Phone',
            'referrer' => 'Referrer',
            'referral' => 'Referral',
            'birthday' => 'Birthday',
            'sex' => 'Sex',
            'countryId' => 'Country',
            'cityId' => 'City',
            'currentPassword' => 'Пароль',
            'newPassword' => 'Новий пароль',
            'confirmPassword' => 'Повторіть новий пароль',
        );
    }

    public function getAsText()
    {
        return "{$this->name}[$this->id]";
    }

    public static function hashPassword($password)
    {
        return Yii::$app->getSecurity()->generatePasswordHash($password);
    }

    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password);
        //return crypt($password, $this->password) === $this->password;
    }

    public function validateEmail($email) {
        $res = Yii::$app->db->createCommand("SELECT COUNT(*) FROM GlobalUsers WHERE email=\"" . $email . '"')->queryOne();

        return $res["COUNT(*)"] === "0";
    }

    public function register() {
        $this->password = $this->hashPassword($this->newPassword);

        if(!$this->validateEmail($this->email)) {
            $this->addError("email", "E-mail вже зайнятий");
            return false;
        }

        $this->save();
        return true;
    }

    public function beforeSave($insert)
    {
        return true;
    }

    /**
     * @var array EAuth attributes
     */
    public $profile;

    /**
     * @return GlobalUsers|null
     */
    public static function getReferrer()
    {
        $ref = \issetdef($_REQUEST['referrer'], null);
        if (empty($ref))
            $ref = \issetdef($_COOKIE['referrer'], null);

        if (!empty($ref)) {
            $user = GlobalUsers::find()->where(['id' => $ref]);
            if (empty($ref))
                $user = GlobalUsers::find()->where(['referral' => $ref]);
            return $user;
        }
        return null;
    }


    /**
     * @return User
     */
    public function ensureLocalUser()
    {
        $localUser = User::find()->where(['id' => $this->id])->one();
        if (!isset($localUser) || empty($localUser)) {
            if (method_exists(User::class, "newUser"))
                $localUser = User::newUser($this);
            else {
                $localUser = new User();
                $localUser->id = $this->id;
                $localUser->save(false);
            }
        }
        return $localUser;
    }


    public function sendSetEmailConfirmMail()
    {
        $this->timeEmailVerificationSent = date("Y-m-d H:i:s");

        $v = new EmailValidator();
        if (preg_match($v->pattern, $this->email)) {
            MyMail::send("layouts/html3", "confirmEmail", [
//            "user" => $this,
                "guser" => $this,
            ], $this->email, "email-confirm", null, null, false);
        }
    }


    public function sendGeneratePasswordEmail($password)
    {

        MyMail::send("layouts/html3", "sendPass", [
//            "user" => $this,
            "guser" => $this,
            'password' => $password
        ], $this->email, "email-confirm", null, null, false);

    }

    /**
     * @param $address
     * @return ActiveQuery
     */
    public static function findByEmail($address)
    {
        return GlobalUsers::find()
            ->where("email=:mail OR id IN (SELECT user FROM UserMail WHERE address=:mail AND timeVerified IS NOT NULL)",[
                "mail"=>$address,
            ]);
    }

//    public function sendRegisterMail()
//    {
//        $this->timeEmailVerificationSent = date("Y-m-d H:i:s");
//        MyMail::send("layouts/html3", "register", [
//            "guser" => $this,
//        ], $this->email, "email-register", null, null, false);
//
//    }


}
