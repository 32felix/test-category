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

if ($type == "share")
{
    $this->title = 'Акції';
    $s_title = 'акції';
    $title = 'акцію';
}
elseif ($type == "partner")
{
    $this->title = 'Партнери';
    $s_title = 'партнера';
    $title = 'партнера';
}
elseif ($type == "new")
{
    $this->title = 'Новини';
    $s_title = 'новини';
    $title = 'новину';
}

$this->title = $main . ' ' . $title;

?>
<div class="project-create">
    <h1><?= $this->title ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->hiddenInput() ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'data-type' => $model->type])->label('Назва '.$s_title) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true])->label('Опис '.$s_title) ?>

    <?= $form->field($model, 'imageId')->hiddenInput()->label('Встановити картинку '.$s_title); ?>
    <label class="image-main" style="margin-bottom: 10px; cursor:pointer;">
        <?php if( ! isset($model->imageId) || empty($model->imageId)){
            echo Html::img(Url::to('/images/no-photo.png'), ['id' => 'image-product']);
        } else {
            echo Html::img(Url::to(ImageUtils::genImageUrl($model->imageId, null, 200, 130), ['id' => 'image-product']));
        }
        ?>

        <?= Html::fileInput('image-jpg'); ?>
    </label>
    <div class="form-group">
        <?= Html::submitButton($main, ['class' =>'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
    $(function () {
        $('input[name="image-jpg"]').on('change', function () {
            var type = $('#servicesform-name').attr('data-type'),
                id = $('#servicesform-id').val();

            var data = new FormData();
            $.each( this.files, function( key, value )
            {
                data.append(key, value);
            });
            data.append('type', type);
            data.append('id', id);

            $.ajax({
                url: '/image/create-image',
                type: 'POST',
                data: data,
                cache: false,
                dataType: 'json',
                processData: false,
                contentType: false
            }).done(function (data) {

                $('#servicesform-imageid').val(data.id);
                $('#image-product').attr('src', data.src)

            }).fail(function (jqXHR, textStatus, errorThrown) {
                alert("Невозможно загрузить: Ошибка: " + jqXHR.status + " " + jqXHR.statusText);
            });
        });
    })
</script>