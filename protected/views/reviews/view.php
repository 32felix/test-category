<?php
/**
 * @var View $this
 * @var Partners $model
 */


use app\models\Partners;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

$this->title = 'Перегляд відгуку';
?>

<h1><?= $this->title ?></h1>

<?= Html::a('Редагувати', ['/update/review/'.$model->id], ['class' => 'btn btn-primary', 'style' => 'margin-right: 10px']); ?>

<?= Html::a(($model->deleted > 0)?'Відновити':'Видалити', [($model->deleted > 0)?('/restore/review/'.$model->id):('/delete/review/'.$model->id)], [
    'class' => 'btn btn-danger',
    'data' => [
        'confirm' => 'Ви впевнені, що хочете '.(($model->deleted > 0)?'відновити':'видалити').' відгук: '.$model->name.'?',
        'method' => 'post',
    ],
]) ?>
<br><br>
<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'userName',
        'review',
        'userId',
        'imageId',
        'timeCreate',
        'timeUpdate',
        'deleted',
    ],
]);
?>

