<?

/***
 * @var $this CController
 */
?>
<div class="ubi-titled-content">
    <div class="title"><? echo('Restore your password'); ?></div>
    <div class="form">
        <?// $this->widget("mod.titUbiAuth.widgets.RestorePasswordRequestFormWidget", array("model"=>$model));?>
        <? echo \tit\ubi\widgets\RestorePasswordRequestFormWidget::widget(["model"=>$model])?>
    </div>
</div>