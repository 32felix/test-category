<?php
/**
 * @var $email
 */
use yii\helpers\Html;

?>

<div class="ubi-titled-content">
    <div class="title">User activation</div>
    <p>User not found.</p>
    <p>You can sign up <?=Html::a("here", array("/ubi/user/register"))?>. </p>
    <div class="info">
        <?php echo date('d.m.Y, H:i', time()); ?>
    </div>
</div>


