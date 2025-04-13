<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var int $totalOrders */
/** @var int $totalProducts */
?>
<div class="seller-reports">
    <h1><?= Yii::t('app', 'Seller Reports') ?></h1>

    <div class="row">
        <div class="col-md-6">
            <h3><?= Yii::t('app', 'Your Products') ?></h3>
            <ul>
                <?php foreach ($products as $product): ?>
                    <li><?= Html::encode($product->name) ?> - <?= Yii::t('app', 'Price:') ?> <?= Html::encode($product->price) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="col-md-6">
            <h3><?= Yii::t('app', 'Your Orders') ?></h3>
            <ul>
                <?php foreach ($orders as $order): ?>
                    <li><?= Yii::t('app', 'Order ID:') ?> <?= Html::encode($order->id) ?> - <?= Yii::t('app', 'Profit:') ?> <?= Html::encode($order->profit) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <h3><?= Yii::t('app', 'Total Profit') ?></h3>
            <p><?= Html::encode($profit) ?></p>
        </div>
    </div>
</div>
