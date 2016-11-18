<?
/**
 * @var yii\web\View $this
 */
use nodge\eauth\Widget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="popup-login" xmlns="http://www.w3.org/1999/html">
    <div class="screen close-popup"></div>
    <div class="body">
        <div class="body-border">
            <div class="body-title-login">
                <div class="body-title-enter">
                    <a>вход</a>
                </div>
                <div class="body-title-register">
                    <a>регистрация</a>
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
                            <a>Забыли пароль?</a>
                        </div>
<!--                    </form>-->
                    <? ActiveForm::end(); ?>
                </div>
                <div class="body-content-social">
                    <div class="body-social-content">
                        <?= Widget::widget(array('action' => 'user/login')); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="close close-popup"></div>
    </div>
</div>
