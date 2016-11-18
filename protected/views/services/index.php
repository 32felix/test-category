<?php
/**
 * @var View $this
 * @var array $model
 * @var string $type
 */
//$this->title = 'My Yii Application';

use app\models\form\ServicesForm;
use yii\web\View;

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
?>

<h1><?=$this->title?></h1>

<div class="services">
    <? foreach ($model as $form):
        /**@var ServicesForm $form*/?>

        <div class="service <?=$type?>">
            <div class="title-services">
                <?= $form->name; ?>
            </div>

            <div class="img">
                <img src="index.php">
            </div>

            <div class="desc">
                <?= $form->description; ?>
            </div>
        </div>

    <? endforeach; ?>
</div>
