<?php
/**
 */
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
    'id' => 'email-form',
    'options' => ['class' => ''],
    'fieldConfig' => [
        'template' => "{input}\n<div class=\"col-lg-8\">{error}</div>",
        'labelOptions' => ['class' => 'col-lg-1 control-label'],
    ],
]); ?>

<?if(isset($massage) && !empty($massage)):?>
    <div class="massage">
        <?=$massage?>
    </div>
<?endif?>
<div class="row">
    <?= $form->field($model, 'email')?>
</div>
<div class="row buttons">
    <?= AjaxSubmit::widget(['label' => 'Сохранить','url'=>Yii::$app->controller->action->id,'htmlOptions'=>['id'=>"activateUserButton"]]);?>
</div>
<?php ActiveForm::end();?>
