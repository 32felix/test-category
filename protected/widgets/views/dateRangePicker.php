<?
/**
 * @var $this View
 * @var $widget \app\widgets\DateRangePicker
 */
use yii\base\View;
use yii\helpers\Html;

$widget=$this->context;

$time = time();
$id = "reportrange-".Html::getInputId($widget->model, $widget->attributeFrom)
    ."-".Html::getInputId($widget->model, $widget->attributeTill);

$format = $widget->timePicker?'YYYY-MM-DD HH:mm':'YYYY-MM-DD';

?>

<script type="text/javascript">
    $(function(){
        setTimeout(function() {
            var format = '<?=$format?>';
            $('#<?=$id?>').daterangepicker(
                {
                    ranges:
                    {
                        'Today': [moment().hour(0).minute(0).second(0), moment().hour(0).minute(0).second(0).add("days",1)],
                        'Yesterday': [moment().subtract('days', 1).hour(0).minute(0).second(0), moment().hour(0).minute(0).second(0)],
                        'Last 7 Days': [moment().subtract('days', 6).hour(0).minute(0).second(0), moment().hour(0).minute(0).second(0).add("days",1)],
                        'Last 30 Days': [moment().subtract('days', 29).hour(0).minute(0).second(0), moment().hour(0).minute(0).second(0).add("days",1)],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
                    },
                    locale: {
                        format: format
                    },
                    startDate: new Date(<?='"'.$widget->model->{$widget->attributeFrom}.'"'?>),
                    endDate: new Date(<?='"'.$widget->model->{$widget->attributeTill}.'"'?>)
                    <?=$widget->timePicker?", timePicker: true, timePicker24Hour:true":""?>
                },
                function(start, end)
                {
//                        console.log(arguments);
                    $('#<?=$id?>').html(''+start.format(format) + ' — ' + end.format(format));
                    $('#<?=Html::getInputId($widget->model, $widget->attributeFrom)?>').attr('value',start.format(format));
                    $('#<?=Html::getInputId($widget->model, $widget->attributeTill)?>').attr('value',end.format(format)).change();
                    <?if (!empty($widget->afterChange)):?>
                    <?=$widget->afterChange?>
                    <?endif;?>
                });
        }, 0);
    });
</script>

<div id="<?=$id?>" class="form-control input-sm date-picker"">
<?=$widget->model->{$widget->attributeFrom}.' — '.$widget->model->{$widget->attributeTill}?>
</div>
<?=Html::activeHiddenInput($widget->model,$widget->attributeTill)?>
<?=Html::activeHiddenInput($widget->model,$widget->attributeFrom)?>
