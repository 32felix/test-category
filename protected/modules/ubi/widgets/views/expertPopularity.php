<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016-07-07
 * Time: 13:16
 */
use app\models\Form;
use yii\web\User;
use yii\web\View;
use yii\helpers\Url;
?>
<style>
    .hidden{
        display: none;
    }
</style>

<div id="personal" class="personal-block blocks" <!--data-id="--><?/*=$table*/?>">
    <div class="expert">
        <? $average = 0;
        $mine = 0;
        $width = 35;
        $max_h = 80;
        $min_h = 5?>

            <h1>Експертна популярність ресурсу</h1>
        <hr>
            <div class="bar-graphics">
                <div class="graphics popular">
                    <?
                    if($graphics['popular']['average'] > $graphics['popular']['mine']){
                        $average = $max_h;
                        $mine = $graphics['popular']['mine']/$graphics['popular']['average']*100;
                        $mine < 5? $mine = 5: $mine=$mine;
                    }
                    elseif($graphics['popular']['average'] < $graphics['popular']['mine']){
                        $mine = $max_h;
                        $average = $graphics['popular']['average']/$graphics['popular']['mine']*100;
                        $average < 5? $average = 5: $average=$average;
                    }
                    ?>
                    <h1>Популярність</h1>
                    <div class="chart">
                    <div class="their" height="<?=$average?>">
                        <div class="text under"><?= round($graphics['popular']['average'], 0) ?></div>
                        <svg version="1.1" class="pyramid"
                             xmlns="http://www.w3.org/2000/svg"
                             x="0px" y="0px" width="100%" height="100%" viewBox="0 0 <?=$width?> <?=$average?>">
                            <polygon class="left" points="0,0 0,<?=$average?> <?=$width?>,<?=$average?> <?=$width?>,0 "/>
                            <polygon class="right" points="0,0 <?=$width?>,<?=$average?> <?=$width?>,0 "/>
                        </svg>

                    </div>
                    <div class="mine" height="<?=$mine?>">
                        <div class="text under"><?= round($graphics['popular']['mine'], 1) ?></div>
                        <svg version="1.1" class="pyramid"
                             xmlns="http://www.w3.org/2000/svg"
                             x="0px" y="0px" width="100%" height="100%" viewBox="0 0 <?=$width?> <?=$mine?>">
                            <polygon class="left" points="0,0 0,<?=$mine?> <?=$width?>,<?=$mine?> <?=$width?>,0  "/>
                            <polygon class="right" points="0,0 <?=$width?>,<?=$mine?> <?=$width?>,0 "/>
                        </svg>
                    </div>
                    <div class="line"></div>
                    </div>
                    <div class="legend-text">
                        <div class="avg">
                            в середньому
                        </div>
                        <div class="this">
                            даної анкети
                        </div>
                    </div>
                </div>
