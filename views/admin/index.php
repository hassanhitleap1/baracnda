<?php

use yii\helpers\Html;
use yii\web\JsExpression;

$this->title = Yii::t('app', 'Admin Dashboard');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="admin-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <?php if (Yii::$app->authManager->checkAccess(Yii::$app->user->id, 'super-admin') ||
                Yii::$app->authManager->checkAccess(Yii::$app->user->id, 'manager') ||
                Yii::$app->authManager->checkAccess(Yii::$app->user->id, 'dataEntry')):?>
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?= Yii::t('app', 'Total Users') ?></h5>
                    <p class="card-text"><?= Html::encode($totalUsers) ?></p>
                </div>
            </div>
        </div>
        <?php endif; ?>
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
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?= Yii::t('app', 'Total Profits') ?></h5>
                    <p class="card-text"><?= Html::encode($totalProfits) ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <h3><?= Yii::t('app', 'Orders Number this month') ?></h3>
            <canvas id="orders-number-this-month"></canvas>
        </div>
        <div class="col-md-6">
            <h3><?= Yii::t('app', 'Orders profits  this month') ?></h3>
            <canvas id="orders-profits-this-month"></canvas>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <h3><?= Yii::t('app', 'Orders Sales  this month') ?></h3>
            <canvas id="orders-sales-this-month"></canvas>
        </div>
    </div>

</div>


<script>
    var ctx = document.getElementById('orders-number-this-month').getContext('2d');
    const dates = <?= json_encode($dates) ?>;
    const dataOrdersNumber = <?= json_encode($dataOrdersNumber) ?>;
    const dataOrdersProfit = <?= json_encode($dataOrdersProfit) ?>;
    const dataTotalSales = <?= json_encode($dataTotalSales) ?>;
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: dates, // Use the correct labels
            datasets: [{
                label: 'Orders Number This Month',
                data: dataOrdersNumber, // Use the correct data
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderWidth: 2,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Orders Over the Last Month'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    var ctxProfits = document.getElementById('orders-profits-this-month').getContext('2d');
    new Chart(ctxProfits, {
        type: 'line',
        data: {
            labels: dates, // Use the correct labels
            datasets: [{
                label: 'Orders Profits This Month',
                data: dataOrdersProfit, // Use the correct data
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderWidth: 2,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Orders Over the Last Month'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    var ctxSales = document.getElementById('orders-sales-this-month').getContext('2d');
    new Chart(ctxSales, {
        type: 'line',
        data: {
            labels: dates, // Use the correct labels
            datasets: [{
                label: 'Orders Sales This Month',
                data: dataTotalSales, // Use the correct data
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderWidth: 2,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Orders Over the Last Month'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
