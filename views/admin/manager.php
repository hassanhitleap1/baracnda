<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var int $totalOrders */
/** @var int $totalProducts */
?>
<div class="manager-reports">
    <h1><?= Yii::t('app', 'Manager Reports') ?></h1>

    <div class="row">
        <div class="col-md-6">
            <h3><?= Yii::t('app', 'Total Orders') ?></h3>
            <p><?= Html::encode($totalOrders) ?></p>
        </div>
        <div class="col-md-6">
            <h3><?= Yii::t('app', 'Total Products') ?></h3>
            <p><?= Html::encode($totalProducts) ?></p>
        </div>
    </div>
</div>
