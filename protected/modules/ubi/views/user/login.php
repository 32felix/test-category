<?
/*
 * */
use nodge\eauth\Widget;
use tit\widgets\AjaxSubmit;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>
<div class="login-block body-border" style="    width: 400px;    height: 230px;">
    
    <div class="body-title-login" style="display: none;">
        <div class="body-title selected" data-id="oauth">
            <a><?=Yii::t("tit/ubi", "Login")?></a>
        </div>
        <div class="body-title" data-id="by-mail">
            <a><?=Yii::t("tit/ubi", "Login by mail")?></a>
        </div>
    </div>
    <div class="body-content" id="oauth">
        <div class="body-social-content">
            <?= Widget::widget(array('id'=>'login-popup-eauth','action' => 'login/login-by-eauth')); ?>
        </div>
        <div class="body-another to-by-mail">
            <a><?=Yii::t("tit/ubi", "Login by mail")?></a>
        </div>
    </div>
    <div class="body-content" id="by-mail">
        <div class="body-content-login">
            <div class="warn"><?=Yii::t("tit/ubi","Registration by email is temporary disabled, but you can login if you are already registered.")?></div>
            <div class="body-content-form">

                <?=$this->render("../login/loginForm",["model"=>$model])?>
            </div>
        </div>
        <div class="body-another to-oauth">
            <a><?=Yii::t("tit/ubi", "Login by social network")?></a>
        </div>
    </div>
</div>

<script>
    $(function () {
        window.oauth_result=function(result)
        {
            console.log(result);
            if (result=="success")
            {
                window.location = <?=json_encode(Yii::$app->getUser()->getReturnUrl())?>;
            }
            else if (result=="success-no-mail") {
                RPopup.callPopup('<?=Url::toRoute(["/ubi/email/add-email-popup"])?>',{
                    onClose:function(){
                        window.location = <?=json_encode(Yii::$app->getUser()->getReturnUrl())?>;
                    }
                });
            }
            else
                alert("Ошибка авторизації: "+result);
        }

        var $div=$(".login-block");

        $div.on("click", ".body-title", function(){
            $(".body-title", $div).removeClass("selected");
            $(this).addClass("selected");

            $(".body-content", $div).hide();
            $("#"+$(this).attr("data-id"), $div).show();
        });

        $(".body-another.to-by-mail a", $div).click(function(){
            $("[data-id=by-mail]", $div).click();
        });

        $(".body-another.to-oauth a", $div).click(function(){
            $("[data-id=oauth]", $div).click();
        });

        $(".body-title.selected", $div).click();
    })
</script>