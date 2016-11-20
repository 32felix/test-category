<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Roman Royik
 * Date: 1/6/13
 * Time: 2:21 AM
 * @var Message $message
 * @var \tit\ubi\model\GlobalUsers $gUser
 * @var View $this
 * @var Mailer $mailer
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\swiftmailer\Mailer;
use yii\swiftmailer\Message;
use yii\web\View;

//$mailer->get = $this->context;
$message->setSubject('Subject test');
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv = "Content-Type" content = "text/html; charset=utf-8"/>
    <title></title>
</head>
<body style="margin: 0;background-color: #ffffff;">
<div style ="width: 572px;margin: 10px auto 60px;">
    <div style="width: 150px;height: 77px;margin: 0 auto 20px;);"></div>
    <div style="background: rgb(211, 227, 230);background: -moz-linear-gradient(90deg, rgb(211, 227, 230) 0%, rgb(239, 245, 246) 100%);background: -webkit-linear-gradient(90deg, rgb(211, 227, 230) 0%, rgb(239, 245, 246) 100%);background: -o-linear-gradient(90deg, rgb(211, 227, 230) 0%, rgb(239, 245, 246) 100%);background: -ms-linear-gradient(90deg, rgb(211, 227, 230) 0%, rgb(239, 245, 246) 100%);background: linear-gradient(180deg, rgb(211, 227, 230) 0%, rgb(239, 245, 246) 100%);width: 100%;height: 6px"></div>
    <div style="width: 480px;border:1px solid #d3e3e6; margin: 0 auto 40px ;padding: 40px 50px 90px 40px">
        <p style="font-weight: bold;font-size: 18px;font-family: Arial;color: #000">Дорогой Пользователь!</p>
        <p style="margin: 25px 0 25px 0;font-family: Arial;font-size: 14px;">Благодарим Вас за регистрацию на сайте Ура!<?//=$site?></p>
        <p style="margin: 25px 0 25px 0;font-family: Arial;font-size: 14px;">Чтобы активировать ваш аккаунт, пожалуйста, перейдите по ссылке, указанной в этом письме! </p>
        <?php //echo Html::a('Cсылка для активирования аккаунта', [Url::toRoute('/ubi/user/activate', ['accessCode' => $model->accessCode()])], array('style' => 'font-size:14px; cursor: pointer;color:#02a3ff !important;text-decoration: none;'))?>
        <?php echo Html::a('Cсылка для активирования аккаунта', Url::to(['/ubi/user/activate', 'accessCode' => $gUser->accessCode, 'user'=>$gUser->id], true), ['style' => 'font-size:14px; cursor: pointer;color:#02a3ff !important;text-decoration: none;']);
        ?>
        <p style="margin: 25px 0 25px 0;font-family: Arial;font-size: 14px;">С наилучшими пожеланиями,</p>
        <?/*<p style="margin: 25px 0 25px 0;font-family: Arial;font-size: 14px;">Команда  <?=$site?></p> */?>
    </div>
    <div style="overflow: hidden;position: relative;width: 495px;padding: 0 45px 0 40px; ">
        <div style="width: 200px;float: left;">
            <p style="font-size: 12px;font-family: arial;color:#1d1d1d;line-height:5px ">Наши контакти:</p>
            <p style="font-size: 12px;font-family: arial;color:#1d1d1d;line-height:5px">Сайт: <a href = "<?//=$site?>" style="font-weight: bold;font-size: 12px;font-family: arial;color:#1d1d1d;line-height:5px;text-decoration: none;"><?//=$site?></span></a>
        </div>
        <div style="width: 130px;float: left;padding: 0 0 0 50px;">
            <p style="font-size: 12px;font-family: arial;color:#1d1d1d;line-height:5px;font-weight: bold; "></p>
            <p style="font-size: 12px;font-family: arial;color:#1d1d1d;line-height:5px;">будем рады помочь</p>
        </div>
        <div style="width: 130px;float: right;padding: 0 0 0 45px;position: relative; overflow: hidden;">
        </div>
    </div>
</div>
</body>
</html>