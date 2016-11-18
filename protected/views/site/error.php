<?php

use yii\helpers\Html;
use \app\widgets\LessWidget;
use app\models\User;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var string $name
 * @var string $message
 * @var Exception $exception
 */

$user = \Yii::$app->user->identity;
if ($exception->statusCode == 404)
    $this->title = 'Помилка 404, сторінка не знайдена';
else
    $this->title = $name;
?>
<?


?>
<div class="site-error">

    <div class="container">
        <h1><?= Html::encode($this->title) ?></h1>

        <hr>

        <?if (isset($exception->statusCode) && $exception->statusCode == 404):?>
            <p>
                Ти шукав неіснуючу сторінку, або довго не проявляв активності на сайті та був вилогінений системою, увійди у власний обліковий запис ще раз.
            </p>

        <?else:?>
            <div class="alert alert-danger">
                <?= nl2br(Html::encode($message)) ?>
            </div>

            <p>
                The above error occurred while the Web server was processing your request.
            </p>
            <p>
                Please contact us if you think this is a server error. Thank you.
            </p>
        <?endif;?>
        
    </div>
</div>
