<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Контакти';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

        <div class="alert alert-success">
            Дякуємо Вам за звернення до нас. Ми відповімо вам як можна швидше.
        </div>

    <?php else: ?>

        <? if ($text): ?>
            <p><?= $text ?></p>
        <? endif; ?>

        <div class="row">
            <div class="col-lg-5">

                <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                    <?= $form->field($model, 'name')->textInput(['autofocus' => true])->label("Ім'я") ?>

                    <?= $form->field($model, 'email') ?>


                    <?= $form->field($model, 'body')->textArea(['rows' => 6])->label("Повідомлення") ?>

                    <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                        'template' => '<div><div>{image}</div><br><div>{input}</div></div>',
                    ])->label("Код перевірки") ?>

                    <div class="form-group">
                        <?= Html::submitButton('Вілправити', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                    </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>

    <?php endif; ?>
</div>
