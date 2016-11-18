<?php

/* @var $this \yii\web\View */

use app\components\utils\GlobalsUtils;
use app\models\form\OrdersForm;
use app\models\Orders;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;
use app\assets\AppAsset;

$ip = OrdersForm::getIp();
$userAgent = GlobalsUtils::issetdef($_SERVER["HTTP_USER_AGENT"]);
$model = Orders::find()->where('ip="'.$ip.'" AND userAgent="'.$userAgent.'" AND status IS NULL')->one();
if ($model)
{
    $sql = "SELECT SUM(IFNULL(PP.price*OP.count,0))
            FROM OrderProducts OP
            LEFT JOIN ProductsPrices PP ON PP.id=OP.productPriceId
            WHERE OP.orderId=".$model->id;
    $count = Yii::$app->db->createCommand($sql)->queryScalar();
}
$count = $count ?? 0;

AppAsset::register($this);

$this->title = 'PizzaTime';
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <div class="container">
        <div class="raw">
            <div class="col-md-3 col-header-logo">
                <div><a href="/"><img src="/images/header/logo.png" class="logo"></a></div>
            </div>
            <div class="raw-mobile">
            <div class="col-md-4 col-xs-6 col-header">
                <div id="phone">
                    <img src="/images/header/phone.png">
                    0 800 23 45 68
                    <div class="point"></div>
                    096 45 12 346
                </div>
                <div id="call-back">Мінімальне замовлення <span class="number-color">50</span> грн</div>
            </div>
            <div class="col-md-3 col-xs-6 col-header">
                <div>
                    <?= Yii::$app->user->isGuest ? (
                        Html::beginForm(['#'], 'post', ['class' => 'navbar-form'])
                        . Html::a('Реєстрація', [Url::toRoute('/site/register')],
                            [
                                'class' => 'btn btn-link',
                                'style' => 'border-right: 1px solid #F6511D',
                            ]
                        )
                        . Html::a('Вхід', [Url::toRoute('/site/login')],
                            ['class' => 'btn btn-link']
                        )
                        . Html::endForm()
                    ) : (
                        Html::beginForm(['/site/logout'], 'post', ['class' => 'navbar-form'])
                        . Html::submitButton('Вихід (' . Yii::$app->user->identity->name . ')',
                            (Yii::$app->user->can('admin')?
                                ['class' => 'btn btn-link', 'style' => 'border-right: 1px solid #F6511D']
                                : ['class' => 'btn btn-link'])
                        )
                        .(Yii::$app->user->can('admin')?Html::a('Адмінка', ['/product/admin', 'type' => 'pizza'],
                            [
                                'class' => 'btn btn-link',
                            ]
                        ):'')
                        . Html::endForm()
                    )
                    ?>
                </div>


                <div id="orders">Приймаємо замовлення з <span class="number-color">10:00</span> до <span class="number-color">22:00</span></div>
            </div>
            </div>
            <div class="col-md-2 col-header">
                <div class="costs">сума <?= sprintf('%.2f', $count) ?> грн</div>
                <div id="bucket" class="<?= $count > 0?'full':'' ?>">
                    Кошик
                    <?= file_get_contents(Yii::$app->basePath . '/../images/header/bucket.svg')?>
                </div>
            </div>
        </div>
    </div>


    <?php
    NavBar::begin([
        'options' => [
            'class' => 'navbar-inverse',
        ],
    ]);

    $items[] = ['label' => 'Піца', 'url' => ['/product/index', 'type' => 'pizza']];
    $items[] = ['label' => 'Набори', 'url' => ['/product/index', 'type' => 'kit']];
    $items[] = ['label' => 'Салати', 'url' => ['/product/index', 'type' => 'salad']];
    $items[] = ['label' => 'Напої', 'url' => ['/product/index', 'type' => 'drink']];
    $items[] = ['label' => 'Попкорн', 'url' => ['/product/index', 'type' => 'popcorn']];
    $items[] = ['label' => 'Доставка', 'url' => ['/site/contact']];
    $items[] = ['label' => 'Акції', 'url' => ['/services/index', 'type' => 'share']];
    $items[] = ['label' => 'Контакти', 'url' => ['/site/contact']]; ?>

    <?= Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $items,
        'activateParents'=>true,
    ]); ?>

    <?NavBar::end(); ?>

    <div class="container" style="margin: 70px auto 30px">
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">Найшвидша доставка піци в місті &copy; <?= date('Y') ?></p>
        <p class="pull-right">Приєднуйтесь до нас
            <a><img src="/images/footer-logo/vk.png" /></a>
            <a><img src="/images/footer-logo/instagram.png" /></a>
            <a><img src="/images/footer-logo/facebook.png" /></a>
        </p>
        <p class="pull">
            <a href="<?= Url::toRoute('/delivery')?>">Доставка</a>
            <a href="<?= Url::toRoute('/contact')?>">Контакти</a>
            <a href="<?= Url::toRoute('/partner')?>">Партнери</a>
            <a href="<?= Url::toRoute('/reviews')?>">Відгуки</a>
        </p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
