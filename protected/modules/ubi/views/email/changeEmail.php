<?php
/**
 * @var $this View
 * @var $model \tit\ubi\model\GlobalUsers
 *
 */
use app\modules\ubi\model\UserMail;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;

/**
 * @var UserMail[] $mails
 * @var \tit\ubi\model\GlobalUsers $model
 * @var \tit\ubi\model\GlobalUsers $gUser
 */
$mails = UserMail::findAll(["user"=>\Yii::$app->user->id]);
?>
<?$form=ActiveForm::begin(["id"=>"ubi-change-email-widget-form", 'fieldConfig'=>["template"=>"{input}\n{error}"], "enableClientValidation"=>false])?>
<input type="hidden" name="pass"/>
<div class="grid-email">
<?foreach($mails as $mail):?>
    <div class="grid-one-email">
        <div class="grid-cell title">
            <p>Email</p>
        </div>
        <div class="grid-cell address">
            <p><?=$mail->address?></p>
        </div>
        <div class="grid-cell confirmed">
            <? if ($mail->timeVerified): ?>
                <span class='confirmed' title="Confirmed at <?=$mail->timeVerified?>">підтверджено</span>
            <? else: ?>
                <span class='not-confirmed' title="Verification mail sent at <?=$mail->timeVerificationSent?>">не підтверджено</span>
            <? endif ?>
        </div>

            <? if ($mail->address==$gUser->email): ?>
                <div class="grid-cell major">
                        <span>oсновний</span>
                </div>
                <div class="grid-cell empty">
                </div>
            <? else: ?>
                <? if ($mail->timeVerified): ?>
                    <div class="grid-cell">
                        <a href="#" class="email-make-primary" data-email="<?=htmlentities($mail->address)?>">зробити основним</a>
                    </div>
                    <? else: ?>
                    <div class="grid-cell empty">
                    </div>
                <? endif ?>
                    <div class="grid-cell">
                        <a href="#" class="email-delete" data-email="<?=htmlentities($mail->address)?>">видалити</a>
                    </div>
            <? endif; ?>
        <div class="grid-cell resent">
            <? if (!$mail->timeVerified): ?>
                <a href="#" class="resend-email-verification" data-email="<?=htmlentities($mail->address)?>">відіслати лист ще раз</a>
            <? endif ?>
        </div>
    </div>
<?endforeach?>
</div>
<div class="grid-email form">
    <div class="grid-one-email">
        <div class="grid-cell title">
        <p>Email</p>
        </div>
        <?=$form->field($model, "email")->textInput(['placeholder'=>'email', 'label'=>'Email'])?>
    </div>
</div>
<div class="for-button">
    <?=\tit\widgets\AjaxSubmit::widget([
        "id"=>"ubi-change-email-widget-submit",
        "url"=>Url::to(["/ubi/email/update"]),
        "htmlOptions"=>["class"=>""],
        "label"=>"Додати",
    ]); ?>
</div>
<!--<div id="successMessage" class="message">
    <?/*=issetdef($msg)*/?>
</div>-->
<script>
    $(function(){
        var $form = $("#ubi-change-email-widget-form");
        function formReload()
        {
            $form.html("<img src='/images/loading.gif'/>")
            jQuery.ajax({
                'type': 'POST',
                'url': '<?=Url::to(["/ubi/email/update"])?>',
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

        $form.on("click", ".email-make-primary", function(){
            var $this = $(this);
            getPass(function(pass)
            {
                return ajaxBtn($this, "<?=Url::to(["/ubi/email/make-primary"])?>", {email:$this.attr("data-email"), pass:pass}, function () {
                    formReload();
                });
            });
            return false;
        })
        $form.on("click", ".email-delete", function(){
            var $this = $(this);
            getPass(function(pass) {
                return ajaxBtn($this, "<?=Url::to(["/ubi/email/delete"])?>", {email: $this.attr("data-email"), pass:pass}, function () {
                    formReload();
                });
            });
            return false;
        })

        $("#ubi-change-email-widget-submit", $form).on("click", function(e){
            getPass(function(pass){
                $("[name=pass]",$form).val(pass);
                $form.submit();
            });
            e.stopImmediatePropagation();
            e.preventDefault();
        })
    })
</script>

<?/*if (!$model->timeEmailVerified):?>
    <div class="msg msg-error">
        Вашу електронну адресу не підтверджено. На електронну адресу <strong><?=$model->email?></strong> було відправлено лист для активації.
        Якщо лист не прийшов протягом 5 хвилин, перегляньте папку зі спамом.
        Також ви можете <a href="#" class="resend-set-mail-mail">відіслати лист ще раз</a>.
    </div>
<?endif*/?>

<?ActiveForm::end()?>
