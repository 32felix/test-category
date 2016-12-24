<?php
/**
 * @var View $this
 * @var array $model
 * @var string $type
 */
//$this->title = 'My Yii Application';

use app\components\utils\ImageUtils;
use app\models\form\ProductsForm;
use app\models\Images;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
if ($type == "pizza")
{
    $this->title = 'Піца';
    $s_title = 'піци';
    $title = 'піцу';
    $w = 170;
    $h = 170;
}
elseif ($type == "kit")
{
    $this->title = 'Набори';
    $s_title = 'набору';
    $title = 'набір';
    $w = 262;
    $h = 195;
}
elseif ($type == "salad")
{
    $this->title = 'Салати';
    $s_title = 'салату';
    $title = 'салат';
    $w = 225;
    $h = 145;
}
elseif ($type == "drink")
{
    $this->title = 'Напої';
    $s_title = 'напою';
    $title = 'напій';
    $w = 115;
    $h = 245;
}
elseif ($type == "popcorn")
{
    $this->title = 'Попкорн';
    $s_title = 'попкорну';
    $title = 'попкорн';
    $w = 150;
    $h = 200;
}

?>

<script>
    $(function () {
        $('input[type="radio"]').each(function () {
            $(this).siblings('.radio-button').toggleClass('checked', this.checked);
            if (this.checked)
            {
                var size = $(this).closest('.product-size'),
                    id = $(this).siblings('.radio-button').attr('data-id');
                size.siblings('.product-buy').find('.price').css({'display': 'none'});
                size.siblings('.product-buy').find('.price-'+id).css({'display': 'inline-block'});
            }
        });
        $(".product-size").on("change", 'input[type="radio"]', function () {
            var size = $(this).closest('.product-size'),
                id = $(this).siblings('.radio-button').attr('data-id');
            size.find('.radio-button').removeClass('checked');
            $(this).siblings('.radio-button').toggleClass('checked', this.checked);
            size.siblings('.product-buy').find('.price').css({'display': 'none'});
            size.siblings('.product-buy').find('.price-'+id).css({'display': 'inline-block'});
        });

        $('.buy').click(function () {
            var $this = $(this),
                html = $this.html(),
                product=$this.closest('.product'),
                id = $this.attr('id'),
                sizes = product.find('.radio-button.checked').attr('data-size');
            $this.attr('disabled', 'disabled');
            $.ajax({
                url: "/orders/add-price-id",
                data: {
                    sizes: sizes,
                    productId: product.attr('data-id')
                },
                type: "POST",
                dataType: 'json'
            }).done(function (data) {
                $this.attr('disabled', null);

                if (data.error !== "")
                {
                    alert(data.error);
                    $this.html(html);
                }

                else if (data.status=="ok")
                {
                    $this.html('Хочу ще');
                    $this.addClass('select');
                    $('#bucket').addClass('full');

                    if (data.add == 'create')
                    {
                        $('.bucket-hidden').find('p').remove();
                        $('.bucket-hidden').append("<div class='products-bucket'></div>" +
                            "<div class='sum-bucket'><p class='pull-left'>Сума:</p><p class='pull-right'>0 грн</p></div>"+
                            "<div class='order-bucket'><a href='<?= Url::toRoute('/orders/create') ?>'>Оформити замовлення</a></div>")
                    }

                    if (data.add == 'add' || data.add == 'create')
                    {
                        $('.bucket-hidden').find('.products-bucket').append("<div class='product-bucket' data-id='"+data.orderId+"'>"+
                            "<div class='bucket-title'><span>"+data.name+(data.size?"<br>"+data.size:"")+"</span><br>"+data.price+" грн"+
                            "</div>" +
                            "<div><div class='count-bucket'><img class='bucket-minus' src='/images/header/bucket-count.png' />" +
                            "<span>"+data.count+"</span><img class='bucket-plus' src='/images/header/bucket-count.png' /></div>" +
                            "</div><div><div class='delete-bucket'><img src='/images/header/bucket-delete.png' /></div></div></div>")
                    }
                    else
                    {
                        $('.bucket-hidden').find('.product-bucket[data-id="'+data.orderId+'"]').find('.count-bucket span').text(data.count)
                    }

                    $('.bucket-hidden').find('.sum-bucket .pull-right').text(data.sum+' грн');
                    $('.bucket').siblings('.costs').text('сума '+data.sum+' грн');
                }
            }).fail(function (jqXHR, textStatus, errorThrown) {
                alert("Невозможно добавить: Ошибка: " + jqXHR.status + " " + jqXHR.statusText);
                $this.html(html);
                $this.attr('disabled', null);
            });
        })
    })
</script>

<? $i=0 ?>
<div class="products <?= $type ?>">
    <? foreach ($model as $form):
        /**@var ProductsForm $form*/?>

        <? if ($i > 0 && $i % 3 == 0): ?>
            </div><div class="products <?= $type ?>">
        <? endif; ?>

        <div class="product" data-id="<?= $form->id ?>">
            <div class="title-product">
                <?= $form->name; ?>
            </div>

            <div class="img">
                <? $img = Images::findOne($form->imageId) ?>
                <img src="<?= $img?ImageUtils::genImageUrl($img->id, $img->timeUpdate, $w, $h):''?>">
            </div>

            <div class="desc">
                (<?= $form->ingredients; ?>)
            </div>
            <div class="product-size">
                <? if (count($form->size) > 1):?>
                    <? $j = 0;
                    foreach ($form->size as $value):?>
                    <?= Html::radio($form->id, $j == 0, [
                            "label" => "<div class='radio-button' data-id='$j' data-size='$value'>$value</div>",
                        ]) ?>
                        <? $j++;
                    endforeach; ?>
                <? elseif ($form->size): ?>
                    <div class='radio-button checked' data-size='<?= $form->size[0] ?>'><?= $form->size[0] ?></div>
                <? endif; ?>
            </div>
            <div class="product-buy">
                <? foreach ($form->price as $index=>$value):?>
                    <div class="price price-<?= $index ?>">
                        <?= $value." <span>грн</span>" ?>
                    </div>
                <? endforeach; ?>
                <div class="buy">Замовити</div>
            </div>
        </div>

    <?$i++;
    endforeach; ?>
</div>
