<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AdminLteAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\helpers\Url;

AdminLteAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title>
        <?= Html::encode($this->title) ?>
    </title>



    <!-- تحميل jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- تحميل jQuery UI -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

<!-- تحميل CSS الخاص بـ jQuery UI -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<link href="<?= Url::to('@web/AdminLTE/css/main.css') ?>" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <?php $this->head() ?>
</head>


<body class="hold-transition sidebar-mini layout-fixed">
    <?php $this->beginBody() ?>
    <div class="wrapper">


        <?php include "header.php"; ?>

        <?php include "nav.php"; ?>

        <?php include "sidebar.php"; ?>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <h1 class="m-0">
                                <?= Html::encode($this->title) ?>
                            </h1>
                        </div><!-- /.col -->
                        <div class="col-sm-12">
                            <?php if (!empty($this->params['breadcrumbs'])): ?>
                                <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
                            <?php endif ?>
                            <?= Alert::widget() ?>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <section class="content">
                <div class="container-fluid">
                    <?= $content ?>
                </div>
            </section>
        </div>

        <?php include "footer.php"; ?>

        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>

    </div>

    <!-- تحميل jQuery UI -->
<script src="<?= Yii::getAlias('@web') ?>/AdminLTE/js/main.js?<?=rand()?>" ></script>
    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>