<?php
use nodge\eauth\Widget;

?>
<div class="body-social-content">
    <span>Ви можете використати соціальну мережу для входу на сайт:</span>
    <?= Widget::widget(array('id'=>'login-eauth','action' => '/site/login-by-eauth', 'predefinedServices' => ['vkontakte', 'ok', 'mailru', 'facebook'])); ?>
    <div class="">
        <div></div>
        <div class="select-eauth">
            <?= Widget::widget(array('id'=>'login-eauth','action' => '/site/login-by-eauth', 'predefinedServices' => ['vkontakte', 'ok', 'mailru', 'facebook'])); ?>
        </div>
    </div>
</div>