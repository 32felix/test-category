<?php
//
//use tit\ubi\UbiModule;
//use yii\base\Component;
//
//class UbiCache extends Component
//{
//    public $cacheTime = 600;
//    public $blocksParams = array('password','id');
//    public $checkUbiDB = true;
//
//    private $userInfo = array();
//
//    public function init()
//    {
//
//    }
//
//    public function get($param, $user=null, $allowCached=null)
//    {
//        if (in_array( $param, $this->blocksParams))
//            throw new Exception('UbiCache: Parameter $param is blocked');
//
//        $id = $user ? $user : Yii::$app->user->getId();
//
//        if (!$id)
//            throw new Exception('UbiCache: user not supplied and not logged in');
//
//        if (isset($this->userInfo[$id][$param]))
//            return $this->userInfo[$id][$param];
//
//        if (!$allowCached)
//        {
//            if ($return = Yii::$app->cache->get("ubi".$id.$param))
//                return $return;
//        }
//
//        // sourceName=>array(keys)
//        $keys = Yii::$app->cache->get("ubi.userParamsLocation");
//
//        if (empty($keys))
//            $keys=array(
//                "ubi"=>array(),
//                'local'=>array(),
//            );
//
//        if (!$this->checkUbiDB)
//            unset($keys['ubi']);
//
//        $value = null;
//        $hasValue = false;
//        $haveNewKeys = false;
//        foreach($keys as $src=>$skeys)
//        {
//            if (empty($skeys) || in_array($param, $skeys))
//            {
//                $A = [];
//                switch ($src)
//                {
//                    case "ubi":
//                        $A = $this->getGlobalUserInfo($id);
//                        break;
//                    case "local":
//                        $A = $this->getLocalUserInfo($id);
//                        break;
//                }
//                $newKeys = array_keys($A);
//                if (count(array_diff($keys[$src],$newKeys))>0 ||
//                    count(array_diff($newKeys,$keys[$src]))>0)
//                {
//                    $keys[$src]=$newKeys;
//                    $haveNewKeys = true;
//                }
//
//                if (array_key_exists($param,$A))
//                {
//                    $value = $A[$param];
//                    $hasValue = true;
//                    break;
//                }
//
//            }
//        }
//
//        if ($haveNewKeys)
//            Yii::$app->cache->set("ubi.userParamsLocation", $keys);
//
//        if (!$hasValue)
//            throw new Exception("UbiCache: Parameter $param has not been found among database columns.");
//
//        return $value;
//    }
//
//    public function clear($param, $user=null)
//    {
//        if (in_array( $param, $this->blocksParams))
//            throw new Exception('UbiCache: Parameter $param is blocked');
//
//        $id = $user ? $user : Yii::$app->user->getId();
//        if (!$id)
//            throw new Exception('UbiCache: user not supplied and not logged in');
//
//        Yii::$app->cache->delete('ubi'.$id.$param);
//    }
//
//    public function getSlug($user=null, $allowCached=null)
//    {
//        return $this->get('slug', $user, $allowCached);
//    }
//
//    public function getName($user=null, $allowCached=null)
//    {
//        return $this->get('name', $user, $allowCached);
//    }
//
//
//    /**
//     * Обновлення інформації в кеші по користувачу з глобальної бази
//    */
//    private function updateCacheUbi($id, $array)
//    {
//
//        foreach ($array as $key=> $value)
//        {
//            if (in_array( $key, $this->blocksParams)) continue;
//            Yii::$app->cache->set('ubi'.$id.$key, $value, $this->cacheTime);
//        }
//    }
//
//    /**
//     * Отримує всю інформацію про користувача з глобальної бази данних
//    */
//    private function getGlobalUserInfo($id)
//    {
//        $sql = 'SELECT * FROM `'.$this->ubiBaseName.'` WHERE id = :id';
//        $info = UbiModule::getInstance()->getDbUbi()->createCommand($sql)->bindValue(':id', $id)->queryOne();
//	    if (is_array($info))
//		    $this->updateCacheUbi($id, $info);
//        if (empty($this->userInfo[$id]))
//            $this->userInfo[$id] = $info;
//        else
//            $this->userInfo[$id] += $info;
//
//        return $this->userInfo[$id];
//    }
//
//    /**
//     * Отримання інформації про користувача з локальної бази данних
//    */
//    private function getLocalUserInfo($id)
//    {
//        $sql = 'SELECT * FROM `'.$this->localUserBaseName.'` WHERE id = :id';
//        $info = UbiModule::getInstance()->getDb()->createCommand($sql)->bindValue(':id', $id)->queryOne();
//	    if (is_array($info))
//		    $this->updateCacheUbi($id, $info);
//        if (empty($this->userInfo[$id]))
//            $this->userInfo[$id] = $info;
//        else
//            $this->userInfo[$id] += $info;
//        return $this->userInfo[$id];
//    }
//
//
//
//}