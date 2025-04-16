<?php

use app\models\orders\OrdersSearch;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\orders\OrdersSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Reports');
$this->params['breadcrumbs'][] = $this->title;

use kartik\daterange\DateRangePicker;

$model = new OrdersSearch();
?>

<?php $form = ActiveForm::begin([
    'action' => ['reports'],
    'method' => 'get',
    'options' => [
        'data-pjax' => 1
    ],
]); ?>

<?= $form->field($model, 'date_range')->widget(DateRangePicker::classname(), [
    'convertFormat' => true,
    'useWithAddon' => true, // Allows the widget to be used with an addon like a button
    'pluginOptions' => [
        'locale' => [
            'format' => 'yyyy-MM-dd', // Format for the date picker
            'separator' => ' + ', // Separator between start and end dates
        ],
        'opens' => 'left', // Opens the picker to the left side
    ],
    'options' => [
        'autocomplete' => 'off', // Disable autocomplete
    ],
])->label("Date Range");

?>

<div class="form-group">
    <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
    <?= Html::a(Yii::t('app', 'Reset'), ['admin/reports'], ['class' => 'btn btn-outline-secondary']) ?>
</div>

<?php ActiveForm::end(); ?>
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