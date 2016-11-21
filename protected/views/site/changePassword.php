<?
/**
 * @var yii\web\View $this
 */
?>
<div class="ubi-titled-content">
    <h3 class="title"><?="Зміна паролю"?></h3>
    <div class="form">
        <?=$this->render("changePasswordForm", array(
            "model"=>$model,
            "successMessage"=>$successMessage,
        ))?>
    </div>
</div>
