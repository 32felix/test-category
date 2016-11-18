<?
/**
 * @var yii\web\View $this
 * @var string $ab_testname
 * @var string $ab_auth_option
 * @var boolean $politeGreeting
 * @var boolean $use_popup
 * @var boolean $research
 */
use app\widgets\LessWidget;
use nodge\eauth\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$contents = [
    'watches' => [
        'popup' => [
            'top' => '
                У цій анкеті на тебе чекатимуть різні питання, що дозволять виявити загальну думку щодо годинників преміум класу. <br> <br>
                Будь ласка, авторизуйся, щоб твої відповіді вважалися достовірними та могли використовуватися для подальшої обробки.
            ',
            'bottom' => '
                Ми не будемо поширювати інформацію без твоєї згоди, продавати твою поштову адресу чи надсилати спам. <br> <br> 
                Дякуємо!
            ',
        ],
        'balloon' => [
            'bottom' => '
                Будь ласка, авторизуйся, щоб ми могли зарахувати твої відповіді
            '
        ]
    ],
    'robot' => [
        'robot' => [
            'top' => '
                Привіт! Авторизуйся, щоб прийняти умови участі у дослідженнях, умови користування ресурсом і політику конфіденційності
            '
        ],
        'no_robot' => [
            'top' => '
                Привіт! Авторизуйся, щоб прийняти умови участі у дослідженнях, умови користування ресурсом і політику конфіденційності
            '
        ]
    ],
    'default_research' => [
        'popup' => [
            'top' => '
                Авторизуйся, щоб пройти далі, а також отримати доступ до схожих опитувань та статистики
            ',
            'bottom' => '
                Ми не побачимо твої паролі, не будемо поширювати інформацію без твоєї згоди, продавати твою поштову адресу чи спамити тебе непотрібними повідомленнями
            '
        ],
        'balloon' => [
            'bottom' => '
                Будь ласка, авторизуйся, щоб ми могли зарахувати твої відповіді
            '
        ]
    ],
    'default_fun' => [
        'popup' => [
            'top' => '
                Любиш проходити квізи - люби і реєструватися.
            ',
            'bottom' => '
                Ми не отримаємо доступ до твоїх паролів та не зможемо поширювати інформацію без твого дозволу. <br> <br>
                І ніякого спаму, чесно
            '
        ],
        'balloon' => [
            'bottom' => '
                Ой, здається, хтось тут забув авторизуватися
            '
        ]
    ]
];

if($ab_testname === 'default') {
    $ab_index_name = 'default_' . ($research ? 'research' : 'fun');
} else {
    $ab_index_name = $ab_testname;
}

$ab_slider = 'reg-slider';

$_SESSION['ab_lastAuthTest'] = $ab_slider;

$ab_slider_do = Yii::$app->ab->getOption($ab_slider);
$content_top     = $contents[$ab_index_name][$ab_auth_option]["top"] ?? "";
$content_bottom  = $contents[$ab_index_name][$ab_auth_option]["bottom"] ?? "";

$btnClose = (!$use_popup)
    ? '<a class="close"><img src="/images/new_respo/quiz/close.png"></a>'
    : '<a class="close"></a>';


?>

<style>
    <?LessWidget::begin()?>
    
    <?LessWidget::end()?>
</style>

<? if (!$use_popup): ?>
    <div class="balloon-auth">
        <div class="balloon-auth-content">
<? else: ?>
    <div class="balloon-auth-popup">
        <div class="screen close-popup"></div>
        <div class="body balloon-auth-content">
<? endif; ?>
        <div class="close"></div>
        <div class="helper-say login-by-oauth">
