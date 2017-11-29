<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'Categories';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    #add-text {
        cursor: pointer;
    }
</style>

<div class="category-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Category', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= $category ? $category : '' ?>
</div>

<script>
    $(function() {
        $('#add-text').click(function() {
            if ($(this).hasClass('glyphicon-plus-sign'))
            {
                $(this).removeClass('glyphicon-plus-sign');
                $(this).addClass('glyphicon-minus-sign');
                $(this).closest('tr').next('.children').removeClass('hidden');
            }
            else
            {
                $(this).addClass('glyphicon-plus-sign');
                $(this).removeClass('glyphicon-minus-sign');
                $(this).closest('tr').next('.children').addClass('hidden');
            }
        })
    })
</script>