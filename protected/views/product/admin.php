<?php
/**
 * @var View $this
 * @var string $type
 * @var ActiveDataProvider $dataProvider
 * @var ActiveDataProvider $dataProvider1
 */

use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

if ($type == "pizza")
{
    $this->title = 'Піца';
    $s_title = 'піци';
    $title = 'піцу';
}
elseif ($type == "kit")
{
    $this->title = 'Набори';
    $s_title = 'набору';
    $title = 'набір';
}
elseif ($type == "salad")
{
    $this->title = 'Салати';
    $s_title = 'салату';
    $title = 'салат';
}
elseif ($type == "drink")
{
    $this->title = 'Напої';
    $s_title = 'напою';
    $title = 'напій';
}
elseif ($type == "popcorn")
{
    $this->title = 'Попкорн';
    $s_title = 'попкорну';
    $title = 'попкорн';
}

?>

<h1><?=$this->title?></h1>

    <a href="<?=Url::to(['/create/'.$type])?>" class="btn btn-primary">Створити ще</a>

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

        'name:raw:Назва',

        'ingredients:raw:Опис',
        'timeCreate:raw:Дата створення',
        'timeUpdate:raw:Дата редагування',

        [
            'class' => \yii\grid\ActionColumn::className(),
            'header' => 'Дії',
            'buttons' => [
                'view' => function ($url,$model) use ($type) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-eye-open" title="Подивитись"></span>',
                        Url::toRoute(['/view/'.$type.'/'.$model['id']]));
                },
                'update' => function ($url,$model) use ($type) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-pencil" title="Редагувати"></span>',
                        Url::toRoute(['/update/'.$type.'/'.$model['id']]));
                },
                'delete' => function ($url,$model) use ($type, $title) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-trash" title="Видалити"></span>',
                        Url::toRoute(['/delete/'.$type.'/'.$model['id']]), [
                        'data-confirm' => Yii::t('yii', 'Ви дійсно хочете видалити '.$title.': '.$model['name'].'?'),
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

        'name:raw:Назва',

        'ingredients:raw:Опис',
        'timeCreate:raw:Дата створення',
        'timeUpdate:raw:Дата редагування',

        [
            'class' => \yii\grid\ActionColumn::className(),
            'header' => 'Дії',
            'buttons' => [
                'view' => function ($url,$model) use ($type) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-eye-open" title="Подивитись"></span>',
                        Url::toRoute(['/view/'.$type.'/'.$model['id']]));
                },
                'update' => function ($url,$model) use ($type) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-pencil" title="Редагувати"></span>',
                        Url::toRoute(['/update/'.$type.'/'.$model['id']]));
                },
                'delete' => function ($url,$model) use ($type, $title) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-refresh" title="Відновити"></span>',
                        Url::toRoute(['/restore/'.$type.'/'.$model['id']]), [
                        'data-confirm' => Yii::t('yii', 'Ви дійсно хочете відновити '.$title.': "'.$model['name'].'"?'),
                        'data-method' => 'post',
                    ]);
                },
            ],
        ],

    ],
]);
?>