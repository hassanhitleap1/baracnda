<?php

use yii\helpers\Html;
use yii\web\JsExpression;

$this->title = Yii::t('app', 'Admin Dashboard');
$this->params['breadcrumbs'][] = $this->title;

// Example data for charts (replace with actual data from the controller)
$ordersData = json_encode($ordersData);
$productsData = json_encode($productsData);
$usersData = json_encode($usersData);

?>
<div class="admin-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?= Yii::t('app', 'Total Users') ?></h5>
                    <p class="card-text"><?= Html::encode($totalUsers) ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?= Yii::t('app', 'Total Orders') ?></h5>
                    <p class="card-text"><?= Html::encode($totalOrders) ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?= Yii::t('app', 'Total Products') ?></h5>
                    <p class="card-text"><?= Html::encode($totalProducts) ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <h3><?= Yii::t('app', 'Orders Chart') ?></h3>
            <canvas id="ordersChart"></canvas>
        </div>
        <div class="col-md-6">
            <h3><?= Yii::t('app', 'Products Chart') ?></h3>
            <canvas id="productsChart"></canvas>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <h3><?= Yii::t('app', 'Users Chart') ?></h3>
            <canvas id="usersChart"></canvas>
        </div>
    </div>

</div>

<?php
$this->registerJs(new JsExpression("
    const ordersCtx = document.getElementById('ordersChart').getContext('2d');
    const productsCtx = document.getElementById('productsChart').getContext('2d');
    const usersCtx = document.getElementById('usersChart').getContext('2d');

    new Chart(ordersCtx, {
        type: 'line',
        data: $ordersData,
        options: {
            responsive: true,
            plugins: {
                legend: { display: true },
                title: { display: true, text: 'Orders Over Time' }
            }
        }
    });

    new Chart(productsCtx, {
        type: 'bar',
        data: $productsData,
        options: {
            responsive: true,
            plugins: {
                legend: { display: true },
                title: { display: true, text: 'Products by Category' }
            }
        }
    });

    new Chart(usersCtx, {
        type: 'pie',
        data: $usersData,
        options: {
            responsive: true,
            plugins: {
                legend: { display: true },
                title: { display: true, text: 'Users by Role' }
            }
        }
    });
"));
?>