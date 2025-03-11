<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\ThemeAsset;
use app\models\Categories\Categories;
use app\models\Pages\Pages;
use app\models\Settings\Settings;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Url;

ThemeAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);

$categories = Categories::find()->where(['category_id' => null])->limit(3)->all();
$settings = Settings::find()->one();
$about = Pages::find()->where(['key' => 'aboutus'])->one();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <title>
        <?= Html::encode($this->title) ?>
    </title>
    <?php $this->head() ?>
</head>

<body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <header>
        <div class="bg-primary text-white">
            <div class="container">
                <div class="py-2 d-none d-md-flex">
                    <div class="flex-grow-1 d-flex gap-3">
                        <div class="d-flex gap-2">
                            <i class="bi bi-geo-alt"></i>
                            <?= $settings->address ?>
                        </div>
                        <div class="d-flex gap-2">
                            <i class="bi bi-telephone"></i>
                            <?= $settings->phone ?>
                        </div>
                    </div>
                    <div class="d-flex gap-3">
                        <a href="<?= $settings->facebook ?>" class="text-white">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="<?= $settings->snapchat ?>" class="text-white">
                            <i class="bi bi-snapchat"></i>
                        </a>
                        <a href="<?= $settings->instagram ?>" class="text-white">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="<?= $settings->tiktok ?>" class="text-white">
                            <i class="bi bi-tiktok"></i>
                        </a>
                        <a href="<?= $settings->youtube ?>" class="text-white">
                            <i class="bi bi-youtube"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <nav class="navbar navbar-expand-lg shadow-sm p-0">
            <div class="container">
                <div class="flex-grow-1 d-block d-lg-none py-2">
                    <a href="<?= Url::to(['site/index']) ?>">
                        <?= Html::img(Yii::getAlias('@web') . '/logo.png', ['alt' => 'Neutron sys logo', 'width' => '140px']) ?>
                    </a>
                </div>
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar"
                    aria-labelledby="offcanvasNavbarLabel">
                    <div class="offcanvas-header">
                        <div class="offcanvas-title" id="offcanvasNavbarLabel">
                            <?= Html::img(Yii::getAlias('@web') . '/logo.png', ['alt' => 'Neutron sys logo']) ?>

                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body align-items-center gap-4">
                        <div class="flex-grow-1 d-none d-lg-block py-2">
                            <a href="<?= Url::to(['site/index']) ?>">
                                <?= Html::img(Yii::getAlias('@web') . '/logo.png', ['alt' => 'Neutron sys logo', 'width' => '140px']) ?>
                            </a>
                        </div>
                        <div class="search-container">
                            <div class="search-box">
                                <button class="btn-search"><i class="fas fa-search"></i></button>
                                <input type="text" class="input-search" value="<?= Yii::$app->request->get('query') ?>"
                                    id="autocomplete" placeholder="Type to Search...">
                            </div>
                            <!-- <form action="" class="search-bar">
                            <input type="search" id="autocomplete" name="search" pattern=".*\S.*" required>
                            <button class="search-btn" type="submit">
                                <span>Search</span>
                            </button>
                        </form> -->
                            <!-- <input type="text" id="autocomplete" class="form-control" placeholder="Search"> -->
                        </div>
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link  <?= Yii::$app->controller->route == 'site/index' ? 'active' : '' ?>"
                                    aria-current="page" href="<?= Url::to(['site/index']) ?>">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link  <?= Yii::$app->controller->route == 'site/about' ? 'active' : '' ?>"
                                    href="<?= Url::to(['site/about']) ?>">About Us</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    Products
                                </a>
                                <ul class="dropdown-menu">
                                    <?php foreach ($categories as $category): ?>

                                        <li><a href="<?= Url::to(["site/category/$category->slug"]) ?>"
                                                class="dropdown-item" href="#">
                                                <?= $category->name_en ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= Url::to(['site/contact']) ?>">Contact Us</a>
                            </li>


                            <?php if (Yii::$app->user->isGuest): ?>
                                <li class="nav-item <?= Yii::$app->controller->route == 'site/login' ? 'active' : '' ?>">
                                    <?= Html::a("Login", ['site/login'], ['class' => "nav-link"]) ?>
                                </li>
                            <?php else: ?>

                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <?php echo Yii::$app->user->identity->username ?>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li class="nav-item">
                                            <a class="dropdown-item  <?= Yii::$app->controller->route == 'admin/index' ? 'active' : '' ?>"
                                                href="<?= Url::to(['admin/']) ?>">admin</a>
                                        </li>
                                        <?php echo '<li class="dropdown-item">'
                                            . Html::beginForm(['/site/logout'], 'post')
                                            . Html::submitButton(
                                                ' log out',
                                                ['class' => 'btn p-0 logout dropdown-item']
                                            )
                                            . \yii\helpers\Html::endForm()
                                            . '</li>'; ?>
                                    </ul>
                                </li>

                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <?= $content ?>

    <footer class="bg-dark">
        <div class="container">
            <div class="py-4">
                <?= Html::img(Yii::getAlias('@web') . '/logo-white.png', ['alt' => 'Neutron sys logo white']) ?>
            </div>
            <hr />
            <div class="row footer-margin mb-5">
                <div class="col-12 col-lg-4 text-white">
                    <div class="footer-about mt-5 mb-3">
                        ABOUT
                    </div>
                    <div>
                        <?php print(substr($about->desc_en, 0, 500))
                            ?>



                    </div>
                </div>
                <div class="col-12 col-lg-4 text-white m-footer-padding">
                    <div class="mt-5 mb-3">
                        CONTACT US
                    </div>
                    <div>
                        <div class="mb-2">
                            Address:
                            <?= $settings->address ?>
                        </div>
                        <div class="mb-2">
                            Office Phone:
                            <?= $settings->phone ?>
                        </div>
                        <div class="mb-2">
                            Mobile:
                            <?= $settings->mobile ?>
                        </div>
                        <div class="mb-2">
                            Email:
                            <?= $settings->email ?>
                        </div>
                        <div class="d-flex gap-3 mt-4">
                            <a href="<?= $settings->facebook ?>" class="text-white">
                                <i class="bi bi-facebook"></i>
                            </a>
                            <a href="<?= $settings->snapchat ?>" class="text-white">
                                <i class="bi bi-snapchat"></i>
                            </a>
                            <a href="<?= $settings->instagram ?>" class="text-white">
                                <i class="bi bi-instagram"></i>
                            </a>
                            <a href="<?= $settings->tiktok ?>" class="text-white">
                                <i class="bi bi-tiktok"></i>
                            </a>
                            <a href="<?= $settings->youtube ?>" class="text-white">
                                <i class="bi bi-youtube"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4 text-white">
                    <div class="mt-5 mb-3">
                        QUICK LINKS
                    </div>
                    <div>
                        <ul class="ps-3">

                            <?php foreach ($categories as $category): ?>

                                <li><a href="<?= Url::to(["site/products/$category->slug"]) ?>" class="dropdown-item"
                                        href="#">
                                        <?= $category->name_en ?>
                                    </a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
            <hr />
            <div class="text-center text-white pb-3">
                Copyright © 2024 sys. All rights reserved.
            </div>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>