<!--                <div class="graphics occupancy">
                    <?/* $average = round($graphics['fillRate']['average'] * 80 / 100, 3);
                    $average = $average<$min_h ? $min_h: $average;
                    $mine = round($graphics['fillRate']['mine'] * 80 / 100, 3);
                    $mine = $mine<$min_h ? $min_h: $mine*/?>
                    <h1>Заповненість</h1>
                    <div class="their" height="<?/*=$average*/?>">
                        <div class="text under"><?/*= round($graphics['fillRate']['average'], 1) */?>%</div>
                        <svg version="1.1" class="pyramid"
                             xmlns="http://www.w3.org/2000/svg"
                             x="0px" y="0px" width="100%" height="100%" viewBox="0 0 <?/*=$width*/?> <?/*=$average*/?>">
                            <polygon class="left" points="0,0 0,<?/*=$average*/?> <?/*=$width*/?>,<?/*=$average*/?> <?/*=$width*/?>,0 "/>
                            <polygon class="right" points="0,0 <?/*=$width*/?>,<?/*=$average*/?> <?/*=$width*/?>,0 "/>
                        </svg>
                    </div>
                    <div class="mine" height="<?/*=$mine*/?>">
                        <div class="text under"><?/*= round($graphics['fillRate']['mine'], 1) */?>%</div>
                        <svg version="1.1" class="pyramid"
                             xmlns="http://www.w3.org/2000/svg"
                             x="0px" y="0px" width="100%" height="100%" viewBox="0 0 <?/*=$width*/?> <?/*=$mine*/?>">
                            <polygon class="left" points="0,0 0,<?/*=$mine*/?> <?/*=$width*/?>,<?/*=$mine*/?> <?/*=$width*/?>,0  "/>
                            <polygon class="right" points="0,0 <?/*=$width*/?>,<?/*=$mine*/?> <?/*=$width*/?>,0 "/>
                        </svg>
                    </div>
                    <div class="line"></div>

                    <div class="legend-text">
                        <div class="avg">
                            в середньому
                        </div>
                        <div class="this">
                            даної анкети
                        </div>
                    </div>
                </div>-->
                <div class="graphics pass">
                    <?
                    if($graphics['formsPerUser']['average'] > $graphics['formsPerUser']['mine']){
                        $average = $max_h;
                        $mine = $graphics['formsPerUser']['mine']/$graphics['formsPerUser']['average']*100;
                        $mine < 5? $mine = 5: $mine=$mine;
                    }
                    else{
                        $mine = $max_h;
                        $average = $graphics['formsPerUser']['average']/$graphics['formsPerUser']['mine']*100;
                        $average < 5? $average = 5: $average=$average;
                    }
                    ?>
                    <h1>Пройдені анкети</h1>
                    <div class="chart">
                        <div class="their" height="<?=$average?>">
                            <div class="text under"><?= round($graphics['formsPerUser']['average'], 1) ?></div>
                            <svg version="1.1" class="pyramid"
                                 xmlns="http://www.w3.org/2000/svg"
                                 x="0px" y="0px" width="100%" height="100%" viewBox="0 0 <?=$width?> <?=$average?>">
                                <polygon class="left" points="0,0 0,<?=$average?> <?=$width?>,<?=$average?> <?=$width?>,0 "/>
                                <polygon class="right" points="0,0 <?=$width?>,<?=$average?> <?=$width?>,0 "/>
                            </svg>

                        </div>
                        <div class="mine" height="<?=$mine?>">
                            <div class="text under"><?= round($graphics['formsPerUser']['mine'], 1) ?></div>
                            <svg version="1.1" class="pyramid"
                                 xmlns="http://www.w3.org/2000/svg"
                                 x="0px" y="0px" width="100%" height="100%" viewBox="0 0 <?=$width?> <?=$mine?>">
                                <polygon class="left" points="0,0 0,<?=$mine?> <?=$width?>,<?=$mine?> <?=$width?>,0  "/>
                                <polygon class="right" points="0,0 <?=$width?>,<?=$mine?> <?=$width?>,0 "/>
                            </svg>
                        </div>
                    <div class="line"></div>
                    </div>
                    <div class="legend-text">
                        <div class="avg">
                            в середньому
                        </div>
                        <div class="this">
                            тобою
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="graphics respond">
                <h1>Респонденти, що покращують світ разом з нами</h1>
                <div class="area" id="area">
                </div>
                <div class="area-big" id="area1"></div>
            </div>
    </div>
    <div class="other-form">
            <h1>Кейси, в які користувачі TheRespo зробили свій внесок</h1>
        <hr>
            <div class="projects-block">
                <div class="content">
                    <div class="block-content">
                        <div class="for-img">
                                <img src="/images/new_respo/quiz/result/projects/11.png">
                        </div>
                        <div class="about-pr">
                            Регулярно досліджуємо ефективність впровадження системи ProZorro,
                            що покликана забезпечити прозорість та подолати корупцію в держзакупівлях.
                            Визначаємо найбільш ефективні канали комунікації, маркетингові меседжі, оптимальні шляхи впровадження системи.
                        </div>
                    </div>
                </div>
               <!-- <hr>-->
                <div class="content">
                    <div class="block-content">
                        <div class="for-img">
                            <img src="/images/new_respo/quiz/result/projects/22.png">
                        </div>
                        <div class="about-pr">
                            Визначили оптимальні характеристики унікальної
                            магістерської програми європейського зразка із іноземними викладачами в Україні, преференції цільової аудиторії та шляхи розповсюдження інформації про програму.
                        </div>
                    </div>
                </div>
               <!-- <hr>-->
                <div class="content">
                    <div class="block-content">
                        <div class="for-img">
                            <img src="/images/new_respo/quiz/result/projects/33.png" class="gl-shapers">
                        </div>
                        <div class="about-pr">
                            Знайшли зацікавлені аудиторії та шляхи їх залучення до реалізації громадсько корисних проектів, які розвиває в Україні молодіжне крило Всесвітнього економічного форуму.
                        </div>
                    </div>
                </div>
            </div>
        <hr>
        <div class="preview-projects">
            <div class="for-img">
                <img src="/images/new_respo/quiz/result/projects/11.png">
            </div>
            <div class="for-img">
                <img src="/images/new_respo/quiz/result/projects/22.png">
            </div>
            <div class="for-img">
                <img src="/images/new_respo/quiz/result/projects/33.png">
            </div>
        </div>
            <!--<div id="show-projects"> Розгорнути <img src="/images/new_respo/quiz/result/more-answers.png"alt=""></div>
            <div id="hide-projects" class="hidden"> Приховати <img src="/images/new_respo/quiz/result/more-answers.png" style="transform: scale(-1)" alt=""></div>-->
    </div>
