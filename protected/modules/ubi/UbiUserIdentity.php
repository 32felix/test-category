<?php
//
///**
// * UserIdentity represents the data needed to identity a user.
// * It contains the authentication method that checks if the provided
// * data can identity the user.
// */
//use nodge\eauth\ServiceBasese;
//use yii\web\IdentityInterface;
//
//require_once(dirname(__FILE__)."/model/GlobalUsers.php");
//require_once(dirname(__FILE__)."/model/UsersSocialAccounts.php");
//
//class UbiUserIdentity implements IdentityInterface {
//
//    public $localUserBase = 'User';
//    private $_id;
//
//    public function authenticateByEAuth(ServiceBasese $service)
//    {
//        if ($service->isAuthenticated)
//        {
//            $usa = UsersSocialAccounts::model()->findByAttributes(array(
//                "provider"=>$service->getServiceName(),
//                "providerId"=>$service->getId(),
//            ));
//
//            if ($usa==null )
//            {
//                $email = $service->getAttribute('email');
//                $user = null;
//
//                if ($email)
//                    $user = GlobalUsers::model()->findByAttributes(array("email"=>$email));
//
//                if ($user==null && !Yii::app()->user->isGuest)
//                    $user = GlobalUsers::model()->findByPk(Yii::app()->user->getId());
//
//                if ($user==null)
//                    $user = new GlobalUsers();
//
//                if ($user->name==null)
//                    $user->name = $service->getAttribute('name');
//                if ($user->email==null)
//                    $user->email = $service->getAttribute('email');
//                if ($user->birthday==null)
//                    $user->birthday = $service->getAttribute('birthday');
//                if ($user->lastName==null)
//                    $user->lastName = $service->getAttribute('lastname');
//                $user->save(false);
//
//                $usa = new UsersSocialAccounts();
//                $usa->userid = $user->id;
//                $usa->provider = $service->getServiceName();
//                $usa->providerId = $service->getId();
//                $usa->data = print_r($service->getAttributes(), true);
//                $usa->save(false);
//
//                $localUser = User::model()->findByPk($user->id);
//                if (!$localUser)
//                {
//                    $localUser = new User();
//                    $localUser->id = $user->id;
//                    $localUser->save();
//                }
//
//            }
//            else
//            {
//                $usa->data = print_r($service->getAttributes(), true);
//                $usa->save(false);
//                $user = GlobalUsers::model()->findByPk($usa->userid);
//
//                $localUser = User::model()->findByPk($user->id);
//                if (!$localUser)
//                {
//                    $localUser = new User();
//                    $localUser->id = $user->id;
//                    $localUser->save();
//                }
//
//            }
//
//            $this->_id = $user->id;
//            $this->username = $user->name;
//
//            $this->setState('id', $this->id);
//            $this->setState('name', $this->username);
//
//            $this->errorCode = self::ERROR_NONE;
//        }
//        else {
//            $this->errorCode = self::ERROR_NOT_AUTHENTICATED;
//        }
//        return !$this->errorCode;
//    }
//
//    public function authenticate()
//    {
//        $criteria = new CDbCriteria();
//        $criteria->condition = ' email = :email';
//        $criteria->params = array(':email' => strtolower($this->username));
//
//        /**
//         * @var GlobalUsers $user
//         */
//        $user = GlobalUsers::model()->find($criteria);
//
//        if ($user === null)
//        {
//            $this->errorCode = self::ERROR_USERNAME_INVALID;
//        }
//        else
//        {
//            if (!$user->validatePassword($this->password))
//            {
//                $this->errorCode = self::ERROR_PASSWORD_INVALID;
//            }
//            else
//            {
//                $this->initUser($user);
//                $this->errorCode = self::ERROR_NONE;
//            }
//        }
//        return $this->errorCode === self::ERROR_NONE;
//    }
//
//    public function initUser(GlobalUsers $user)
//    {
//        $this->_id = $user->id;
//        $this->setState('name', $user->name);
//        $this->setState('email', $user->email);
//    }
//
//    public function getId()
//    {
//        return $this->_id;
//    }
//
//}