<?php
/**
 * @var View $this
 * @var PartnersForm $model
 */

use app\components\utils\ImageUtils;
use app\models\form\PartnersForm;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

$paramKey = [
    'mainPage' => 'Головна сторінка',
    'delivery' => 'Доставка',
    'contact' => 'Контакти',
    'phone' => 'Телефон у верху сторінок',
    'work' => 'Термін роботи',
];

$this->title = $main . ' тексту на сторінці "'.$paramKey[$model['key']].'"';

?>
<div class="reviews-create">
    <h1><?= $this->title ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <div style="height: 0;">
        <?= $form->field($model, 'id')->hiddenInput() ?>
        <?= $form->field($model, 'key')->hiddenInput()->label('') ?>
    </div>

    <?= $form->field($model, 'value')->textarea(['maxlength' => true, 'rows' => 10, 'style' => 'resize: vertical']) ?>

    <div class="form-group">
        <?= Html::submitButton($main, ['class' =>'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>