</div>

<script>
    $(function () {
        var countS = 0;
        var countB = 0;
        $(window).resize(function(){

            var isSingle = $("#personal").hasClass("single-block");
            if(!isSingle){
                if($(window).width() < 984){
                    if(countB < 1){
                        console.log($(window).width(), "countB: "+countB);
                        drawPopularityChart("big");

                    }
                    $(".area").addClass("hidden");
                    $(".area-big").removeClass("hidden");
                }else{
                    if(countS < 1){
                        console.log($(window).width(), "countS: "+countS);
                        drawPopularityChart("small");

                    }
                    $(".area-big").addClass("hidden");
                    $(".area").removeClass("hidden");
                }
            }
        });
        function primaryDraw (){
            var isSingle = $("#personal").hasClass("single-block");
            $(".area").toggleClass("hidden", isSingle);
            $(".area-big").toggleClass("hidden", !isSingle);
            var mode = isSingle ? "big" : "small";
            if($(window).width() < 984){
                mode = "big";
                $(".area").addClass("hidden");
                $(".area-big").removeClass("hidden");
            }
            drawPopularityChart(mode);
        }
        $($('.main-block').length ? '' : '.personal-block').addClass('single-block');
        primaryDraw();
        function drawPopularityChart(mode){
            google.charts.setOnLoadCallback(function () {
                var data = google.visualization.arrayToDataTable(<?=$table?>);
                var chartArea;
                var width;
                var height;
                var bar;
                if(mode == 'small'){
                    chartArea = {left: 40, width: 210, height: 110};
                    width = 250;
                    height = 150;
                    bar = {groupWidth: '61%'};
                }else if(mode == 'big'){
                    chartArea = { top: 25, left: 45, width: 310, height: 150};
                    width = 380;
                    height = 200;
                    bar = {groupWidth: '57%'}
                }
                var options = {
                    width: width,
                    height: height,
                    bar: bar,
                    vAxis: {
                        gridlines: {color: 'transparent'},
                        baselineColor: 'grey',
                        baseline: 200,
                        scaleType: 'linear',
                        viewWindow: {
                            min: 1000,
                        },
                        textStyle: {
                            fontSize: 11,
                            fontName: 'Myriad Pro LightExt',
                            color: '#6B5A6A'
                        }
                    },
                    hAxis: {
                        gridlines: {color: 'red'},
                        baselineColor: '#d3d3d3',
                        showTextEvery:5,
                        textPosition: 'out',
                        minTextSpacing: 1,
                        textStyle: {
                            fontSize: 11,
                            fontName: 'Myriad Pro Light',
                            color: '#6B5A6A'
                        }
                    },
                    chartArea: chartArea,
                    crosshair: {
                        color: '#000'
                    },
                    series: {
                        0: {
                            color: '#a79ba4',
                            visibleInLegend: false,
                            pointSize: '10px',
                            pointsVisible: true
                        }
                    }
                };
                if(mode == "small") {
                    var chart = new google.visualization.ColumnChart(document.getElementById('area'));
                    countS++;
                }
                else{
                    var chart = new google.visualization.ColumnChart(document.getElementById('area1'));
                    countB++;
                }
                chart.draw(data, options);
            });
        }

        $('.projects-block').slick({
            centerMode: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            fade: true,
            asNavFor: '.preview-projects',
            prevArrow: '<button type="button" data-role="none" class="prev-diagram" tabindex="0" role="button">' +
            '<img src="/images/new_respo/about/projects/next.png"/></button>',
            nextArrow: '<button type="button" data-role="none" class="next-diagram" tabindex="0" role="button">' +
            '<img src="/images/new_respo/about/projects/next.png"/></button>'
        });

        $('.preview-projects').slick({
            centerPadding: 0,
            slidesToShow: 2,
            slidesToScroll: 1,
            dots: false,
            focusOnSelect: true,
            asNavFor: '.projects-block',
            prevArrow: '<button type="button" data-role="none" class="prev-project" tabindex="0" role="button">' +
            '<img src="/images/new_respo/quiz/result/arrow.png"/></button>',
            nextArrow: '<button type="button" data-role="none" class="next-project" tabindex="0" role="button">' +
            '<img src="/images/new_respo/quiz/result/arrow.png"/></button>'
        });
    })
</script>
