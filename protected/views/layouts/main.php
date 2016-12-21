<?php

/* @var $this \yii\web\View */

use app\components\utils\GlobalsUtils;
use app\components\utils\ParamsUtils;
use app\models\form\OrdersForm;
use app\models\Orders;
use app\models\Params;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;
use app\assets\AppAsset;

$start = ParamsUtils::selectParam('workStart', '00:00');
$finish = ParamsUtils::selectParam('workFinish', '23:59');
$phone = ParamsUtils::selectParam('phone', null);
$phone2 = ParamsUtils::selectParam('phone2', null);
$minOrder = ParamsUtils::selectParam('minOrder', 0);


$ip = OrdersForm::getIp();
$userAgent = GlobalsUtils::issetdef($_SERVER["HTTP_USER_AGENT"]);
$model = Orders::find();

if ($userId = Yii::$app->user->getId())
    $model->andWhere(['userId' => $userId]);
else
    $model->andWhere('ip="'.$ip.'" AND userAgent="'.$userAgent.'" AND status IS NULL');

$model = $model->one();
$orders = [];
if ($model)
{
    $sql = "SELECT SUM(IFNULL(PP.price*OP.count,0)) as `sum`, P.name, P.type, OP.count, OP.id, PP.price, PS.size
            FROM OrderProducts OP
            LEFT JOIN ProductsPrices PP ON PP.id=OP.productPriceId
            LEFT JOIN ProductsSize PS ON PS.id=PP.sizeId
            LEFT JOIN Products P ON P.id=PP.productId
            WHERE OP.orderId=".$model->id."
            GROUP BY OP.id";
    $orders = Yii::$app->db->createCommand($sql)->queryAll();
}

