<?
/*
 * */
use tit\widgets\AjaxSubmit;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$asAsset=\tit\widgets\AjaxSubmitAsset::register($this);
?>
<?$form = ActiveForm::begin(['action'=>'/user/login']); ?>
<?= $form->field($model, 'email')->textInput()->label('Ваш email')?>

<div id="successMessage" class="row">
    <?=!empty($successMessage)?$successMessage:""?>
</div>

<input type="submit"
       style="position: absolute; height: 0px; width: 0px; border: none; padding: 0px;"
       hidefocus="true" tabindex="-1"/>
<button type="button" id="save_email-button" class="popup-login-button">Сохранить</button>

<script>
    $(function(){
        jQuery('#save_email-button').ajaxSubmit({
            "loaderImage":"<?=$asAsset->baseUrl?>/load.gif",
            "url":"/ubi/user2/add-email",
            onJson:function(data, form)
            {
                var rp = form.closest(".RPopup").data("RPopup");
                if (rp)
                    rp.close("success");
                else
                    window.location=window.location;
            }
        });
    })
</script>
<? ActiveForm::end(); ?>