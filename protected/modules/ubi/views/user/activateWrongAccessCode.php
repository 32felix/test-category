<?php
/**
 * @var $email
 */
use yii\helpers\Html;

?>

<div class="ubi-titled-content">
    <div class="title">User activation</div>
    <p>Activation link is expired.</p>
    <p>If you cannot sign in, try to <?=Html::a("restore your password", array("/ubi/user/restore-password-request"))?>. </p>
    <div class="info">
        <?php echo date('d.m.Y, H:i', time()); ?>
    </div>
</div>


