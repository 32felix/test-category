<?php
/**
 * @var $this View
 * @var $model app\modules\ubi\model\UserPhone
 *
 */
use app\modules\ubi\model\UserPhone;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;

/**
 * @var UserPhone[] $phones
 * @var \tit\ubi\model\GlobalUsers $gUser
 */
$phones = UserPhone::findAll(["user"=>\Yii::$app->user->id]);
?>
<?$form=ActiveForm::begin(["id"=>"ubi-change-phone-widget-form", 'fieldConfig'=>["template"=>"{input}\n{error}"], "enableClientValidation"=>false])?>
<input type="hidden" name="pass"/>
<div class="grid-email">
    <?foreach($phones as $phone):?>
        <div class="grid-one-email">
            <div class="grid-cell title">
                <p>Phone</p>
            </div>
            <div class="grid-cell address">
                <p><?=$phone->phone?></p>
            </div>
            <div class="grid-cell confirmed">
                <? if ($phone->timeVerified): ?>
                    <span class='confirmed' title="Confirmed at <?=$phone->timeVerified?>">підтверджено</span>
                <? else: ?>
                    <span class='not-confirmed' title="Verification mail sent at <?=$phone->timeVerificationSent?>">не підтверджено</span>
                <? endif ?>
            </div>

            <? if ($phone->phone==$gUser->phone): ?>
                <div class="grid-cell major">
                    <span>oсновний</span>
                </div>
                <div class="grid-cell empty">
                </div>
            <? else: ?>
                <? if ($phone->timeVerified): ?>
                    <div class="grid-cell">
                        <a href="#" class="phone-make-primary" data-email="<?=htmlentities($phone->phone)?>">зробити основним</a>
                    </div>
                <? else: ?>
                    <div class="grid-cell empty">
                    </div>
                <? endif ?>
                <div class="grid-cell">
                    <a href="#" class="phone-delete" data-email="<?=htmlentities($phone->phone)?>">видалити</a>
                </div>
            <? endif; ?>
            <div class="grid-cell resent">
                <? if (!$phone->timeVerified): ?>
                    <a href="#" class="resend-phone-verification" data-email="<?=htmlentities($phone->phone)?>">відіслати лист ще раз</a>
                <? endif ?>
            </div>
        </div>
    <?endforeach?>
</div>
<div class="grid-email form">
    <div class="grid-one-email">
        <div class="grid-cell title">
            <p>Phone</p>
        </div>
        <?=$form->field($model, "phone")->textInput(['placeholder'=>'phone', 'label'=>'Phone'])?>
    </div>
</div>
<div class="for-button">
    <?=\tit\widgets\AjaxSubmit::widget([
        "id"=>"ubi-change-phone-widget-submit",
        "url"=>Url::to(["/ubi/phone/update"]),
        "htmlOptions"=>["class"=>""],
        "label"=>"Додати",
    ]); ?>
</div>
<!--<div id="successMessage" class="message">
    <?/*=issetdef($msg)*/?>
</div>-->
<script>
    $(function(){
        var $form = $("#ubi-change-phone-widget-form");
        function formReload()
        {
            $form.html("<img src='/images/loading.gif'/>")
            jQuery.ajax({
                'type': 'POST',
                'url': '<?=Url::to(["/ubi/phone/update"])?>',
            }).done(function (data, textStatus, jqXHR)
            {
                $form.replaceWith(data);
            }).fail(function (jqXHR, textStatus, errorThrown) {
                $form.html("Error: " + jqXHR.status + " " + jqXHR.statusText);
            });
        }

        var _pass;
        function getPass(done)
        {
            if (_pass)
                done(_pass);
            else
                swal({
                    title: "Потрібен пароль!",
                    text: "Введіть свій пароль:",
                    type: "input",
                    showCancelButton: true,
//                    closeOnConfirm: false,
                    animation: "slide-from-top",
                    inputPlaceholder: "password",
                    inputType:"password",
                }, function(inputValue){
                    if (inputValue === false) return false;
                    //                if (inputValue === "") {
                    //                    pass = inputValue;
                    //                    swal.showInputError("You need to write something!");
                    //                    return false
                    //                }
                    //                swal("Nice!", "You wrote: " + inputValue, "success");
                    _pass = inputValue;
                    done(_pass);
                });
        }

        $form.on("click", ".phone-make-primary", function(){
            var $this = $(this);
            getPass(function(pass)
            {
                return ajaxBtn($this, "<?=Url::to(["/ubi/phone/make-primary"])?>", {phone:$this.attr("data-email"), pass:pass}, function () {
                    formReload();
                });
            });
            return false;
        })
        $form.on("click", ".phone-delete", function(){
            var $this = $(this);
            getPass(function(pass) {
                return ajaxBtn($this, "<?=Url::to(["/ubi/phone/delete"])?>", {phone: $this.attr("data-email"), pass:pass}, function () {
                    formReload();
                });
            });
            return false;
        })

        $("#ubi-change-phone-widget-submit", $form).on("click", function(e){
            getPass(function(pass){
                $("[name=pass]",$form).val(pass);
                $form.submit();
            });
            e.stopImmediatePropagation();
            e.preventDefault();
        })
    })
</script>
<?ActiveForm::end()?>
