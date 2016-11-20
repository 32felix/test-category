<?php
namespace tit\ubi\model;

use app\models\User;
use tit\ubi\UbiModule;
use tit\utils\helpers\Globals;
use yii\base\Security;
use yii\db\ActiveRecord;
use Yii;

/**
 * This is the model class for table "GlobalUsers".
 *
 * The followings are the available columns in table 'GlobalUsers':
 * @property integer $id
 * @property string $email
 * @property string $password
 * @property string $timeRegistration
 * @property string $timeAddAvatar
 * @property string $accessCode
 * @property integer $active
 * @property string $slug
 * @property string $name
 * @property string $lastName
 * @property string $login
 * @property string $telephone
 * @property integer $referrer
 * @property string $referral
 * @property string $birthday
 * @property string $sex
 * @property string $unconfirmedEmail
 * @property integer $countryId
 * @property integer $cityId
 * @property string $verifyCode Verify Code
 */
class GlobalUsers extends ActiveRecord
{
    const SCENARIO_LOGIN = 'login';
    const SCENARIO_REGISTRATION = 'registration';
    const SCENARIO_ACTIVATE = 'activate';
    const SCENARIO_EMAIL_CONFIRMATION = 'emailConfirmation';
    const SCENARIO_RETURN_PASS = 'returnPass';
    const SCENARIO_CHANGE_PASS = 'changePass';
    const SCENARIO_ADD_EMAIL = 'addEmail';
    const SCENARIO_UNCONFIRMED_EMAIL = 'unconfirmedEmail';
    const SCENARIO_RESTORE_PASSWORD_REQUEST = 'restorePasswordRequest';


    const ACTIVE = 1;
    const INACTIVE = 0;

    const DEFAULT_REFERRER = 0;


    public $verifyCode;
    public $user;


	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'GlobalUsers';
	}

    public static  function getDb()
    {
        Yii::$app->getModule("ubi");
        $name=UbiModule::getInstance()->ubiDatabaseName;
        return Yii::$app->{$name};
//        return Yii::$app->dbUbi;
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{

		return
        [
//            [['active', 'referrer', 'countryId', 'cityId'], 'integer', 'integerOnly'=>true],
            [['email'], 'unique',   'on' => [self::SCENARIO_REGISTRATION]],
            [['email'], 'email',    'on' => [self::SCENARIO_REGISTRATION]],
            [['email'], 'required', 'on' => [self::SCENARIO_REGISTRATION]],

//            [['email'], 'filter', 'filter' => 'mb_strtolower']],

            [['password'], 'filter', 'filter' => array($this, 'hashPassword'), 'on' => array(self::SCENARIO_CHANGE_PASS,self::SCENARIO_REGISTRATION,self::SCENARIO_ACTIVATE, self::SCENARIO_EMAIL_CONFIRMATION, self::SCENARIO_LOGIN )],
//          [['password'], 'authenticate', 'on' => self::SCENARIO_LOGIN],
            [['accessCode'], 'default', 'value' =>  $this->accessCode(), 'on' => [self::SCENARIO_RETURN_PASS, self::SCENARIO_ADD_EMAIL, self::SCENARIO_REGISTRATION]],

            [['email'], 'email', 'on' => [self::SCENARIO_RESTORE_PASSWORD_REQUEST]],
            [['email'], 'required', 'on' => [self::SCENARIO_RESTORE_PASSWORD_REQUEST]],
            [['email'], 'exist', 'on' => [self::SCENARIO_RESTORE_PASSWORD_REQUEST]],
            [['accessCode'], 'default', 'value' => GlobalUsers::accessCode(), 'on' => [self::SCENARIO_RESTORE_PASSWORD_REQUEST , self::SCENARIO_UNCONFIRMED_EMAIL]],

//            [['email', 'password', 'accessCode'], 'length', 'max'=>100],
//            [['slug','name', 'lastName', 'login', 'telephone', 'referral'], 'length', 'max'=>128],
//            [['sex'], 'length', 'max'=>5],
            [['timeRegistration', 'birthday'], 'safe'],

            ['verifyCode', 'captcha', 'captchaAction' => 'ubi/user/captcha','on'=>[self::SCENARIO_REGISTRATION]],
//            ['verifyCode', 'captcha', 'captchaAction' => 'ubi/user/captcha'],

            [['id', 'email', 'password', 'timeAddAvatar', 'timeRegistration', 'accessCode', 'active', 'slug', 'name', 'lastName', 'login', 'telephone', 'referrer', 'referral', 'birthday', 'sex', 'countryId', 'cityId'], 'safe', 'on'=>'search'],
        ];

	}

    public static function accessCode()
    {
        $assess = new Security();
        return $assess->generateRandomString(32);
    }


	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'email' => Yii::t('app','Email'),
            'verifyCode' => Yii::t('app','verifyCode'),
			'password' => 'Password',
			'timeRegistration' => 'Time Registration',
			'accessCode' => 'Access Code',
			'active' => 'Active',
			'slug' => 'Slug',
			'name' => 'Name',
			'lastName' => 'Last Name',
			'login' => 'Login',
			'telephone' => 'Telephone',
			'referrer' => 'Referrer',
			'referral' => 'Referral',
			'birthday' => 'Birthday',
			'sex' => 'Sex',
			'countryId' => 'Country',
			'cityId' => 'City',
		);
	}

    public function hashPassword($password)
    {
        return Yii::$app->getSecurity()->generatePasswordHash($password);
    }

    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password);
        //return crypt($password, $this->password) === $this->password;
    }


    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert))
        {
            if (!$insert)
            {
                switch ($this->scenario)
                {
                    case self::SCENARIO_EMAIL_CONFIRMATION :
                        $this->active = self::ACTIVE;
                        $this->accessCode = null;
                        break;
                }
            }
            return true;
        }
        return false;
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

        if (!empty($ref))
        {
            $user = GlobalUsers::find()->where(['id'=>$ref]);
            if (empty($ref))
                $user = GlobalUsers::find()->where(['referral' => $ref]);
            return $user;
        }
        return null;
    }


    public function ensureLocalUser()
    {
        $localUser = Users::find()->where(['id'=>$this->id])->one();
        if (!isset($localUser) || empty($localUser))
        {
            if (method_exists(Users::class, "newUser"))
                $localUser = Users::newUser($this);
            else
            {
                $localUser = new Users();
                $localUser->id = $this->id;
                $localUser->save(false);
            }
        }
        return $localUser;
    }

}
