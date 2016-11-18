<?php
/**
 * @var $this View
 * @var $widget \tit\ubi\widgets\LoginFormWidget
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var \tit\ubi\model\form\LoginForm $model
 */

use yii\base\View;
use yii\helpers\Html;
use yii\helpers\Url;
use nodge\eauth\Widget;
use tit\widgets\AjaxSubmit;
use yii\widgets\ActiveForm;

$widget = $this->context;
$id = $widget->getId();
?>
<div id="<?=$id?>-body">
        <div class="body-border">
            <div class="body-content" id="oauth">
                <div class="body-social-content">
                    <?= Widget::widget(array('id'=>'login-popup-eauth','action' => 'login/login-by-eauth')); ?>
                </div>
                <div class="body-another to-by-mail">
                    <a><?=Yii::t("tit/ubi", "Login by mail")?></a>
                </div>
            </div>
            <div class="body-content" id="by-mail" style="display: none">
                <div class="body-content-login">
                    <div class="warn"><?=Yii::t("tit/ubi","Registration by email is temporary disabled, but you can login if you are already registered.")?></div>
                    <div class="body-content-form">

                        <?=$this->renderFile(__DIR__."/../../views/login/loginForm.php",["model"=>$model])?>
                    </div>
                </div>
                <div class="body-another to-oauth">
                    <a><?=Yii::t("tit/ubi", "Login by social network")?></a>
                </div>
            </div>
        </div>

</div>
<script>
    $(function(){
        var $div = $("#<?=$id?>-body");
        $(".to-by-mail a", $div).click(function(){
            $("#by-mail", $div).show();
            $("#oauth", $div).hide();
        });

        $(".to-oauth a", $div).click(function(){
            $("#by-mail", $div).hide();
            $("#oauth", $div).show();
        });

    })
</script>
