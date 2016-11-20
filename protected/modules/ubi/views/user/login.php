<?/*Стара форма логіна*/?>
<?php
//use nodge\eauth\Widget;
//use yii\helpers\Html;
//use yii\widgets\ActiveForm;
//
///**
// * @var yii\web\View $this
// * @var yii\widgets\ActiveForm $form
// * @var \tit\ubi\model\form\LoginForm $model
// */
//$this->title = 'Login';
//$this->params['breadcrumbs'][] = $this->title;
//?>
<!--<div class="site-login">-->
<!--    <h1>--><?//=Yii::t('app',Html::encode($this->title))?><!--</h1>-->
<!---->
<!--    --><?php
//    if (Yii::$app->getSession()->hasFlash('error')) {
//        echo '<div class="alert alert-danger">'.Yii::$app->getSession()->getFlash('error').'</div>';
//    }
//    ?>
<!--    <p class="lead">--><?//=Yii::t('app','Do you already have an account on one of these sites? Click the logo to log in with it here')?><!--:</p>-->
<!--    --><?php //echo Widget::widget(array('action' => 'user/login')); ?>
<!---->
<!---->
<!--    <p>--><?//=Yii::t('app','Please fill out the following fields to login')?><!-- :</p>-->
<!---->
<!--    --><?php //$form = ActiveForm::begin([
//        'id' => 'login-form',
//        'options' => ['class' => 'form-horizontal'],
//        'fieldConfig' => [
//            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
//            'labelOptions' => ['class' => 'col-lg-1 control-label'],
//        ],
//    ]); ?>
<!---->
<!--    --><?//= $form->field($model, 'login') ?>
<!---->
<!--    --><?//= $form->field($model, 'password')->passwordInput() ?>
<!---->
<!--    --><?//= $form->field($model, 'rememberMe', [
//        'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
//    ])->checkbox() ?>
<!---->
<!--    <div class="form-group">-->
<!--        <div class="ubi-register">-->
<!--            --><?//=Html::a(Yii::t('app','register'),['/ubi/user/register'])?>
<!--        </div>-->
<!--        <div class="col-lg-offset-1 col-lg-11">-->
<!--            --><?//= Html::submitButton(Yii::t('app','enter'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
<!--        </div>-->
<!--    </div>-->
<!---->
<!--    --><?php //ActiveForm::end(); ?>
<!---->
<!--    <div class="col-lg-offset-1" style="color:#999;">-->
<!--        You may login with <strong>admin/admin</strong> or <strong>demo/demo</strong>.<br>-->
<!--        To modify the username/password, please check out the code <code>app\models\User::$users</code>.-->
<!--    </div>-->
<!--</div>-->

<?
/**
 * @var yii\web\View $this
 */
use nodge\eauth\Widget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="popup-login" xmlns="http://www.w3.org/1999/html">
        <div class="body-border">
            <div class="body-title-login">
                <div class="body-title-enter">
                    <a>вход</a>
                </div>
                <div class="body-title-register">
                    <a href="/user/registration">регистрация</a>
                </div>
            </div>
            <div class="body-content-login">
                <div class="body-content-form">
                    <!--                    <form>-->
                    <? $form = ActiveForm::begin(['action'=>'/user/login']); ?>
                    <!--                        <label>Ваш email</label>-->
                    <!--                        <input type="text" name="--><?//=$model->formName()?><!--[login]" />-->
                    <?= $form->field($model, 'login')->textInput()->label('Ваш email')?>
                    <?= $form->field($model, 'password')->passwordInput()->label('Ведите свой пароль')?>

                    <!--                        <label>Ведите свой пароль</label>-->
                    <!--                        <input type="password" name="--><?//=$model->formName()?><!--[password]">-->

                    <div class="popup-login-wrap">
                        <!--                        <a class="body-button">Войти</a>-->
                        <?= Html::submitButton('Войти', ['class' => 'popup-login-button', 'name' => 'login-button']) ?>
                        <?/*<a>Забыли пароль?</a>*/?>
                    </div>
                    <!--                    </form>-->
                    <? ActiveForm::end(); ?>
                </div>
                <div class="body-content-social">
                    <div class="body-social-content">
                        <?= Widget::widget(array('action' => 'user2/login-by-eauth')); ?>
                    </div>
                </div>
            </div>
        </div>
</div>
<script>
    window.oauth_result=function(result)
    {
        window.location=window.location;
    }
</script>