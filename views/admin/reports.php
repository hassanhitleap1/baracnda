<?php

use app\models\orders\OrdersSearch;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\orders\OrdersSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Reports');
$this->params['breadcrumbs'][] = $this->title;

$model = new OrdersSearch();
?>

<div class="admin-reports">
    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><?= Yii::t('app', 'Filter by Date Range') ?></h4>
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin([
                'action' => ['reports'],
                'method' => 'get',
                'options' => ['data-pjax' => 1],
            ]); ?>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'start_date')->input('date', [
                        'placeholder' => Yii::t('app', 'Start Date'),
                        'class' => 'form-control',
                    ])->label(Yii::t('app', 'Start Date')) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'end_date')->input('date', [
                        'placeholder' => Yii::t('app', 'End Date'),
                        'class' => 'form-control',
                    ])->label(Yii::t('app', 'End Date')) ?>
                </div>
            </div>

            <div class="form-group text-center">
                <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Reset'), ['admin/reports'], ['class' => 'btn btn-outline-secondary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card text-center">
                <div class="card-header bg-success text-white">
                    <h5><?= Yii::t('app', 'Total Profits') ?></h5>
                </div>
                <div class="card-body">
                    <p class="card-text display-4"><?= Html::encode($totalProfits) ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-center">
                <div class="card-header bg-info text-white">
                    <h5><?= Yii::t('app', 'Total Orders') ?></h5>
                </div>
                <div class="card-body">
                    <p class="card-text display-4"><?= Html::encode($countOrders) ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card text-center">
                <div class="card-header bg-warning text-white">
                    <h5><?= Yii::t('app', 'Filtered Profits') ?></h5>
                </div>
                <div class="card-body">
                    <p class="card-text display-4"><?= Html::encode($filteredProfits) ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-center">
                <div class="card-header bg-danger text-white">
                    <h5><?= Yii::t('app', 'Filtered Orders') ?></h5>
                </div>
                <div class="card-body">
                    <p class="card-text display-4"><?= Html::encode($filteredCountOrders) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>