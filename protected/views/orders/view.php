<?php
/**
 * @var View $this
 * @var string $type
 * @var Orders $model
 * @var SqlDataProvider $dataProviderPrice
 */


use app\models\Orders;
use yii\data\SqlDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

$this->title = 'Перегляд замовлення';
?>

<h1><?= $this->title ?></h1>

<?= Html::a('Редагувати', ['/update/orders/'.$model->id], ['class' => 'btn btn-primary', 'style' => 'margin-right: 10px']); ?>

<?= Html::a(($model->deleted > 0)?'Відновити':'Видалити', [($model->deleted > 0)?('/restore/orders/'.$model->id):('/delete/orders/'.$model->id)], [
    'class' => 'btn btn-danger',
    'data' => [
        'confirm' => 'Ви впевнені, що хочете '.(($model->deleted > 0)?'відновити':'видалити').' замовлення: '.$model->userName.'?',
        'method' => 'post',
    ],
]) ?>
<br><br>
<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'userName',
        'userAgent',
        'telephone',
        'email',
        'ip',
        'status',
        [
            'label' => 'Адреса',
            'value' => \app\models\Addresses::findOne($model->addressId),
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

        [
            'label' => 'Замовлений продукт',
            'format' => 'raw',
            'value' => function ($model) {
                return $model['name'] . ', ' . $model['size'] . ' (' . $model['price'] . ')';
            }
        ],
        'count:raw:Кількість',

    ],
]);
?>



