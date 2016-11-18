<?php
/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 * @var ActiveDataProvider $dataProvider1
 */

use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

$this->title = 'Тексти сторінок';

$paramKey = [
    'mainPage' => 'Головна сторінка',
    'delivery' => 'Доставка',
    'contact' => 'Контакти',
    'phone' => 'Телефон у верху сторінок',
    'work' => 'Термін роботи',
]
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
        [
            'attribute' => 'key',
            'label' => 'Сторінка',
            'value' => function ($model) use ($paramKey) {
                return $paramKey[$model['key']];
            }
        ],

        'value',

        [
            'class' => \yii\grid\ActionColumn::className(),
            'template' => '{update} {delete}',
            'header' => 'Дії',
            'buttons' => [
                'view' => function ($url,$model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-eye-open" title="Подивитись"></span>',
                        Url::toRoute(['/view/params/'.$model['id']]));
                },
                'update' => function ($url,$model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-pencil" title="Редагувати"></span>',
                        Url::toRoute(['/update/params/'.$model['id']]));
                },
                'delete' => function ($url,$model) use ($paramKey) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-trash" title="Видалити"></span>',
                        Url::toRoute(['/delete/params/'.$model['id']]), [
                        'data-confirm' => Yii::t('yii', 'Ви дійсно хочете видалити текст на сторінці: '.$paramKey[$model['key']].'?'),
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
        [
            'attribute' => 'key',
            'label' => 'Сторінка',
            'value' => function ($model) use ($paramKey) {
                return $paramKey[$model['key']];
            }
        ],

        'value',

        [
            'class' => \yii\grid\ActionColumn::className(),
            'header' => 'Дії',
            'buttons' => [
                'view' => function ($url,$model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-eye-open" title="Подивитись"></span>',
                        Url::toRoute(['/view/params/'.$model['id']]));
                },
                'update' => function ($url,$model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-pencil" title="Редагувати"></span>',
                        Url::toRoute(['/update/params/'.$model['id']]));
                },
                'delete' => function ($url,$model) use ($paramKey) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-refresh" title="Відновити"></span>',
                        Url::toRoute(['/restore/params/'.$model['id']]), [
                        'data-confirm' => Yii::t('yii', 'Ви дійсно хочете відновити текст на сторінці: "'.$paramKey[$model['key']].'"?'),
                        'data-method' => 'post',
                    ]);
                },
            ],
        ],

    ],
]);
?>