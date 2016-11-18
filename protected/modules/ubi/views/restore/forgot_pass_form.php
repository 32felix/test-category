<?
/*
 * */
use tit\widgets\AjaxSubmit;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$asAsset = \tit\widgets\AjaxSubmitAsset::register($this);
?>
<? $form = ActiveForm::begin(['action' => '/user/login']); ?>
<? if (isset($msg)): ?>
    <div><?= $msg ?></div>
<?else:?>
    
    <?= $form->field($model, 'login')->textInput(["placeholder" => Yii::t("tit/ubi", "Enter your email")])->label(false)?>

    <div class="form-group">
        <? //= Html::submitButton('Войти', ['class' => 'popup-login-button', 'name' => 'login-button']) ?>
        <div id="successMessage" class="row">
            <?= !empty($successMessage) ? $successMessage : "" ?>
        </div>
        <input type="submit" style="position: absolute; height: 0px; width: 0px; border: none; padding: 0px;" hidefocus="true" tabindex="-1"/>
        <button type="button" id="forgot-button" class="popup-login-button">Далі</button>
    </div>
    <script>
        $(function () {
            jQuery('#forgot-button').ajaxSubmit({
                "loaderImage": "<?=$asAsset->baseUrl?>/load.gif",
                "url": "/ubi/restore/forgot-password",
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