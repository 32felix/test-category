<?php
/**
 * @var View $this
 * @var array $model
 * @var string $type
 */
//$this->title = 'My Yii Application';

use app\components\utils\ImageUtils;
use app\models\form\ServicesForm;
use app\models\Images;
use yii\web\View;

if ($type == "share")
{
    $this->title = 'Акції';
    $s_title = 'акції';
    $title = 'акцію';
    $w = 600;
    $h = 200;
}
elseif ($type == "partner")
{
    $this->title = 'Партнери';
    $s_title = 'партнера';
    $title = 'партнера';
    $w = 250;
    $h = 250;
}
elseif ($type == "new")
{
    $this->title = 'Новини';
    $s_title = 'новини';
    $title = 'новину';
    $w = 170;
    $h = 170;
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
                <? $img = Images::findOne($form->imageId) ?>
                <img src="<?= $img?ImageUtils::genImageUrl($img->id, $img->timeUpdate, $w, $h):''?>">
            </div>

            <div class="desc">
                <?= $form->description; ?>
            </div>
        </div>

    <? endforeach; ?>
</div>
