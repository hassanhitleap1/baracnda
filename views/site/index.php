<?php
use yii\helpers\Url;

/** @var yii\web\View $this */

$this->title = 'neutron sys';
?>

<section>
    <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">

            <?php foreach ($sliders as $key => $slider): ?>
                <div class="carousel-item <?= $key == 0 ? 'active' : '' ?>"
                    style="background-image: url(<?= Yii::$app->request->baseUrl . "/$slider->src" ?>);">
                    <!-- <img src="<?= Yii::getAlias('@web') . "/$slider->src" ?>" class="d-block w-100" alt="..."> -->
                    <div class="carousel-caption">
                        <div class="carousel-subtitle">
                            <?= $slider->text ?>
                        </div>
                        <h1 class="carousel-title">
                            <?= $slider->title ?>
                        </h1>
                        <div class="carousel-description mb-3">
                            <?= $slider->sub_title ?>
                        </div>
                        <a href="<?= Url::to(['site/contact']) ?>" class=" btn btn-lg btn-primary px-5">Contact Us</a>
                    </div>
                </div>

            <?php endforeach; ?>

        </div>
        <!-- <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button> -->
    </div>
</section>
<section>
    <div class="container my-5">
        <div class="row">
            <div class="col-12 col-lg-4">
                <img src="<?= Yii::getAlias('@web') . "/$about->image" ?>" alt="" width="100%">
            </div>
            <div class="col-12 col-lg-8">
                <div>
                    <h2>
                        About Neutron sys
                    </h2>
                </div>

                <div>
                    <?php print($about->desc) ?>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="bg-secondary-subtle py-5">
    <div class="container">

        <div class="d-flex gap-3 direction-column-mobile">

            <?php foreach ($categories as $category): ?>
                <div class="position-relative col-12 col-lg-3">
                    <div class="">
                        <a href="<?= Url::to(["site/category/$category->id"]) ?>" class="products-box">
                            <img src="<?= Yii::getAlias('@web') . "/$category->image" ?>" alt="<?= $category->name_en ?>"
                                width="100%" height="100%"/>
                            <div class="read-more">
                                <h5>
                                    <?= $category->name_en ?>
                                </h5>
                                <div>
                                    Read more
                                    <i class="bi bi-arrow-right"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

            <?php endforeach; ?>
            <div class="position-relative">
                <a href="<?= Url::to(["site/categories"]) ?>" class="products-box">
                    <img src="<?= Yii::getAlias('@web') . '/see-more.jpg' ?>" alt="Mechanical Engineering" width="100%" height="100%">
                    <div class="read-more">
                        <div>
                            See more
                            <i class="bi bi-arrow-right"></i>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>