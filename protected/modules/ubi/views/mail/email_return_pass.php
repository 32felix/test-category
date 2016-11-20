<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Roman Royik
 * Date: 1/6/13
 * Time: 2:21 AM
 * @var YiiMailMessage $message
 * @var GlobalUser $gUser
 */
use yii\helpers\Html;
use yii\helpers\Url;

?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv = "Content-Type" content = "text/html; charset=utf-8"/>
		<title></title>
	</head>
	<body style="margin: 0;background-color: #ffffff;">
		<div style ="width: 572px;margin: 10px auto 60px;">
			<?//<div style = "font-size: 12px;font-family: Arial;color: #c8c8c8; text-decoration: underline;text-align: center;cursor: pointer;margin-bottom: 21px;">Смотреть веб-версию письма</div>?>
			<div style="width: 150px;height: 77px;margin: 0 auto 20px;);"></div>
			<div style="background: rgb(211, 227, 230);background: -moz-linear-gradient(90deg, rgb(211, 227, 230) 0%, rgb(239, 245, 246) 100%);background: -webkit-linear-gradient(90deg, rgb(211, 227, 230) 0%, rgb(239, 245, 246) 100%);background: -o-linear-gradient(90deg, rgb(211, 227, 230) 0%, rgb(239, 245, 246) 100%);background: -ms-linear-gradient(90deg, rgb(211, 227, 230) 0%, rgb(239, 245, 246) 100%);background: linear-gradient(180deg, rgb(211, 227, 230) 0%, rgb(239, 245, 246) 100%);width: 100%;height: 6px"></div>
			<div style="width: 480px;border:1px solid #d3e3e6; margin: 0 auto 40px ;padding: 40px 50px 90px 40px">
				<p style="font-weight: bold;font-size: 18px;font-family: Arial;color: #000">Дорогой Пользователь!</p>
				<p style="margin: 25px 0 25px 0;font-family: Arial;font-size: 14px;">Вы восстанавливаете пароль на сайте UkrAutoPortal.com</p>
				<p style="margin: 25px 0 25px 0;font-family: Arial;font-size: 14px;">Мы рады Вашему присутствию на сайте, посвященном здоровому образу жизни и многим аспектам жизни </p>
				<p style="margin: 25px 0 25px 0;font-family: Arial;font-size: 14px;">Чтобы востановить Ваш пароль, пожалуйста, перейдите по ссылке, указанной в этом письме! </p>
				<?= Html::a('Cсылка для восстановления пароля аккаунта',
						Url::to(['/ubi/user/emailConfirmation', 'accessCode'=>$model->accessCode, 'return'=>1], true),
						['style' => 'font-size:14px; cursor: pointer;color:#02a3ff !important;text-decoration: none;']);
				?>
				<p style="margin: 25px 0 25px 0;font-family: Arial;font-size: 14px;">С наилучшими пожеланиями,</p>
				<p style="margin: 25px 0 25px 0;font-family: Arial;font-size: 14px;">Команда LifeGid.com</p>
			</div>
			<div style="overflow: hidden;position: relative;width: 495px;padding: 0 45px 0 40px; ">
				<div style="width: 200px;float: left;">
					<p style="font-size: 12px;font-family: arial;color:#1d1d1d;line-height:5px ">Наши контакти:</p>
					<p style="font-size: 12px;font-family: arial;color:#1d1d1d;line-height:5px">E-mail:<span style="font-weight: bold;!important;font-size: 12px;font-family: arial;color:#1d1d1d;!important;line-height:5px;"> lifegid.com@gmail.com</span> </p>
					<p style="font-size: 12px;font-family: arial;color:#1d1d1d;line-height:5px">Сайт: <a href = "http://lifegid.com" style="font-weight: bold;font-size: 12px;font-family: arial;color:#1d1d1d;line-height:5px;text-decoration: none;"> lifegid.com</span></a>
				</div>
				<div style="width: 130px;float: left;padding: 0 0 0 50px;">
					<p style="font-size: 12px;font-family: arial;color:#1d1d1d;line-height:5px;font-weight: bold; "></p>
					<p style="font-size: 12px;font-family: arial;color:#1d1d1d;line-height:5px;">будем рады помочь</p>
				</div>
				<div style="width: 130px;float: right;padding: 0 0 0 45px;position: relative; overflow: hidden;">
					<p style="font-size: 12px;font-family: arial;color:#1d1d1d;line-height:5px;">© 2008-2013 LifeGid</p>
				</div>
			</div>
		</div>
	</body>
</html>