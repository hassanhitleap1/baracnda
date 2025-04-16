<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\orders\OrdersSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Reports');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="admin-reports">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-6">
            <h3><?= Yii::t('app', 'Total Profits') ?></h3>
            <p><?= Html::encode($totalProfits) ?></p>
        </div>
        <div class="col-md-6">
            <h3><?= Yii::t('app', 'Total Orders') ?></h3>
            <p><?= Html::encode($countOrders) ?></p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <h3><?= Yii::t('app', 'Filtered Profits') ?></h3>
            <p><?= Html::encode($filteredProfits) ?></p>
        </div>
        <div class="col-md-6">
            <h3><?= Yii::t('app', 'Filtered Orders') ?></h3>
            <p><?= Html::encode($filteredCountOrders) ?></p>
        </div>
    </div>
</div>