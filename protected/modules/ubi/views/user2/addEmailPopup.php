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
        <h2>Ваш email</h2>

        Для завершения регистрации укажите ваш электронный адрес
        <?=$this->render("addEmailForm",["model"=>$model])?>
    </div>
</div>
<script>

    !function(){
        var popup = RPopup.lastPopup;
        if (!popup.options.onClose)
            popup.options.onClose= function (result) {
            }
        $(function(){
//            console.log(popup);
        });
    }()
</script>