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

$this->title = 'Відгуки';

?>

<h1><?=$this->title?></h1>

    <a href="<?=Url::to(['/create/reviews'])?>" class="btn btn-primary">Створити ще</a>

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

        'review:raw:Відгук',
        'timeCreate:raw:Дата створення',
        'timeUpdate:raw:Дата редагування',

        [
            'class' => \yii\grid\ActionColumn::className(),
            'header' => 'Дії',
            'buttons' => [
                'view' => function ($url,$model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-eye-open" title="Подивитись"></span>',
                        Url::toRoute(['/view/reviews/'.$model['id']]));
                },
                'update' => function ($url,$model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-pencil" title="Редагувати"></span>',
                        Url::toRoute(['/update/reviews/'.$model['id']]));
                },
                'delete' => function ($url,$model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-trash" title="Видалити"></span>',
                        Url::toRoute(['/delete/reviews/'.$model['id']]), [
                        'data-confirm' => Yii::t('yii', 'Ви дійсно хочете видалити відгук: '.$model['userName'].'?'),
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

        'review:raw:Відгук',
        'timeCreate:raw:Дата створення',
        'timeUpdate:raw:Дата редагування',

        [
            'class' => \yii\grid\ActionColumn::className(),
            'header' => 'Дії',
            'buttons' => [
                'view' => function ($url,$model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-eye-open" title="Подивитись"></span>',
                        Url::toRoute(['/view/reviews/'.$model['id']]));
                },
                'update' => function ($url,$model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-pencil" title="Редагувати"></span>',
                        Url::toRoute(['/update/reviews/'.$model['id']]));
                },
                'delete' => function ($url,$model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-refresh" title="Відновити"></span>',
                        Url::toRoute(['/restore/reviews/'.$model['id']]), [
                        'data-confirm' => Yii::t('yii', 'Ви дійсно хочете відновити відгук: "'.$model['userName'].'"?'),
                        'data-method' => 'post',
                    ]);
                },
            ],
        ],

    ],
]);
?>