<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $model->name_en;
$this->params['breadcrumbs'][] = $this->title;
?>


<section>
    <div class="container my-5">
        <div class="breadcrumbs mb-3">
            <span>
                <a href="<?= Url::to(['site/index']) ?>" title=" Go to Neutron sys web" class="home">Home</a>
            </span>

            <span><i class="fa fa-angle-right"></i></span>
            <?php if ($model->category->category): ?>

                <a href="<?= Url::to(["site/category/" . $model->category->category->slug]) ?>"
                    title=" Go to Neutron sys web">
                    <?= Html::encode($model->category->category->name) ?>
                </a>
                <span><i class="fa fa-angle-right"></i></span>

            <?php endif; ?>

            <?php if ($model->category): ?>


                <a href="<?= Url::to(["site/category/" . $model->category->slug]) ?>" title=" Go to Neutron sys web">
                    <?= Html::encode($model->category->name) ?>
                </a>
                <span><i class="fa fa-angle-right"></i></span>

            <?php endif; ?>

            <span>
                <?= Html::encode($this->title) ?>
            </span>
        </div>
        <div class="mb-5">
            <h1 class="page_title">
                <?= Html::encode($this->title) ?>
            </h1>
        </div>

        <div class="row">
            <div class="col-4">

                <?= Html::img(Yii::getAlias('@web') . "/" . $model->image, ['width' => "100%"]) ?>


            </div>
            <div class="col-8">
                <div>
                    <h2>
                        <?= Html::encode($this->title) ?>
                    </h2>
                </div>
                <div>
                    <?php print($model->desc_en) ?>
                </div>

            </div>

        </div>
    </div>
</section>