<?php
/**
 * @var $this yii\web\View
 * @var $error
 */
use tit\widgets\AjaxSubmit;
use yii\captcha\Captcha;
use yii\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin(['id' => 'user-registration']);?>
    <div style="width: 400px;margin: 0 auto;">
        <div class = "row">
            <?= $form->field($model, 'email') ?>
        </div>
        <div class = "row">
            <?=$form->field($model, 'verifyCode')->widget(
                Captcha::className(),
                [
                    'captchaAction' => '/ubi/user/captcha',
                    'options' => ['class' => 'form-control'],
                    'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-9">{input}</div></div>',
                ]);
            ?>
        </div>
        <div id="successMessage" class="row"></div>
        <div class = "row buttons">
            <?= AjaxSubmit::widget(['label' => Yii::t('app','Sign up'),'url'=>"/ubi/user/register",'htmlOptions'=>['id'=>"registrationButton"]]);?>
        </div>
    </div>
<?php ActiveForm::end(); ?>
