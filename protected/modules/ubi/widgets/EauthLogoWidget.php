<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ertong
 * Date: 8/16/13
 * Time: 8:46 PM
 * To change this template use File | Settings | File Templates.
 */
namespace app\modules\ubi\widgets;


use app\model\form\ChangePassForm;
use app\models\Users;
use yii\base\View;
use yii\base\Widget;
use Yii;


class EauthLogoWidget extends Widget
{
    public $action= array("/site/changePass");
    public $successfulUrl=null;
    /**
     * @var ChangePassForm
     */
    public $model;
    public $successMessage;

    public function run()
    {
        
        return $this->render("eauthLogo");
    }
}