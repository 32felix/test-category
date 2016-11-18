<?php
/**
 * @var $this UserController
 * @var $model User
 * @var $form ActiveForm
 */
use tit\widgets\AjaxSubmit;
use yii\widgets\ActiveForm;

?>

<? $form = ActiveForm::begin(['id'=>'comments-form', 'enableAjaxValidation'=>false]);?>
    <div class="row">
        <?php echo $form->field($model, 'email')->label('E-mail:'); ?>
    </div>

    <div id="successMessage" class="row"></div>

    <div class = "row buttons">
        <?= AjaxSubmit::widget(['label' => 'Send', 'url'=>\Yii::$app->controller->action->id, 'htmlOptions'=>['id'=>"registrationButton"]]);?>
    </div>
<?php ActiveForm::end(); ?>

