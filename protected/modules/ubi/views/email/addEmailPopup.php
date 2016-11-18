<?
/**
 * @var yii\web\View $this
 */
use nodge\eauth\Widget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="popup-add-mail">
    <div class="screen close-popup"></div>
    <div class="body">
        <div class="close"></div>
        <div class="logo"></div>
        <p>Для завершення реєстрації введіть вашу електронну адресу</p>
        <?=$this->render("addEmailForm",["model"=>$model])?>
    </div>
</div>
<script>

    RPopup.onLoad(function(popup, $div){
        var popup = RPopup.lastPopup;
        if (!popup.options.onClose)
            popup.options.onClose= function (result) {
//            console.log(popup);
            };
        $div.on("click", ".close", function(){
            popup.close();
        })
    })
</script>