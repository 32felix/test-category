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

    <div class="form-group">
        <?= Html::submitButton($main, ['class' =>'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<div class="order-items">
    <? foreach ($orders as $order): ?>
        <div class="order-item" data-id="<?= $order['id'] ?>">
            <div class="order-title">
                <div><?= $order['name'] . ($order['size']?'<span>'.$order['size'].'</span>':'') ?></div>
                <div><?= sprintf('%.2f', $order['price']) ?> грн</div>
            </div>
            <div>
                <div class="count-bucket">
                    <img class="bucket-minus" src="/images/header/bucket-count.png" />
                    <span><?= $order['count'] ?></span>
                    <img class="bucket-plus" src="/images/header/bucket-count.png" />
                </div>
            </div>
            <div class="sum"><?= sprintf('%.2f', ($order['price'] * $order['count'])) ?> грн</div>
            <div>
                <div class="delete-bucket">
                    <img src="/images/header/bucket-delete.png" />
                </div>
            </div>
        </div>
    <? endforeach; ?>
</div>

<script>
    $('.order-items').on('click', '.count-bucket img', function () {
        var $this = $(this),
            id = $this.closest('.order-item').attr('data-id'),
            factor,
            count = $this.siblings('span').text(),
            countSpan = $('.bucket-hidden .product-bucket[data-id="'+id+'"]').find('.count-bucket span');

        if ($this.attr('class') == 'bucket-minus')
        {
            factor = -1;
        }
        else
        {
            factor = 1;
        }

        if (count != 1 || factor != -1)
        {


            $.ajax({
                url: "/orders/add-count",
                data: {
                    id: id,
                    factor: factor
                },
                type: "POST",
                dataType: 'json'
            }).done(function (data) {
                if (data.error)
                {
                    alert(data.error);
                }
                else if (data.status=="ok") {
                    $this.siblings('span').text(data.count);
                    countSpan.text(data.count);
                    countSpan.closest('.bucket-hidden').find('.sum-bucket .pull-right').text(data.sum+' грн');
                    countSpan.closest('.bucket').siblings('.costs').text('сума '+data.sum+' грн');
                    $this.closest('.order-item').find('.sum').text(data.sumItem+' грн');
                }
            }).fail(function (jqXHR, textStatus, errorThrown) {
                alert("Невозможно сохранить: Ошибка: " + jqXHR.status + " " + jqXHR.statusText);
            });
        }
    });

    $('.order-items').on('click', '.delete-bucket img', function () {
        var $this = $(this),
            id = $this.closest('.order-item').attr('data-id'),
            countSpan = $('.bucket-hidden .product-bucket[data-id="'+id+'"]').find('.count-bucket span');

        $.ajax({
            url: "/orders/delete-price-id",
            data: {
                id: id
            },
            type: "POST",
            dataType: 'json'
        }).done(function (data) {
            if (data.error)
            {
                alert(data.error);
            }
            else if (data.status=="ok")
            {
                countSpan.closest('.bucket-hidden').find('.sum-bucket .pull-right').text(data.sum+' грн');
                countSpan.closest('.bucket').siblings('.costs').text('сума '+data.sum+' грн');

                $this.closest('.order-item').remove();

                if (data.close == 'one')
                    countSpan.closest('.product-bucket').remove();
                else
                {
                    countSpan.closest('.bucket-hidden').siblings('div').removeClass('full');
                    countSpan.closest('.products-bucket').siblings('div').remove();
                    countSpan.closest('.products-bucket').replaceWith('<p>Кошик пустий</p>');
                }
            }
        }).fail(function (jqXHR, textStatus, errorThrown) {
            alert("Невозможно сохранить: Ошибка: " + jqXHR.status + " " + jqXHR.statusText);
        });
    });
</script>