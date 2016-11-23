<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\form\LoginForm */

use nodge\eauth\Widget;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Вхід';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form-vertical'],
        'fieldConfig' => [
            'template' => "<div class='row'>{label}\n</div><div class='row'><div class=\"col-lg-4\">{input}</div>\n<div class=\"col-lg-8\">{error}</div></div>",
            'labelOptions' => ['class' => 'col-lg-12 control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'email')->textInput() ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

    <?= $form->field($model, 'rememberMe')->checkbox([
        'template' => "<div class='row'><div class='col-lg-4'>{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div></div>",
    ]) ?>

    <div class="form-group">
        <?= Html::a("Забули пароль?", \yii\helpers\Url::to('/restore-password-request')) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton("Ввійти", ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <div class="body-social-content">
        <span>Ви можете використати соціальну мережу для входу на сайт:</span>
        <?= Widget::widget(array('id'=>'login-eauth','action' => '/site/login-by-eauth', 'predefinedServices' => ['vkontakte', 'ok', 'mailru', 'facebook'])); ?>
    </div>

</div>
