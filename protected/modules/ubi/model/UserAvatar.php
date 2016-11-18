<?php
namespace tit\ubi\model;

use tit\ubi\UbiModule;
use Yii;
use yii\db\ActiveRecord;
    /**
     * This is the model class for table "Avatars".
     *
     * The followings are the available columns in table 'Avatars':
     * @property integer $id
     * @property string $image
     */
    class UserAvatar extends ActiveRecord
    {

        public $measurements;
        public $pictureName;
        /**
         * @return string the associated database table name
         */
        public static  function tableName()
        {
            return 'UserAvatar';
        }

        public static  function getDb()
        {
            return Yii::$app->{UbiModule::getInstance()->ubiDatabaseName};
        }

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return
            [
                [['id'], 'integer' ,'integerOnly'=>true],
                [['image'],'safe'],
                [['id', 'image'],'safe','on'=>'search']
            ];
        }


        /**
         * @return array customized attribute labels (name=>label)
         */
        public function attributeLabels()
        {
            return array(
                'id' => 'ID',
                'image' => 'Image',
            );
        }
    }