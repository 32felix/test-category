<?
/*
 * */
use tit\widgets\AjaxSubmit;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$asAsset = \tit\widgets\AjaxSubmitAsset::register($this);
?>
<? $form = ActiveForm::begin(["id"=>"login-form-2", 'action' => '/user/login', 'fieldConfig'=>["template" => "{input}\n{hint}"]]); ?>



    <?= $form->field($model, 'login')->textInput(["placeholder" => "E-mail"])->label(false)?>
    <?= $form->field($model, 'password')->passwordInput(["placeholder" => "Пароль"])->label(false)?>

    <?=$form->errorSummary($model,["header"=>""])?>
    <a class="to-recall-password">Відновити пароль?</a>
    <div class="form-group field-submit" style="text-align: center;">
        <div id="successMessage" class="row">
            <?= !empty($successMessage) ? $successMessage : "" ?>
        </div>
        <input type="submit" style="position: absolute; height: 0; width: 0; border: none; padding: 0;" hidefocus="true" tabindex="-1"/>
        <button type="button" id="login-button" class="popup-login-button">Увійти</button>

        <!--<div id="successMessage" class="row">
            <?/*= !empty($successMessage) ? $successMessage : "" */?>
        </div>-->
        <input type="submit" style="position: absolute; height: 0; width: 0; border: none; padding: 0;" hidefocus="true" tabindex="-1"/>
        <button type="button" id="register-button" class="popup-register-button">Реєстрація</button>
    </div>


    <script>
        $(function () {
            jQuery('#login-form-2 #login-button').ajaxSubmit({
                "loaderImage": "/images/loader-circle.svg",
                "url": "<?=Url::to(["/ubi/login/login-by-form", "v"=>"2"])?>",
                onJson: function (data, form) {
                    var rp = form.closest(".RPopup").data("RPopup");
                    console.log("lf2", form, rp, this);
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