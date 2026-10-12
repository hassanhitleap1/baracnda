<?php

use yii\helpers\Html;
use yii\web\JsExpression;

$this->title = Yii::t('app', 'Admin Dashboard');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="admin-index">

    <section class="kpi-ledger" aria-label="<?= Yii::t('app', 'Totals') ?>">
        <?php if (Yii::$app->authManager->checkAccess(Yii::$app->user->id, 'super-admin') ||
                Yii::$app->authManager->checkAccess(Yii::$app->user->id, 'manager') ||
                Yii::$app->authManager->checkAccess(Yii::$app->user->id, 'dataEntry')):?>
        <div class="kpi">
            <p class="kpi-label"><?= Yii::t('app', 'Total Users') ?></p>
            <p class="kpi-value"><?= Html::encode($totalUsers) ?></p>
        </div>
        <?php endif; ?>
        <div class="kpi">
            <p class="kpi-label"><?= Yii::t('app', 'Total Orders') ?></p>
            <p class="kpi-value"><?= Html::encode($totalOrders) ?></p>
        </div>
        <div class="kpi">
            <p class="kpi-label"><?= Yii::t('app', 'Total Products') ?></p>
            <p class="kpi-value"><?= Html::encode($totalProducts) ?></p>
        </div>
        <div class="kpi">
            <p class="kpi-label"><?= Yii::t('app', 'Total Profits') ?></p>
            <p class="kpi-value is-money"><?= Html::encode($totalProfits) ?></p>
        </div>
    </section>

    <div class="row">
        <div class="col-lg-6">
            <div class="chart-panel">
                <h3><?= Yii::t('app', 'Orders this month') ?></h3>
                <canvas id="orders-number-this-month"></canvas>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="chart-panel">
                <h3><?= Yii::t('app', 'Profits this month') ?></h3>
                <canvas id="orders-profits-this-month"></canvas>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="chart-panel">
                <h3><?= Yii::t('app', 'Sales this month') ?></h3>
                <canvas id="orders-sales-this-month"></canvas>
            </div>
        </div>
    </div>

</div>


<script>
    if (Chart.defaults && Chart.defaults.font) {
        Chart.defaults.font.family = getComputedStyle(document.body).fontFamily;
        Chart.defaults.color = '#5f7175';
    }
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
                borderColor: '#0f7c80',
                backgroundColor: 'rgba(15, 124, 128, 0.08)',
                borderWidth: 2,
                pointRadius: 0,
                tension: 0.3,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: { display: false }
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
                borderColor: '#0f7c80',
                backgroundColor: 'rgba(15, 124, 128, 0.08)',
                borderWidth: 2,
                pointRadius: 0,
                tension: 0.3,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: { display: false }
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
                borderColor: '#0f7c80',
                backgroundColor: 'rgba(15, 124, 128, 0.08)',
                borderWidth: 2,
                pointRadius: 0,
                tension: 0.3,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
