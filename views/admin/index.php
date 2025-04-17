<?php

use yii\helpers\Html;
use yii\web\JsExpression;

$this->title = Yii::t('app', 'Admin Dashboard');
$this->params['breadcrumbs'][] = $this->title;



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
            <h3><?= Yii::t('app', 'Orders Last Month') ?></h3>
            <canvas id="orders-last-month"></canvas>
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

    <div class="row">
        <div class="col-md-6">
            <h3><?= Yii::t('app', 'Profits Chart') ?></h3>
            <canvas id="profitsChart"></canvas>
        </div>
        <div class="col-md-6">
            <h3><?= Yii::t('app', 'Sales Chart') ?></h3>
            <canvas id="salesChart"></canvas>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('orders-last-month').getContext('2d');
    const labelsOrdersLastMonth = <?= json_encode($labelsOrdersLastMonth) ?>;
    const dataOrdersLastMonth = <?= json_encode($dataOrdersLastMonth) ?>;
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labelsOrdersLastMonth, // Use the correct labels
            datasets: [{
                label: 'Orders Last Month',
                data: dataOrdersLastMonth, // Use the correct data
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
