<?
/**
 * @var yii\web\View $this
 */
?>
<div class="ubi-titled-content">
    <div class="title"><?="Change password"?></div>
    <div class="form">
        <?=$this->render("changePasswordForm", array(
            "model"=>$model,
            "successMessage"=>$successMessage,
        ))?>
    </div>
</div>