<!--            <div class="logo"></div>
            <div class="top-content">
                <span>Авторизуй<?/*= \app\components\PoliteGreetings::isPolite() ? 'теся' : 'ся' */?>, щоб пройти далі та:</span>
                <ul>
                    <li>отримати доступ до десятків розважальних <span>квізів</span> та серйозних соціологічних <span>опитувань</span></li>
                    <li>приєднатися до спільноти <span>експертів</span> в улюблених сферах</li>
                    <li>вільно виражати свою думку та <span>змінювати цим світ</span></li>
                </ul>
            </div>-->
            <? if (($ab_slider_do) && ($research)): ?>
            <div class="for-slider">
                <div class="logo-slider"></div>
                <p>Приєднуйся до тих, хто знає відповіді</p>
                <div class="popup-slider">
                    <ul class="slidewrapper" data-current=0 data-slide=1>
                        <li class="popup-slide first">
                                <!--<img src="/images/new_respo/popup/shoping.png"/>-->
                            <div class="for-text">
                                <h5 class="popup-text">У якому магазині найвигідніші знижки?</h5>
                            </div>
                        </li>
                        <li class="popup-slide">
                               <!-- <img src="/images/new_respo/popup/bananas.png"/>-->
                            <div class="for-text">
                                <h5 class="popup-text">У якому супермаркеті найстигліші фрукти?</h5>
                            </div>
                        </li>
                        <li class="popup-slide last">
                            <!--<img src="/images/new_respo/popup/taxi.png"/>-->
                            <div class="for-text">
                                <h5 class="popup-text">Яка зі служб таксі завжди подає автомобіль?</h5>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <? else: ?>
                <div class="logo"></div>
                <div class="top-content">
                    <span>Авторизуй<?= \app\components\PoliteGreetings::isPolite() ? 'теся' : 'ся' ?>, щоб пройти далі та:</span>
                    <ul>
                        <li>отримати доступ до десятків розважальних <span>квізів</span> та серйозних соціологічних <span>опитувань</span></li>
                        <li>приєднатися до спільноти <span>експертів</span> в улюблених сферах</li>
                        <li>вільно виражати свою думку та <span>змінювати цим світ</span></li>
                    </ul>
                </div>
            <? endif; ?>
            <?/*
            <p>Привіт! Зареєструйся, щоб прийняти умови участі у  дослідженнях, <a class="privacy-popup  dashed">політику конфіденційності</a> і <a class="terms-popup dashed">умови користування ресурсом</a></p>
            <div class="oauth-icons">
                <?= Widget::widget(array('id' => 'login-popup-eauth', 'action' => 'login/login-by-eauth')); ?>
                <div class="to-login-by-mail"></div>
            </div>
            <span class="message"></span>
            <a class="close"><img src="/images/new_respo/quiz/close.png"></a>
            */?>
            <div class="oauth-icons">
                <?= Widget::widget(array('id' => 'login-popup-eauth', 'action' => 'login/login-by-eauth')); ?>
                <div class="to-login-by-mail"></div>
            </div>

            <p class="content-bottom">
                <?/*= $content_bottom */?>
            </p>
            <div class="bottom-content">
                <p>Авторизуючись на сайті, я приймаю умови <a class="privacy-popup">політики конфіденційності</a> та <a class="terms-popup">умови користування ресурсом</a>.</p>
            </div>


            <?= $btnClose ?>
        </div>

        <div class="helper-say login-by-mail">
            <div class="logo"></div>
            <?= $this->render("loginForm2", ["model" => $model]) ?>

            <div class="div-bottom-button" style="text-align: center; padding-top: 7px;">
                <a class="to-login-by-oauth">Ввійти через соціальну мережу</a>
            </div>

            <?= $btnClose ?>
        </div>

        <div class="helper-say recall-password">
            <div class="logo"></div>
            <?= $this->render("../restore/forgot_pass_form2", ["model" => $model]) ?>
            <div class="div-bottom-button" style=" text-align: center; padding-top: 7px;">
                <a class="to-login-by-mail">Ввійти через email</a>
            </div>

            <?= $btnClose ?>
        </div>

        <div class="helper-say register">
            <div class="logo"></div>
            <?= $this->render("../register/registerForm") ?>
            

            <?= $btnClose ?>
        </div>
    </div>
