<?
/*
 * */
use tit\widgets\AjaxSubmit;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$asAsset = \tit\widgets\AjaxSubmitAsset::register($this);
?>
<? $form = ActiveForm::begin(["id"=>"forget-password-form-2", 'action' => '/user/login', 'fieldConfig'=>["template" => "{label}\n{input}\n{hint}"]]); ?>
<? if (isset($msg)): ?>
    <div><?= $msg ?></div>
<?else:?>
    
    <p>Введ<?= \app\components\PoliteGreetings::isPolite() ? 'іть' : 'и' ?> адресу електронної пошти, на яку ми надішлемо форму
    для відновлення паролю</p>

    <?= $form->field($model, 'login')->textInput(["placeholder" => Yii::t("tit/ubi", "E-mail")])->label(false)?>
    <?=$form->errorSummary($model,["header"=>""])?>
    <div class="form-group field-submit">
        <div id="successMessage" class="row">
            <?= !empty($successMessage) ? $successMessage : "" ?>
        </div>
        <input type="submit" style="position: absolute; height: 0px; width: 0px; border: none; padding: 0px;" hidefocus="true" tabindex="-1"/>
        <button type="button" id="forgot-button" class="popup-login-button">Надіслати</button>
    </div>


    
    <script>
        $(function () {
            jQuery('#forgot-button').ajaxSubmit({
                "loaderImage": "/images/loader-circle.svg",
                "url": '<?=Url::to(["/ubi/restore/forgot-password", "v"=>"2"])?>',
                onJson: function (data, form) {
                    var rp = form.closest(".RPopup").data("RPopup");
                    if (rp)
                        rp.close("success");
                    else
                        window.location =<?=json_encode(Yii::$app->getUser()->getReturnUrl())?>;
                }
            });
        })
    </script>
<? endif ?>



<? ActiveForm::end(); ?>