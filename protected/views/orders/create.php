<?php
/**
 * @var View $this
 * @var OrdersForm $model
 */

use app\models\form\OrdersForm;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

$this->title = $main . ' замовлення';

?>
<div class="order-create">
    <h1><?= $this->title ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'userId')->textInput(['maxlength' => true]); ?>

    <?= $form->field($model, 'telephone')->textInput(['maxlength' => true]); ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]); ?>

    <?= $form->field($model, 'addressId')->textInput(['maxlength' => true]); ?>

    <?/* for ($i = 0; $i < 25; $i++):*/?><!--

        <div class="form-group <?/*=(empty($model->productPriceId[$i])?'hidden':'')*/?>">

            <?/*=$form->field($model, "productPriceId[$i]", ['options' => ['style' => 'width: 32%; float: left; padding-right: 2%;']])
                ->label("Замовлений продукт")
                ->textInput(['value' => (isset($model->productPriceId[$i])?$model->productPriceId[$i]:'')]);*/?>

            <?/*=$form->field($model, "count[$i]", ['options' => ['style' => 'width: 32%; float: left;  padding-right: 2%;']])
                ->label("Кількість")
                ->textInput(['value' => (isset($model->count[$i])?$model->count[$i]:'')]);*/?>

            <?/*if (isset($model->productPriceId[$i])):*/?>
                <?/*= Html::a('X', ['delete-price-id', 'productPriceId' => $model->productPriceId[$i], 'orderId' => $model->id], [
                    'class' => 'btn delete-size',
                    'style' => 'margin: 24px 0 9px',
                    'data' => [
                        'confirm' => 'Ви впевнені, що хочете видалити цей продукт?',
                        'method' => 'post',
                    ],
                ]) */?>
            <?/*else:*/?>
                <div class="btn delete-product-size" data-id="<?/*=$i*/?>" style="margin: 24px 0 9px;">X</div>
            <?/*endif;*/?>
        </div>

    <?/*endfor;*/?>
    <div class="form-group">
        <?/*= Html::submitButton('Добавити розмір', ['class' => 'btn info bonus-from-value']) */?>
    </div>-->
    <div class="form-group">
        <?= Html::submitButton($main, ['class' =>'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<!--<script>
    $(function () {
        $('.form-group').on('click', '.bonus-from-value', function (e) {
            e.preventDefault();
            var form = $('.form-group');
            form.siblings('.hidden:eq(0)').removeClass('hidden');
        });

        $('.form-group').on('click', '.delete-product-size', function () {
            var form = $('.form-group'),
                index = $(this).attr('data-id');
            form.children('.field-ordersform-productpriceid-'+index).addClass('hidden');
            form.children('.field-ordersform-productpriceid-'+index).find('input').val('');
            form.children('.field-ordersform-count-'+index).addClass('hidden');
            form.children('.field-ordersform-count-'+index).find('input').val('');
            form.children('.field-ordersform-count-'+index).next().addClass('hidden');
        })
    })
</script>-->