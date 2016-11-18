<?php
/**
 * @var View $this
 * @var array $model
 */
//$this->title = 'My Yii Application';

use app\models\form\ReviewsForm;
use yii\web\View;

$this->title = 'Відгуки';

?>

<h1><?=$this->title?></h1>

<div class="reviews">
    <? foreach ($model as $form):
        /**@var ReviewsForm $form*/?>
        <div class="img">
            <img src="index.php">
        </div>

        <div class="review">
            <div class="title-review">
                <?= $form->userName; ?>
            </div>

            <div class="desc">
                <?= $form->review; ?>
            </div>
        </div>

    <? endforeach; ?>
</div>
