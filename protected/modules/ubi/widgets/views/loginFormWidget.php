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
                    <?= Widget::widget(array('id'=>'login-popup-eauth','action' => 'user2/login-by-eauth')); ?>
                </div>
                <div class="body-another to-by-mail">
                    <a><?=Yii::t("tit/ubi", "Login by mail")?></a>
                </div>
            </div>
            <div class="body-content" id="by-mail" style="display: none">
                <div class="body-content-login">
                    <div class="warn"><?=Yii::t("tit/ubi","Registration by email is temporary disabled, but you can login if you are already registered.")?></div>
                    <div class="body-content-form">

                        <?=$this->renderFile(__DIR__."/../../views/user2/loginForm.php",["model"=>$model])?>
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

<?/*


    <p class="lead">Do you already have an account on one of these sites? Click the logo to log in with it here:</p>
    <?php echo Widget::widget(array('action' => 'user/login')); ?>


    <p>Please fill out the following fields to login:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'login') ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

    <?/*= $form->field($model, 'rememberMe', [
        'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
    ])->checkbox() * /?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?=AjaxSubmit::widget([
                'label'=>'Sign in',

//        'url'=>$this->id,
                'htmlOptions'=>array('id'=>"user-change-info-form-submit",'class'=>'btn btn-primary','style'=>'width:100%')
            ])?>
        </div>
    </div>

<?/*
    <div class="forgetPassword">
        <?= Html::a("Forget password"."?",array("/ubi/user/restorePasswordRequest"))?>
    </div>
* /?>
    <?php ActiveForm::end(); ?>

<script>
    setTimeout(function(){
        $("#loginForm").find("input[type='text']").focus();
    },0);
</script>
*/?>
