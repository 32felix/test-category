<?
/*
 * */
use tit\ubi\model\GlobalUsers;
use tit\widgets\AjaxSubmit;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

if (empty($model)) {
    $model = new \app\modules\ubi\model\form\RegisterForm();
}

$asAsset = \tit\widgets\AjaxSubmitAsset::register($this);
?>

<? $form = ActiveForm::begin(["id"=>"register-form", 'action' => '/user/login', 'fieldConfig'=>["template" => "{label}\n{input}\n{hint}"]]); ?>
<? if (isset($msg)): ?>
    <div><?= $msg ?></div>
<?else:?>
    
    <p><?= \app\components\PoliteGreetings::isPolite() ? 'Доброго дня! Зареєструйтеся' : 'Привіт! Зареєструйся' ?>, щоби прийняти умови участі у дослідженні,
    умови користування ресурсом і політику конфіденційності</p>

    <?= $form->field($model, 'email')->textInput(["placeholder" => "E-mail"])->label(false)?>
    <?= $form->field($model, 'password')->passwordInput(["placeholder" => "Пароль"])->label(false)?>
    <!--<div class="field-loginform-password">
        <input type="password" name="confirmPassword" id="confirmPassword" placeholder="Підтверди пароль">
    </div>
    <div class="pass-fault"><p>Паролі не співпадають</p></div>-->

    <?=$form->errorSummary($model,["header"=>""])?>
    <div class="form-group field-submit">
        <div id="successMessage" class="row">
            <?= !empty($successMessage) ? $successMessage : "" ?>
        </div>
        <input type="submit" style="position: absolute; height: 0px; width: 0px; border: none; padding: 0px;" hidefocus="true" tabindex="-1"/>
        <button type="button" id="register-button1" class="popup-login-button">Зареєструватись</button>
    </div>

    <div class="div-bottom-button" style=" text-align: center; padding-top: 7px;">
        <a class="to-login-by-oauth">Ввійти через соціальну мережу</a>
    </div>

<? endif ?>
<script>

    /*$("#register-button1").click(
        function() {
            if($("#registerform-password").val() == $("#confirmPassword").val()){
            } else{
                $(".pass-fault").css("height", "20px");
            }
        });*/

    $(function () {
        jQuery('#register-button1').ajaxSubmit({
            "loaderImage": "/images/loader-circle.svg",
            "url": "<?=Url::to(["/ubi/register/form"])?>",
            onJson: function (data, form) {
                var rp = form.closest(".RPopup").data("RPopup");
                if (rp)
                    rp.close("success");
                else {
                    alert(2);
                    window.location =<?=json_encode(Yii::$app->getUser()->getReturnUrl())?>;
                }
            }
        });
    })

    
</script>

<? ActiveForm::end(); ?>