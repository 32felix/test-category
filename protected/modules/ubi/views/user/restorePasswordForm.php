<?php
/**
 * @var UserController $this
 * @var GlobalUser $model
 * @var CActiveForm $form
 * @var integer $return
 */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'developer-form',
    'enableAjaxValidation'=>false,
)); ?>
<?//php echo $form->errorSummary($model); ?>

<div class="row">
    <?php echo $form->labelEx($model,'password'); ?>
    <?php echo $form->passwordField($model,'password',array('value'=>null)); ?>
    <?php echo $form->error($model,'password'); ?>
</div>
<div id="successMessage" class="row">
    <?=nemptydef($successMessage,"")?>
</div>
<div class="row buttons">
    <?php  $this->widget('ext.titWidgets.AjaxSubmit', array(
        'label'=>t("TitUbiAuthModule.general","Save"),
        'url'=>$this->action,
        'htmlOptions'=>array('id'=>"restorePasswordButton"),
    ));?>
</div>
<?php $this->endWidget(); ?>
