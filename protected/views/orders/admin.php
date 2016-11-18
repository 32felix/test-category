<?php
/**
 * @var View $this
 * @var string $type
 * @var ActiveDataProvider $dataProvider
 * @var ActiveDataProvider $dataProvider1
 */

use app\models\Addresses;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;


$this->title = 'Замовлення';
?>

<h1><?=$this->title?></h1>

<?

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'layout'=>'{items}{pager}',
    'filterModel'=>null,
    'tableOptions' => [
        'class'=>'table table-condensed table-users',
        'style' => 'margin-left: auto;',
    ],
    'columns' => [

        "userName:raw:Ім'я користувача",

        'telephone:raw:Телефон',
        [
            'format' => 'raw',
            'attribute' => 'addressId',
            'value' => function ($model)
            {
                $address = Addresses::findOne([$model['addressId']]);
                return 'вул. ' . $address->street . ', б. ' . $address->build . ($address->flat?', кв. '.$address->flat:'');
            },
            'label' => 'Адреса'
        ],
        'telephone:raw:Телефон',
        'timeCreate:raw:Дата створення',

        [
            'class' => \yii\grid\ActionColumn::className(),
            'header' => 'Дії',
            'buttons' => [
                'view' => function ($url,$model) use ($type) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-eye-open" title="Подивитись"></span>',
                        Url::toRoute(['/view/orders/'.$model['id']]));
                },
                'update' => function ($url,$model) use ($type) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-pencil" title="Редагувати"></span>',
                        Url::toRoute(['/update/orders/'.$model['id']]));
                },
                'delete' => function ($url,$model) use ($type, $title) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-trash" title="Видалити"></span>',
                        Url::toRoute(['/delete/orders/'.$model['id']]), [
                        'data-confirm' => Yii::t('yii', 'Ви дійсно хочете видалити замовлення: '.$model['userName'].'?'),
                        'data-method' => 'post',
                    ]);
                },
            ],
        ],

    ],
]);
?>

<h3>Видалені</h3>


<?

echo GridView::widget([
    'dataProvider' => $dataProvider1,
    'layout'=>'{items}{pager}',
    'filterModel'=>null,
    'tableOptions' => [
        'class'=>'table table-condensed table-users',
        'style' => 'margin-left: auto;',
    ],
    'columns' => [

        "userName:raw:Ім'я користувача",

        'telephone:raw:Телефон',
        [
            'format' => 'raw',
            'attribute' => 'addressId',
            'value' => function ($model)
            {
                $address = Addresses::findOne([$model['addressId']]);
                return 'вул. ' . $address->street . ', б. ' . $address->build . ($address->flat?', кв. '.$address->flat:'');
            },
            'label' => 'Адреса'
        ],
        'telephone:raw:Телефон',
        'timeCreate:raw:Дата створення',

        [
            'class' => \yii\grid\ActionColumn::className(),
            'header' => 'Дії',
            'buttons' => [
                'view' => function ($url,$model) use ($type) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-eye-open" title="Подивитись"></span>',
                        Url::toRoute(['/view/orders/'.$model['id']]));
                },
                'update' => function ($url,$model) use ($type) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-pencil" title="Редагувати"></span>',
                        Url::toRoute(['/update/orders/'.$model['id']]));
                },
                'delete' => function ($url,$model) use ($type, $title) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-refresh" title="Відновити"></span>',
                        Url::toRoute(['/restore/orders/'.$model['id']]), [
                        'data-confirm' => Yii::t('yii', 'Ви дійсно хочете відновити замовлення: "'.$model['userName'].'"?'),
                        'data-method' => 'post',
                    ]);
                },
            ],
        ],

    ],
]);
?>