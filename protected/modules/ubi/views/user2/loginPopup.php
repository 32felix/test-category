<?
/**
 * @var yii\web\View $this
 */
use nodge\eauth\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>
<div class="popup-login" xmlns="http://www.w3.org/1999/html">
    <div class="screen close-popup"></div>
    <div class="body">
        <div class="body-border">
            <div class="body-title-login">
                <div class="body-title selected" data-id="oauth">
                    <a><?=Yii::t("tit/ubi", "Login")?></a>
                </div>
                <div class="body-title" data-id="by-mail">
                    <a><?=Yii::t("tit/ubi", "Login by mail")?></a>
                </div>
            </div>
            <div class="body-content" id="oauth">
                <div class="body-social-content">
                    <?= Widget::widget(array('id'=>'login-popup-eauth','action' => 'user2/login-by-eauth')); ?>
                </div>
                <div class="body-another to-by-mail">
                    <a><?=Yii::t("tit/ubi", "Login by mail")?></a>
                </div>
            </div>
            <div class="body-content" id="by-mail">
                <div class="body-content-login">
                    <div class="warn"><?=Yii::t("tit/ubi","Registration by email is temporary disabled, but you can login if you are already registered.")?></div>
                    <div class="body-content-form">

                        <?=$this->render("loginForm",["model"=>$model])?>
                    </div>
                </div>
                <div class="body-another to-oauth">
                    <a><?=Yii::t("tit/ubi", "Login by social network")?></a>
                </div>
            </div>
        </div>
        <div class="close close-popup"></div>
    </div>
</div>
<script>
    RPopup.onLoad(function (popup, $div) {
        window.oauth_result=function(result)
        {
            console.log(result);
            if (result=="success")
                popup.close(result);
            else if (result=="success-no-mail") {
//                popup.close(result);
                RPopup.callPopup('<?=Url::toRoute(["/ubi/user2/add-email-popup"])?>',{
                    onClose:function(){
//                            msg.html("Error: " + data.error);
//                        form.find("[name=hadAuth]").val(1);
//                        btn.html(btnhtml);
//                        btn.attr('disabled', null);
//                        btn.click();
                        popup.close("success");
                    }
                });
            }
            else
                alert("Ошибка авторизации: "+result);
        }
        if (!popup.options.onClose)
            popup.options.onClose= function (result) {
                if (result=="success")
                    window.location = window.location;
            }

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