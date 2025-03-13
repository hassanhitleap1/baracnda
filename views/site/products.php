<?php

/** @var yii\web\View $this */


use app\models\Categories\Categories;
use yii\helpers\Html;
use yii\helpers\Url;


$this->title = $category->name?? '';
$this->params['breadcrumbs'][] = $this->title;


?>
<section class="container my-5">
    <div class="breadcrumbs mb-3">
        <span>
            <a href="<?= Url::to(['site/index']) ?>" title=" Go to Neutron sys web" class="home">Home</a>
        </span>
        <?php if (isset($slug2)): ?>
            <?php $categorySlug = Categories::findOne(['slug' => $slug]); ?>
            <span><i class="fa fa-angle-right"></i></span>
            <span>
                <a href="<?= Url::to(["site/category/$categorySlug->slug"]) ?>" title=" Go to Neutron sys web" class="home">
                    <?= Html::encode($categorySlug->name) ?>
                </a>


            </span>
        <?php endif; ?>

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
        <?php foreach ($products as $product): ?>

            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <a href="<?= Url::to(["site/product/$product->id"]) ?>">
                    <div class="card">
                        <div style="height:200px;">
                            <?= Html::img(Yii::getAlias('@web') . "/" . $product->image, ['alt' => 'Card image cap', 'width' => 'auto', 'style' => 'width:100%;']) ?>
                        </div>
                        <div class="card-body text-center">
                            <h5 class="card-title">
                                <?= $product->name ?>
                            </h5>
                            <p class="card-text mt-4">
                                <button class="btn btn-dark readmore-btn">
                                    Read More
                                    >
                                </button>
                            </p>
                        </div>
                    </div>
                </a>
            </div>


        <?php endforeach; ?>
    </div>
    <!-- <hr /> -->
    <div class="row pagination-container mt-4">
        <?= \yii\bootstrap5\LinkPager::widget([
            'pagination' => $pagination,
        ]); ?>
    </div>
</section>