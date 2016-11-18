<?php
/**
 * @var View $this
 * @var string $type
 * @var ProductsForm $model
 */
//$this->title = 'My Yii Application';

use app\components\utils\ImageUtils;
use app\models\form\ProductsForm;
use yii\bootstrap\ActiveForm;
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
    $w = 170;
    $h = 170;
}
elseif ($type == "salad")
{
    $this->title = 'Салати';
    $s_title = 'салату';
    $title = 'салат';
    $w = 170;
    $h = 170;
}
elseif ($type == "drink")
{
    $this->title = 'Напої';
    $s_title = 'напою';
    $title = 'напій';
    $w = 170;
    $h = 170;
}
elseif ($type == "popcorn")
{
    $this->title = 'Попкорн';
    $s_title = 'попкорну';
    $title = 'попкорн';
    $w = 170;
    $h = 170;
}

$this->title = $main . ' ' . $title;

?>
<div class="project-create">
    <h1><?= $this->title ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->hiddenInput() ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'data-type' => $model->type])->label('Назва '.$s_title) ?>

    <?= $form->field($model, 'ingredients')->textInput(['maxlength' => true])->label('Інгредієнти '.$s_title) ?>

    <?= $form->field($model, 'imageId')->hiddenInput()->label('Встановити картинку '.$s_title); ?>
    <label class="image-main" style="margin-bottom: 10px; cursor:pointer;">
        <?php if( ! isset($model->imageId) || empty($model->imageId)){
            echo Html::img(Url::to('/images/no-photo.png'), ['id' => 'image-product']);
        } else {
            echo Html::img(Url::to(ImageUtils::genImageUrl($model->imageId, null, $w, $h)), ['id' => 'image-product']);
        }
        ?>

        <?= Html::fileInput('image-jpg'); ?>
    </label>
    <? for ($i = 0; $i < 15; $i++):?>

        <div class="form-group <?=(empty($model->size[$i])?'hidden':'')?>">

            <?=$form->field($model, "size[$i]", ['options' => ['style' => 'width: 32%; float: left; padding-right: 2%;']])
                ->label("Розмір/Вага ".$s_title)
                ->textInput(['value' => (isset($model->size[$i])?$model->size[$i]:'')]);?>

            <?=$form->field($model, "price[$i]", ['options' => ['style' => 'width: 32%; float: left;  padding-right: 2%;']])
                ->label("Ціна ".$s_title." в грн")
                ->textInput(['value' => (isset($model->price[$i])?$model->price[$i]:'')]);?>

            <?=$form->field($model, "countMen[$i]", ['options' => ['style' => 'width: 32%; float: left;  padding: 0;']])
                ->label("Кількість людей")
                ->textInput(['value' => (isset($model->countMen[$i])?$model->countMen[$i]:'')]);?>

            <?if (isset($model->size[$i])):?>
                <?= Html::a('X', ['delete-price', 'size' => $model->size[$i], 'productId' => $model->id, 'type' => $model->type], [
                    'class' => 'btn delete-size',
                    'style' => 'margin: 24px 0 9px',
                    'data' => [
                        'confirm' => 'Ви впевнені, що хочете видалити цей розмір?',
                        'method' => 'post',
                    ],
                ]) ?>
            <?else:?>
                <div class="btn delete-product-size" data-id="<?=$i?>" style="margin: 24px 0 9px;">X</div>
            <?endif;?>
        </div>

    <?endfor;?>
    <div class="form-group">
        <?= Html::submitButton('Добавити розмір', ['class' => 'btn info bonus-from-value']) ?>
    </div>
    <div class="form-group">
        <?= Html::submitButton($main, ['class' =>'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
    $(function () {
        $('.form-group').on('click', '.bonus-from-value', function (e) {
            e.preventDefault();
            var form = $('.form-group');
            form.siblings('.hidden:eq(0)').removeClass('hidden');
        });

        $('.form-group').on('click', '.delete-product-size', function () {
            var form = $('.form-group'),
                index = $(this).attr('data-id');
            form.children('.field-productsform-size-'+index).addClass('hidden');
            form.children('.field-productsform-size-'+index).find('input').val('');
            form.children('.field-productsform-price-'+index).addClass('hidden');
            form.children('.field-productsform-price-'+index).find('input').val('');
            form.children('.field-productsform-countmen-'+index).addClass('hidden');
            form.children('.field-productsform-countmen-'+index).find('input').val('');
            form.children('.field-productsform-countmen-'+index).next().addClass('hidden');
        });

        $('input[name="image-jpg"]').on('change', function () {
            var type = $('#productsform-name').attr('data-type'),
                id = $('#productsform-id').val(),
                src = $('#image-product').attr('src');

            $('#image-product').attr('src', '/images/load.gif');

            var data = new FormData();
            $.each( this.files, function( key, value )
            {
                data.append(key, value);
            });
            data.append('type', type);
            data.append('id', id);
            data.append('w', <?= $w ?>);
            data.append('h', <?= $h ?>);

            $.ajax({
                url: '/image/create-image',
                type: 'POST',
                data: data,
                cache: false,
                dataType: 'json',
                processData: false,
                contentType: false
            }).done(function (data) {
                if (data.error == "none")
                {
                    $('#productsform-imageid').val(data.id);
                    $('#image-product').attr('src', data.src);
                }
                else
                {
                    alert(data.error);
                    $('#image-product').attr('src', src);
                }
            }).fail(function (jqXHR, textStatus, errorThrown) {
                alert("Невозможно загрузить: Ошибка: " + jqXHR.status + " " + jqXHR.statusText);
                $('#image-product').attr('src', src);
            });
        });
    })
</script>