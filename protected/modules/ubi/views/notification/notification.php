<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016-08-02
 * Time: 11:38
 */
namespace tit\ubi\views;
use app\models\User;
use app\modules\ubi\model\UserMail;
use yii\helpers\Url;
use tit\ubi\model\GlobalUsers;
?>
<?
$errors = [];
$id = \Yii::$app->user->getId();
$guser = GlobalUsers::findOne(["id" => \Yii::$app->user->getId()]);

if (!$guser->email)
        array_push($errors, "Для повноцінного користування ресурсом тобі потрібно <a class='add-mail'>додати</a> електронну адресу.");
else {
        $mainMail = $guser->email;
        $mainNotVerified = UserMail::find()
            ->where(["user"=>$id])
            ->andWhere(["address"=>$mainMail])
            ->andWhere("timeVerified IS NULL")
            ->one();
        $allMails = UserMail::find()
            ->where(["user"=>$id])
            ->andWhere("timeVerified IS NULL")
            ->all();
        if($mainNotVerified)
                array_push($errors, "Твою електронну адресу <span>".$mainMail."</span> не підтверджено. 
                                <br>
                                На цю адресу було відправлено листа з інструкціями для підтвердження.
                                <br>
                                Якщо лист не надійшов, ти можеш 
                                <a class='resend-email-verification' data-email=".$mainMail.">відправити його ще раз</a>.
                                <br>
                                Змінити свою електронну адресу можна в <a href=".Url::to(['/profile/profile/cabinet']).">особистому кабінеті</a>.");
        foreach($allMails as $mail){
                if($mail->address != $mainMail)
                        array_push($errors, "Твою електронну адресу <span>".$mail->address."</span> не підтверджено.
                        <br>
                         На цю адресу було відправлено листа з інструкціями для підтвердження.
                               <br>
                         Якщо лист не надійшов, ти можеш 
                        <a class='resend-email-verification' data-email=".$mail->address.">відправити його ще раз</a>.
                        <br>
                        Ти можеш 
                        <a class='email-delete' data-email=".$mail->address.">видалити</a> цю адресу.
                        <br>
                        Для здійснення інших операцій перейди до <a href=".Url::to(['/profile/profile/cabinet']).">особистого кабінету</a>");
        }
}
$errors = array_filter($errors);
?>
<?if (!empty($errors)): ?>
        <div class="main-notification">
                <p><?=$errors[0]?></p>
        </div>
<?endif;?>
<script>
        $(function () {
                var $form = $("#ubi-change-email-widget-form");
                var notificationWindow = $('.main-notification');
                var _pass;
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
                        jQuery.ajax({
                                'type': 'POST',
                                'url': '<?=Url::to(["/ubi/notification/update"])?>',
                        }).done(function (data, textStatus, jqXHR)
                        {
                                notificationWindow.replaceWith(data);
                        }).fail(function (jqXHR, textStatus, errorThrown) {
                                notificationWindow.html("Error: " + jqXHR.status + " " + jqXHR.statusText);
                        });
                }
                function getPass(done)
                {
                                swal({
                                        title: "Потрібен пароль!",
                                        text: "Введіть свій пароль:",
                                        type: "input",
                                        showCancelButton: true,
                                        animation: "slide-from-top",
                                        inputPlaceholder: "password",
                                        inputType:"password",
                                }, function(inputValue){
                                        debugger;
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
                $('.add-mail').click(function (e) {
                        RPopup.callPopup( "/ubi/email/add-email-popup", {onClose: function () {
                                formReload();
                        }});
                });
                $(document).on("click", ".resend-email-verification", function () {
                        var $this = $(this);
                        return ajaxBtn($this, "<?=Url::to(["/ubi/email/resend-confirmation"])?>", {email:$this.attr("data-email")}, function () {
                        });
                });
                $(document).on("click", ".email-delete", function(){
                        var $this = $(this);
                        getPass(function(pass) {
                                debugger;
                                return ajaxBtn($this, "<?=Url::to(["/ubi/email/delete"])?>", {email: $this.attr("data-email"), pass:pass}, function () {
                                        formReload();
                                });
                        });
                        return false;
                })
        });
</script>
