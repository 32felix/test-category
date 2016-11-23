<?php
/**
 * @var $email
 */
use yii\helpers\Html;

?>

<div class="ubi-titled-content">
    <p>Посилання, за яким Ви перейшли, вже не дійсне.</p>
    <p>Якщо Ви не ввійшли, <?=Html::a("відновіть свій пароль", array("/restore-password-request"))?>. </p>
    <div class="info">
        <?php echo date('d.m.Y, H:i', time()); ?>
    </div>
</div>


