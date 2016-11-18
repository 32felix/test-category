<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016-08-12
 * Time: 17:50
 */
namespace tit\ubi\widgets;
use yii\base\Widget;
use app\models\Form;
use app\models\TestSession;
class ExpertPopularityWidget extends Widget
{
    public $user;
    public $formId;
    public function init(){
        
    }
    public function run(){

            $graphics = Form::calcStat($this->formId, $this->user ? $this->user->id : 0);
            $stat = TestSession::statByWeek();
            $test=[];
            $monthes = ['', 'січень', 'лютий', 'березень', 'квітень', 'травень', 'червень', 'липень', 'серпень', 'вересень', 'жовтень', 'листопад', 'грудень'];
            $d = date_parse_from_format("Y-m-d", $stat['days'][0]);
            $test[0]=['Month', 'Respondents'];
            $test[1][0] = '';
            $test[1][1] = $stat['before'] + $stat['cnt'][0];
            for($i = 1; $i < count($stat['cnt']); $i++){
                $d = date_parse_from_format("Y-m-d", $stat['days'][$i]);
                $test[$i+1][0] = $monthes[$d['month']];
                $test[$i+1][1] = $test[$i][1] + $stat['cnt'][$i];
            }

            $table = str_replace("'", '', json_encode($test));
            return $this->render('expertPopularity', array(
                    "table" => $table,
                    "graphics" => $graphics,
            ));
        }
    
}