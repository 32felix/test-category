<?

/***
 * @var $this CController
 */
use kartik\form\ActiveForm;
use yii\helpers\Html;

?>
<div class="ubi-titled-content">
    <h3 class="title"><? echo('Відновлення паролю'); ?></h3>
    <div class="form">
        <? $form = ActiveForm::begin(['id'=>'comments-form', 'enableAjaxValidation'=>false]);?>
        <div class="row">
            <?php echo $form->field($model, 'email')->label('E-mail:'); ?>
        </div>

        <div id="successMessage" class="row"></div>

        <div class = "row buttons">
            <?= Html::submitButton('Відновити', ['id'=>"registrationButton", 'class' => 'btn btn-danger']);?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>