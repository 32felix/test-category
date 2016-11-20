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
<?//= $form->field($model, 'login')->textInput()->label('Ваш email')?>
<?//= $form->field($model, 'password')->passwordInput()->label('Ведите свой пароль')?>

    <div class="form-group required">
        <?=Html::activeTextInput($model, 'login', ["placeholder"=>Yii::t("tit/ubi","Enter your email"), "class"=>"form-control"])?>
        <div class="help-block"></div>
    </div>

    <div class="form-group required">
        <?=Html::activePasswordInput($model, 'password', ["placeholder"=>Yii::t("tit/ubi","Enter your password"), "class"=>"form-control"])?>
        <div class="help-block"></div>
    </div>

    <div class="popup-login-wrap">
        <?//= Html::submitButton('Войти', ['class' => 'popup-login-button', 'name' => 'login-button']) ?>
        <div id="successMessage" class="row">
            <?=!empty($successMessage)?$successMessage:""?>
        </div>

        <input type="submit"
               style="position: absolute; height: 0px; width: 0px; border: none; padding: 0px;"
               hidefocus="true" tabindex="-1"/>
        <button type="button" id="login-button" class="popup-login-button"><?=Yii::t("tit/ubi","Login")?></button>


        <?/*= AjaxSubmit::widget(['label' => 'Войти',
            'url'=>Url::toRoute("user2/login-by-form"),
            'htmlOptions'=>['class'=>"popup-login-button"]]);
        */?>
        <?/*<a>Забыли пароль?</a>*/?>
    </div>
<script>
    $(function(){
        jQuery('#login-button').ajaxSubmit({
            "loaderImage":"<?=$asAsset->baseUrl?>/load.gif",
            "url":"/ubi/user2/login-by-form",
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