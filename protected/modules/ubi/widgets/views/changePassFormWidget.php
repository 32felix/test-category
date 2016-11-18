<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ertong
 * Date: 8/16/13
 * Time: 8:50 PM
 * To change this template use File | Settings | File Templates.
 */
use tit\ubi\model\form\ChangePassForm;
use tit\ubi\widgets\ChangePassFormWidget;
use tit\widgets\AjaxSubmit;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * @var $this ChangePassFormWidget
 * @var $form ActiveForm
 */
?>
<?php $form = ActiveForm::begin() ?>

<?if ($model->scenario== ChangePassForm::SCENARIO_CHANGE):?>
        <?= $form->field($model, 'oldPass')->passwordInput()?>
<?endif;?>
    <?= $form->field($model, 'newPass')->passwordInput()?>
    <?= $form->field($model, 'newPassRepeat')->passwordInput()?>

<div id="successMessage" class="row">
    <?= issetdef($message) ?>
</div>
<div class="for-button">
    <?= \tit\widgets\AjaxSubmit::widget([
        "id" => "ubi-change-password-widget-submit1",
        "url" => Url::to(["/ubi/user/change-password"]),
        "htmlOptions" => ["class" => ""],
        "label" => "Змінити",
    ]);
    ?>
</div>
<?php ActiveForm::end(); ?>