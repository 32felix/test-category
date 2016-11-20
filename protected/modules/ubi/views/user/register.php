<?//
///**
// * @var yii\web\View $this
// */
//use nodge\eauth\Widget;
//use tit\ubi\widgets\RegisterFormWidget;
//?>
<!--<div class="ubi-titled-content">-->
<!--    <div class="title">--><?//=Yii::t('app','Sign up')?><!--</div>-->
<!--    <div class="form">-->
<!--        --><?//= RegisterFormWidget::widget(["model"=>$model]);?>
<!--    </div>-->
<!--    <div class="ubi-auth-services">-->
<!--        <span class="services-title">Sign up using</span>-->
<!--        --><?//= Widget::widget(array('action' => 'user/login')); ?>
<!--    </div>-->
<!--</div>-->
<?
/**
 * @var yii\web\View $this
 */
use nodge\eauth\Widget;
use yii\captcha\Captcha;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="popup-login" xmlns="http://www.w3.org/1999/html">
    <div class="body-border">
        <div class="body-title-login">
            <div class="body-title-enter-re">
                <a href="/user/login">вход</a>
            </div>
            <div class="body-title-register-re">
                <a>регистрация</a>
            </div>
        </div>
        <div class="body-content-login">
            <div class="body-content-form">
                <? $form = ActiveForm::begin([
                    'action'=>'/ubi/user/register',

                ]);?>
                <div class="row"><?= $form->field($model, 'email')->label('Ваш email') ?></div>
                <div class="row">
                    <div class="ubi-kapza">
                        <?=$form->field($model, 'verifyCode')->widget(Captcha::class, [
                            'captchaAction' => '/ubi/user/captcha',
                            'options' => ['class' => 'form-item req'],
                            'template' => '
                                        <div class="row">
                                            <p>{image}<a href="#" class="symbols-refresh">Получить новый код</a></p>
                                        </div>
                                        <div class="row"> <label>Введите текст с картинки</label> </div>
                                        <div class="row">
                                            <div class="kod">{input}</div>
                                        </div>',
                        ])->label(false); //->label('Введите текст с картинки'); ?>
                    </div>
                </div>
                <div class="row"><?= Html::submitButton('Регистрация', ["class" => "popup-login-button", "name" => "login-button"]) ?></div>
                <!--                    </form>-->
                <? ActiveForm::end(); ?>
            </div>
            <div class="body-content-social">
                <div class="body-social-content">
                    <?= Widget::widget(array('action' => 'user/registration')); ?>
                </div>
            </div>
        </div>
    </div>
</div>

