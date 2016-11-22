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
        
        <?php echo $form->field($model, 'email')->label('E-mail:'); ?>

        <div id="successMessage" class="row"></div>

        <div class = "buttons">
            <?= Html::submitButton('Відновити', ['id'=>"registrationButton", 'class' => 'btn btn-danger']);?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>