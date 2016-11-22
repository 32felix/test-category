<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\form\RegisterForm */

use app\components\utils\ImageUtils;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Реєстрація';
$this->params['breadcrumbs'][] = $this->title;

$image = ImageUtils::captchaBuild()

?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p style="color: red">Всі поля обов'язкові для заповнення!</p>

    <?if ($model->verifyMessage):?>
        <p style="color: red"><?= $model->verifyMessage ?></p>
    <?endif?>

    <?php $form = ActiveForm::begin([
        'id' => 'register-form',
        'options' => ['class' => 'form-vertical'],
        'fieldConfig' => [
            'template' => "<div class='row'>{label}\n</div><div class='row'><div class=\"col-lg-7\">{input}</div>\n<div class=\"col-lg-5\">{error}</div></div>",
            'labelOptions' => ['class' => 'col-lg-12 control-label'],
        ],
    ]); ?>

        <?= $form->field($model, 'name') ?>

        <?= $form->field($model, 'email') ?>

        <?= $form->field($model, 'telephone') ?>

        <?= $form->field($model, 'password')->passwordInput() ?>

        <?= $form->field($model, 'passwordRewrite')->passwordInput() ?>

        <img class="captcha-img" src="<?= $image ?>" />

        <?= Html::a('Оновити картинку', '#', ['class' => 'captcha-reload']) ?>

        <?= $form->field($model, 'verifyCode') ?>

    <div class="form-group">
        <?= Html::submitButton('Зареєструватися', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<script type="text/javascript">
    $(function () {
        $('.captcha-reload').click(function (e) {
            e.preventDefault();

            $.ajax({
                url: "/site/captcha-build",
                data: {},
                type: "POST",
                dataType: 'json'
            }).done(function (data) {
                $('.captcha-img').prop('src', data.text);
            }).fail(function (jqXHR, textStatus, errorThrown) {
                alert("Невозможно сохранить: Ошибка: " + jqXHR.status + " " + jqXHR.statusText);
            });
        })
    })
</script>