<?php
/**
 * @var View $this
 * @var string $type
 * @var Products $model
 * @var SqlDataProvider $dataProviderPrice
 */


use app\models\Products;
use yii\data\SqlDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

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

$this->title = 'Перегляд '.$s_title;
?>

<h1><?= $this->title ?></h1>

<?= Html::a('Редагувати', ['/update/'.$type.'/'.$model->id], ['class' => 'btn btn-primary', 'style' => 'margin-right: 10px']); ?>

<?= Html::a(($model->deleted > 0)?'Відновити':'Видалити', [($model->deleted > 0)?('/restore/'.$type.'/'.$model->id):('/delete/'.$type.'/'.$model->id)], [
    'class' => 'btn btn-danger',
    'data' => [
        'confirm' => 'Ви впевнені, що хочете '.(($model->deleted > 0)?'відновити':'видалити').' '.$title.': '.$model->name.'?',
        'method' => 'post',
    ],
]) ?>
<br><br>
<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        [
            'label' => 'Назва '.$s_title,
            'value' => $model->name,
        ],

        [
            'label' => 'Інгредієнти '.$s_title,
            'value' => $model->ingredients,
        ],

        [
            'label' => 'ID картинки '.$s_title,
            'value' => $model->imageId,
        ],
        'timeCreate',
        'timeUpdate',
        'deleted',
    ],
]);
?>

<?= GridView::widget([
    'dataProvider' => $dataProviderPrice,
    'layout'=>'{items}{pager}',
    'filterModel'=>null,
    'tableOptions' => [
        'class'=>'table table-condensed table-users',
        'style' => 'margin-left: auto;',
    ],
    'columns' => [

        'size:raw:Розмір/Вага '.$s_title,
        'price:raw:Ціна '.$s_title.' в грн',
        'countMen:raw:Кількість людей',

    ],
]);
?>
