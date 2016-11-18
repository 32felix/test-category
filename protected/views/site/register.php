<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\form\RegisterForm */

use Gregwar\Captcha\CaptchaBuilder;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Реєстрація';
$this->params['breadcrumbs'][] = $this->title;

$builder = new CaptchaBuilder;
$builder->build();
Yii::$app->cache->set('captcha-register', $builder->getPhrase())

?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p style="color: red">Всі поля обов'язкові для заповнення!</p>

    <?php $form = ActiveForm::begin([
        'id' => 'register-form',
        'options' => ['class' => 'form-vertical'],
        'fieldConfig' => [
            'template' => "<div class='row'>{label}\n</div><div class='row'><div class=\"col-lg-4\">{input}</div>\n<div class=\"col-lg-8\">{error}</div></div>",
            'labelOptions' => ['class' => 'col-lg-12 control-label'],
        ],
    ]); ?>

        <?= $form->field($model, 'name') ?>

        <?= $form->field($model, 'email') ?>

        <?= $form->field($model, 'telephone') ?>

        <?= $form->field($model, 'password')->passwordInput() ?>

        <?= $form->field($model, 'passwordRewrite')->passwordInput() ?>

        <img src="<?php echo $builder->inline(); ?>" />

        <?= $form->field($model, 'verifyCode') ?>

    <div class="form-group">
            <div class="col-lg-12">
                <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
</div>
