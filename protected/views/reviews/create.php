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


$this->title = $main . ' відгуку';

?>
<div class="reviews-create">
    <h1><?= $this->title ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->hiddenInput() ?>

    <?= $form->field($model, 'userName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'review')->textarea(['maxlength' => true]) ?>

    <?= $form->field($model, 'imageId')->hiddenInput()->label('Встановити картинку користувача'); ?>
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
            var type = $('#reviewsform-name').attr('data-type'),
                id = $('#reviewsform-id').val();

            var data = new FormData();
            $.each( this.files, function( key, value )
            {
                data.append(key, value);
            });
            data.append('type', 'review');
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

                $('#reviewsform-imageid').val(data.id);
                $('#image-product').attr('src', data.src)

            }).fail(function (jqXHR, textStatus, errorThrown) {
                alert("Невозможно загрузить: Ошибка: " + jqXHR.status + " " + jqXHR.statusText);
            });
        });
    })
</script>