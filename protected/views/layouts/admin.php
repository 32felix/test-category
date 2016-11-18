<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

$count = 0;

AppAsset::register($this);

$this->title = 'PizzaKP';
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap" style="padding-top: 70px">

    <?php
    NavBar::begin([
        'options' => [
            'class' => 'navbar-inverse admin',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Піца', 'url' => ['/admin/pizza']],
            ['label' => 'Набори', 'url' => ['/admin/kit']],
            ['label' => 'Салати', 'url' => ['/admin/salad']],
            ['label' => 'Напої', 'url' => ['/admin/drink']],
            ['label' => 'Попкорн', 'url' => ['/admin/popcorn']],
            ['label' => 'Акції', 'url' => ['/admin/share']],
            ['label' => 'Новини', 'url' => ['/admin/new']],
            ['label' => 'Відгуки', 'url' => ['/admin/reviews']],
            ['label' => 'Партнери', 'url' => ['/admin/partner']],
            ['label' => 'Замовлення', 'url' => ['/admin/orders']],
            ['label' => 'Інше редагування', 'url' => ['/admin/params']],
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; PizzaKP, <?= date('Y') ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
