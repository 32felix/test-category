<?php
/**
 * Created by PhpStorm.
 * User: Igor
 * Date: 2015-06-16
 * Time: 15:28
 */

namespace app\widgets;


use app\assets\DateRangePickerAsset;
use yii\base\Model;
use yii\base\Widget;

class DateRangePicker extends Widget
{

    /**
     * @var Model the data model that this widget is associated with.
     */
    public $model;

    /**
     * @var string the model attribute that this widget is associated with.
     */
    public $attributeFrom;
    public $attributeTill;

    public $timePicker=false;

    public function init()
    {

    }

    public function run()
    {
        DateRangePickerAsset::register($this->getView());
        echo $this->render("dateRangePicker",[
            "model"=>$this->model
        ]);
    }


}