<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;
?>


<section>
    <div class="container my-5">
        <div class="row">
            <div class="col-4">
                <img src="<?= Yii::getAlias('@web') . "/$model->image" ?>" alt="" width="100%">
            </div>
            <div class="col-8">
                <div>
                    <h2>
                        <?= Html::encode($this->title) ?>
                    </h2>
                </div>
                <div>
                    <?php print($model->desc) ?>
                </div>

            </div>

        </div>
    </div>
</section>