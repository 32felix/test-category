<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ertong
 * Date: 8/16/13
 * Time: 8:50 PM
 * To change this template use File | Settings | File Templates.
 */
use tit\ubi\widgets\ChangePassFormWidget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var $this ChangePassFormWidget
 * @var $form ActiveForm
 */
?>
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
    <?= $form->field($model, 'oldPass')->passwordInput()?>
</div>
<div class="row">
    <?= $form->field($model, 'newPass')->passwordInput()?>
</div>
<div class="row">
    <?= $form->field($model, 'newPassRepeat')->passwordInput()?>
</div>

<div id="successMessage">
</div>
<div class="row buttons">
    <div class="col-lg-offset-3">
        <?=Html::submitButton('Змінити', ['id'=>"changePasswordButton",'class'=>'btn btn-primary','style'=>'width:100%'])?>
    </div>
</div>
<?php ActiveForm::end(); ?>