if (!empty($orders))
{
    $count = ArrayHelper::getColumn($orders, 'sum');
    $count = array_sum($count);
}
$count = !empty($count)?$count:0;

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
                    <?if ($phone || $phone2):?>
                        <img src="/images/header/phone.png">
                    <?endif;?>
                    <?= $phone?$phone:'' ?>
                    <?if ($phone && $phone2):?>
                        <div class="point"></div>
                    <?endif;?>
                    <?= $phone2?$phone2:'' ?>
                </div>
                <div id="call-back">Мінімальне замовлення <span class="number-color"><?= $minOrder ?></span> грн</div>
            </div>
            <div class="col-md-3 col-xs-6 col-header">
                <div>
                    <?= Yii::$app->user->isGuest ? (
                        Html::beginForm(['#'], 'post', ['class' => 'navbar-form'])
                        . Html::a('Реєстрація', [Url::toRoute('/register')],
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
                        Html::beginForm(['/logout'], 'post', ['class' => 'navbar-form'])
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


                <div id="orders">Приймаємо замовлення з <span class="number-color"><?= $start ?></span> до <span class="number-color"><?= $finish ?></span></div>
            </div>
            </div>
            <div class="col-md-2 col-header">
                <div class="costs">сума <?= sprintf('%.2f', $count) ?> грн</div>
                <div class="bucket">
                    <div id="bucket" class="<?= $count > 0?'full':'' ?>">
                        Кошик
                        <?= file_get_contents(Yii::$app->basePath . '/../images/header/bucket.svg')?>
                    </div>
                    <div class="bucket-hidden hidden">
                        <img src="/images/header/logo-bucket.png" />
                        <? if ($count > 0): ?>
                            <div class="products-bucket">
                                <? foreach ($orders as $order): ?>
                                    <div class="product-bucket" data-id="<?= $order['id'] ?>">
                                        <div class="bucket-title">
                                            <span><?= $order['name'] . ($order['size']?'<br>'.$order['size']:'') ?></span><br><?= sprintf('%.2f', $order['price']) ?> грн
                                        </div>
                                        <div>
                                            <div class="count-bucket">

                                                <img class="bucket-minus" src="/images/header/bucket-count.png" />
                                                <span><?= $order['count'] ?></span>
                                                <img class="bucket-plus" src="/images/header/bucket-count.png" />
                                            </div>
                                        </div>
                                        <div>
                                            <div class="delete-bucket">
                                                <img src="/images/header/bucket-delete.png" />
                                            </div>
                                        </div>
                                    </div>
                                <? endforeach; ?>
                            </div>
                            <div class="sum-bucket">
                                <p class="pull-left">Сума:</p>
                                <p class="pull-right"><?= sprintf('%.2f', $count) ?> грн</p>
                            </div>
                            <div class="order-bucket">
                                <a href="<?= Url::toRoute('/orders/create') ?>">Оформити замовлення</a>
                            </div>
                        <? else: ?>
                            <p>Кошик пустий</p>
                        <? endif; ?>
                    </div>
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
    $items[] = ['label' => 'Доставка', 'url' => ['/site/delivery']];
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

<script>
    $(function () {
        $('#bucket').click(function () {
            $(this).siblings('.bucket-hidden').toggleClass('hidden');
        });

        $('.bucket-hidden').on('click', '.count-bucket img', function () {
            var $this = $(this),
                id = $this.closest('.product-bucket').attr('data-id'),
                factor,
                count = $this.siblings('span').text();

            if ($this.attr('class') == 'bucket-minus')
            {
                factor = -1;
            }
            else
            {
                factor = 1;
            }

            if (count != 1 || factor != -1)
            {


                $.ajax({
                    url: "/orders/add-count",
                    data: {
                        id: id,
                        factor: factor
                    },
                    type: "POST",
                    dataType: 'json'
                }).done(function (data) {
                    if (data.error)
                    {
                        alert(data.error);
                    }
                    else if (data.status=="ok") {
                        $this.siblings('span').text(data.count);
                        $this.closest('.bucket-hidden').find('.sum-bucket .pull-right').text(data.sum+' грн');
                        $this.closest('.bucket').siblings('.costs').text('сума '+data.sum+' грн');
                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    alert("Невозможно сохранить: Ошибка: " + jqXHR.status + " " + jqXHR.statusText);
                });
            }
        });

        $('.bucket-hidden').on('click', '.delete-bucket img', function () {
            var $this = $(this),
                id = $this.closest('.product-bucket').attr('data-id');

            $.ajax({
                url: "/orders/delete-price-id",
                data: {
                    id: id
                },
                type: "POST",
                dataType: 'json'
            }).done(function (data) {
                if (data.error)
                {
                    alert(data.error);
                }
                else if (data.status=="ok")
                {
                    $this.closest('.bucket-hidden').find('.sum-bucket .pull-right').text(data.sum+' грн');
                    $this.closest('.bucket').siblings('.costs').text('сума '+data.sum+' грн');

                    if (data.close == 'one')
                        $this.closest('.product-bucket').remove();
                    else
                    {
                        $this.closest('.bucket-hidden').siblings('div').removeClass('full');
                        $this.closest('.products-bucket').siblings('div').remove();
                        $this.closest('.products-bucket').replaceWith('<p>Кошик пустий</p>');
                    }
                }
            }).fail(function (jqXHR, textStatus, errorThrown) {
                alert("Невозможно сохранить: Ошибка: " + jqXHR.status + " " + jqXHR.statusText);
            });
        });

        var height = $('.navbar-inverse').offset().top;

        if ($(this).scrollTop() + 10 >= height)
        {
            $('.navbar-inverse').css({
                'position': 'fixed',
                'top': '10px'
            });
        }

        $(document).scroll(function () {
            if ($(this).scrollTop() + 10 >= height)
            {
                $('.navbar-inverse').css({
                    'position': 'fixed',
                    'top': '10px'
                });
            }

            if ($(this).scrollTop() + 10 < height)
            {
                $('.navbar-inverse').css({
                    'position': 'absolute',
                    'top': height+'px',
                });
            }

        })
    })
</script>