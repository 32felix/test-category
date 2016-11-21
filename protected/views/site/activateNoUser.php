<?php
/**
 * @var $email
 */
use yii\helpers\Html;

?>

<div class="ubi-titled-content">
    <p>Користувача не знайдено.</p>
    <p>Ви повинні зареєструватися <?=Html::a("тут", array("/register"))?>. </p>
    <div class="info">
        <?php echo date('d.m.Y, H:i', time()); ?>
    </div>
</div>


