<?php
/**
 * @var $this View
 * @var $model \tit\ubi\model\GlobalUsers
 *
 */
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;

?>

<? $form = ActiveForm::begin(["id" => "ubi-change-password-widget-form", 'options' => [
    'class' => 'container',
    'style' => 'position: relative; top: 30px;'
]]) ?>
<?= $form->field($user, "id")->textInput()->hiddenInput()->label(''); ?>
<?= $form->field($user, "newPassword")->textInput(['type' => 'password', 'placeholder' => 'Введіть новий пароль',])->label(false); ?>
<?= $form->field($user, "confirmPassword")->textInput(['type' => 'password', 'placeholder' => 'Введіть новий пароль ще раз',])->label(false); ?>

<div id="successMessage" class="message">
    <?= issetdef($message) ?>
</div>

<?= \tit\widgets\AjaxSubmit::widget([
    "id" => "ubi-change-password-widget-submit",
    "url" => Url::to(["/ubi/restore/change-pass",
        "user"=>$_REQUEST["user"] ?? "",
        "token"=>$_REQUEST["token"] ?? "",
        "email"=>$_REQUEST["email"] ?? "",
    ]),
    "htmlOptions" => ["class" => "btn btn-success button-green"],
    "label" => "Змінити",
]);
?>


<? ActiveForm::end() ?>
