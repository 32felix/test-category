<?php

namespace app\model;

use yii\db\ActiveRecord;
use Yii;

/**
 * This is the model class for table "UsersSocialAccounts".
 *
 * The followings are the available columns in table 'UsersSocialAccounts':
 * @property integer $userid
 * @property string $provider
 * @property string $providerId
 */
class UsersSocialAccounts extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public static  function tableName()
	{
		return 'UsersSocialAccounts';
	}

    public static  function getDb()
    {
        return Yii::$app->db;
    }
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return
        [
            [['userid, provider, providerId'], 'required'],
            [['userid', 'numerical'], 'integerOnly'=>true],
            [['provider', 'length'], 'max'=>20],
            [['providerId', 'length'], 'max'=>1000],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            [['userid, provider, providerId'], 'safe', 'on'=>'search'],
        ];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'userid' => 'Userid',
			'provider' => 'Provider',
			'providerId' => 'Provider',
		];
	}
}
