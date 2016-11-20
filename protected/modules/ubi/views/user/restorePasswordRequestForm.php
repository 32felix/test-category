<?

/***
 * @var $this View
 */
//$this->widget("mod.titUbiAuth.widgets.RestorePasswordRequestFormWidget.php", array("model"=>$model));
use tit\ubi\widgets\RestorePasswordRequestFormWidget;
use yii\web\View;

echo RestorePasswordRequestFormWidget::widget(['model'=>$model]);

