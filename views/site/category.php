<?php

/** @var yii\web\View $this */


use yii\helpers\Html;
use yii\helpers\Url;


$this->title = $category->name_en;
$this->params['breadcrumbs'][] = $this->title;
?>


<section class="container my-5">
    <div class="breadcrumbs mb-3">
        <span>
            <a href="<?= Url::to(['site/index']) ?>" title=" Go to Neutron sys web" class="home">Home</a>
        </span>
        <span><i class="fa fa-angle-right"></i></span>
        <span>
            category
        </span>
        <span><i class="fa fa-angle-right"></i></span>
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
        <?php foreach ($categories as $categor): ?>

            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <a href="<?= Url::to(["site/category/$category->id/$categor->id"]) ?>">
                    <div class="card">
                        <div style="height: 200px; text-align: center;">
                            <?= Html::img(Yii::getAlias('@web') . "/" . $categor->image, ['alt' => 'Card image cap', 'width' => 'auto', 'style' => 'max-height: 100%; margin: auto;']) ?>
                        </div>
                        <div class="card-body text-center">
                            <h5 class="card-title">
                                <?= $categor->name?>
                            </h5>
                            <p class="card-text mt-4">
                                <button class="btn btn-dark readmore-btn">
                                    see all product
                                    >
                                </button>
                            </p>
                        </div>
                    </div>
                </a>
            </div>


        <?php endforeach; ?>
    </div>


</section>