</div>
<script>
    $(function(){

        function slideSwitch() {
            var $active = $('.popup-slide.active');

            if ( $active.length == 0 ) $active = $('.popup-slide:last');

            var $next =  $active.next().length ? $active.next()
                : $('.popup-slide:first');

            $active.addClass('last-active');

            $next.css({opacity: 0.0})
                .addClass('active')
                .animate({opacity: 1.0}, 1000, function() {
                    $active.removeClass('active last-active');
                });
        };


        var slideWidth=458;
        var sliderTimer;
        var current = 1;

        var $wrapper = $('.slidewrapper');

        var count = $wrapper.children().size();
        var textLabels = $('.popup-slide > div');
        var slides = $('.popup-slide');
        $(textLabels[0]).css('top', '10%');
        $(textLabels[1]).css('top', '50%');
        $(textLabels[2]).css('top', '30%');
       // $wrapper.append($wrapper.children().first().clone());
        /*$('.slidewrapper').width((count+1)*slideWidth);*/
        $wrapper.css("left", 0);
        $wrapper.data('current', 0);
        $(slides[0]).css('opacity', 1);
        setTimeout(nextSlide,3500);
        $(textLabels[0]).css('opacity', 0.95).animate({width: 350}, 1500);
        function nextSlide(){
            if (!document.contains($wrapper[0]))
                return;
            var currentSlide=$wrapper.data('current');
            currentSlide=(currentSlide+1)%count;
            $(textLabels[currentSlide]).css('opacity', 0).css({width: 0});
            $(slides[currentSlide]).css('opacity', 0);
            $(slides[currentSlide]).animate({opacity: 1}, 500, function(){
                $(slides[currentSlide-1]).css('opacity', 0);
                if(currentSlide == 0){
                    $(slides[2]).css('opacity', 0);
                }
                $wrapper.css({left:0});
                $wrapper.data('current', currentSlide);
                $(textLabels[currentSlide])
                    .css('opacity', 0.95)
                    .animate({width: 350}, 1500, function(){
                        setTimeout(nextSlide,2000);
                    });
            });

            /*$wrapper
                .animate({left: -currentSlide*slideWidth},500, function(){
                    if (currentSlide==count) {
                        currentSlide = 0;
                        $(textLabels[currentSlide]).css('opacity', 0).css({left: -250});
                        $wrapper.css({left:0});
                    }
                    $wrapper.data('current', currentSlide);

                    $(textLabels[currentSlide])
                        .css('opacity', 0.95)
                        .animate({left: 0}, 700, function(){
                            setTimeout(nextSlide,3000);
                        });
                })*/
        }
    });
</script>
<script>
    <?php
        $onLoadFunctionName = (!$use_popup)
            ? 'Animal.onBalloonLoad'
            : 'RPopup.onLoad';
    ?>
    <?= $onLoadFunctionName ?>(function (popup, $div) {
        window.oauth_result = function (result) {
            console.log(result);
            if (result == "success")
                popup.close(result);
            else if (result == "success-no-mail") {
//                popup.close(result);
                RPopup.callPopup('<?=Url::toRoute(["/ubi/email/add-email-popup"])?>', {
                    onClose: function () {
//                            msg.html("Error: " + data.error);
//                        form.find("[name=hadAuth]").val(1);
//                        btn.html(btnhtml);
//                        btn.attr('disabled', null);
//                        btn.click();
                        popup.close("success");
                    }
                });
            }
            else
                alert("Помилка авторизації: " + result);
        };
        if (!popup.options.onClose)
            popup.options.onClose = function (result) {
//                console.log("defOnClose", result, popup);
                if (result == "success") {
//                    alert(1);
                    window.location = window.location;
                }
            };

        $('.login-by-oauth', $div).show();


        $div.on("click", ".to-login-by-mail", function(){
            $(".helper-say", $div).hide();
            $(".recall-password", $div).hide();
            $(".login-by-mail", $div).show();
        })
        $div.on("click", ".to-login-by-oauth", function(){
            $(".helper-say", $div).hide();
            $(".login-by-oauth", $div).show();
        })
        $div.on("click", ".to-recall-password", function(){
            $(".helper-say", $div).hide();
            $(".recall-password", $div).show();
        })
        $div.on("click", "#register-button", function(){
            $(".helper-say", $div).hide();
            $(".register", $div).show();
        })
        $div.on("click", ".close", function(){
            popup.close();
        })
        $div.on("click", ".close-but", function(){
            popup.close();
        })
        $div.on("click", ".privacy-popup", function(){
            $(".balloon-auth-content").css("transition", "transform 1s ease-in-out 1s");
            $(".balloon-auth-content").css("transition", "top 1s ease-in-out 1s");
            $(".balloon-auth-content").css("transform", "scale(0.9)");
            $(".balloon-auth-content").css("top", "-50px");
        })
    })
</script>
<?/*
<script>
    Animal.onBalloonLoad(function (popup, $div) {
        var oldClose = popup.close;
        popup.close = function (result) {
            window.yaCounter31256393 && window.yaCounter31256393.reachGoal("reg-" + result);
            oldClose(result);
        };
    })
</script>
*/?>