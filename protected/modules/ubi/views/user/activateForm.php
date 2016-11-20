<?php

use tit\widgets\AjaxSubmit;
use yii\widgets\ActiveForm;
use tit\ubi\model\GlobalUsers;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var GlobalUsers $model
 */
?>

<?php $form = ActiveForm::begin([
    'id' => 'developer-form',
    'options' => ['class' => ''],
    'fieldConfig' => [
        'template' => "{input}\n<div class=\"col-lg-8\">{error}</div>",
        'labelOptions' => ['class' => 'col-lg-1 control-label'],
    ],
]); ?>

<div class="row">
    <?= $form->field($model, 'name')->textInput(['placeholder' => 'Name'])?>
</div>
<div class="row">
    <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Password'])?>
    <?= $form->field($model, 'user')->hiddenInput(['value'=>$model->id])?>
    <?= $form->field($model, 'accessCode')->hiddenInput(['value'=>$model->accessCode])?>
</div>

<div id="successMessage" class="row">
<!--    --><?//=\tit\utils\helpers\nempydef($successMessage,"")?>
</div>
<div class="row buttons">
    <?= AjaxSubmit::widget(['label' => 'Сохранить','url'=>Yii::$app->controller->action->id,'htmlOptions'=>['id'=>"activateUserButton"]]);?>
</div>
<?php ActiveForm::end();?>
