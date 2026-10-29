<?php
use yii\bootstrap5\Html;

?>
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button" aria-label="Toggle menu">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto align-items-center">
        <?php if (!Yii::$app->user->isGuest): ?>
            <li class="nav-item d-none d-sm-block">
                <span class="nav-link"><?= Html::encode(Yii::$app->user->identity->full_name ?? Yii::$app->user->identity->username ?? '') ?></span>
            </li>
        <?php endif; ?>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button" aria-label="Full screen">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>
