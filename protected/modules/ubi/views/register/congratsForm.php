<?
/**
 * @var yii\web\View $this
 * @var string $ab_testname
 * @var string $ab_auth_option
 * @var boolean $use_popup
 * @var boolean $research
 */
use app\widgets\LessWidget;
use nodge\eauth\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>
<p>Щоб підтвердити реєстрацію, зайди на свою пошту та перейди за
лінком, який ми тобі надіслали</p>
<div class="field-submit"><button type="button" id="close-button" class="close-but">OK</button></div>
