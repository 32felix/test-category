<?
/**
 * @var yii\web\View $this
 */
use nodge\eauth\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>
<div class="popup-login-with-text" xmlns="http://www.w3.org/1999/html">
    <div class="screen close-popup"></div>
    <div class="body">
        <div class="body-border">
            <p style="text-align: center;font-size: 18px;padding: 8px 0 0 0;">Авторизуйтесь, будь ласка, для того щоб побачити повні результати та отримати можливість зберегти їх та поділитися з друзями.</p>
            <?= \tit\ubi\widgets\LoginFormWidget::widget(array('id'=>'before-result-login')); ?>
        </div>
        <div class="close close-popup"></div>
    </div>
</div>

<script>
    !function(){
        var popup = RPopup.lastPopup;
        window.oauth_result=function(result)
        {
            console.log(result);
            if (result=="success")
                popup.close(result);
            else if (result=="success-no-mail") {
                RPopup.callPopup('<?=Url::toRoute(["/ubi/user2/add-email-popup"])?>',{
                    onClose:function(){
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
    }()
</script>