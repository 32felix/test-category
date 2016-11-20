<?php
/**
 * @var $this yii\web\View
 */

?>
<div class="ubi-titled-content">
	<div class="title">User activation</div>
    <div class="form">
       <?= $this->render("activateForm", ['model'=>$model]);?>
    </div>
</div>