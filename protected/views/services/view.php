<?php
/**
 * @var View $this
 * @var string $type
 * @var Services $model
 * @var SqlDataProvider $dataProviderPrice
 */


use app\models\Services;
use yii\data\SqlDataProvider;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

if ($type == "share")
{
    $this->title = 'Акції';
    $s_title = 'акції';
    $title = 'акцію';
}
elseif ($type == "partner")
{
    $this->title = 'Партнери';
    $s_title = 'партнера';
    $title = 'партнера';
}
elseif ($type == "new")
{
    $this->title = 'Новини';
    $s_title = 'новини';
    $title = 'новину';
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
            'label' => 'Опис '.$s_title,
            'value' => $model->description,
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
