<?
/**
 * @var yii\web\View $this
 */
use kartik\form\ActiveForm;
use yii\helpers\Html;

?>
<div class="ubi-titled-content">
    <h3 class="title"><?="Відновлення паролю"?></h3>
    <div class="form">
        <?php $form = ActiveForm::begin(
            [
                'options' => ['class' => 'form-horizontal'],
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"col-lg-6\">{input}</div>\n<div class=\"col-lg-4\">{error}</div>",
                    'labelOptions' => ['class' => 'col-lg-3 control-label'],
                ],
            ]);
        ;?>
        <div class="row">
            <?= $form->field($model, 'password')->passwordInput()?>
        </div>
        <div class="row">
            <?= $form->field($model, 'rewritePassword')->passwordInput()?>
        </div>

        <div id="successMessage" class="row">
        </div>
        <div class="row buttons">
            <div class="col-lg-offset-3">
                <?=Html::submitButton('Змінити', ['id'=>"changePasswordButton",'class'=>'btn btn-primary col-lg-offset-3'])?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
