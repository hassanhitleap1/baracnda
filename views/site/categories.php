<?php

/** @var yii\web\View $this */


use yii\helpers\Html;
use yii\helpers\Url;


$this->title = 'Categories';
$this->params['breadcrumbs'][] = $this->title;
?>


<section class="container my-5">
    <div class="breadcrumbs mb-3">
        <span>
            <a href="<?= Url::to(['site/index']) ?>" title=" Go to Neutron sys web" class="home">Home</a>
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
        <?php foreach ($categories as $category): ?>

            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <a href="<?= Url::to(["site/category/$category->slug"]) ?>">
                    <div class="card">
                        <div style="height:200px;">
                            <?= Html::img(Yii::getAlias('@web') . "/" . $category->image, ['alt' => 'Card image cap', 'width' => 'auto', 'style' => 'width:100%;']) ?>
                        </div>
                        <div class="card-body text-center">
                            <h5 class="card-title">
                                <?= $category->name_en ?>
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

</section>