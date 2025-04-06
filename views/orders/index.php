<?php

use app\models\status\Status;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\widgets\Pjax;
use yii\bootstrap5\Modal; // Use Bootstrap 5 Modal
use kartik\daterange\DateRangePicker;
use yii\helpers\ArrayHelper;
/** @var yii\web\View $this */
/** @var app\models\orders\OrdersSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Orders');
$this->params['breadcrumbs'][] = $this->title;

// Define the $statusList variable
$statusList = \yii\helpers\ArrayHelper::map(Status::find()->all(), 'id', 'name');
?>
<div class="orders-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Orders'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::button(Yii::t('app', 'Print Selected'), [
            'class' => 'btn btn-primary',
            'id' => 'print-selected',
        ]) ?>
        <?= Html::button(Yii::t('app', 'Change Status'), [
            'class' => 'btn btn-warning',
            'id' => 'change-status',
        ]) ?>
    </p>

    <?php
    Modal::begin([
        'id' => 'status-modal',
        'title' => Yii::t('app', 'Change Status'),
        'footer' => Html::button(Yii::t('app', 'Apply Status'), [
            'class' => 'btn btn-success',
            'id' => 'apply-status',
        ]),
    ]);
    ?>
    <?= Html::dropDownList('new-status', null, $statusList, [
        'id' => 'new-status',
        'class' => 'form-control',
        'prompt' => Yii::t('app', 'Select New Status'),
    ]) ?>
    <?php Modal::end(); ?>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'class' => 'yii\grid\CheckboxColumn',
                'checkboxOptions' => function ($model) {
                    return ['value' => $model->id];
                },
            ],
            'id',
            [
                'attribute' => 'user_id',
                'label' => Yii::t('app', 'User'),
                'value' => 'user.full_name',
                'filter' => ArrayHelper::map(\app\models\users\Users::find()->all(), 'id', 'full_name'),
            ],
            [
                'attribute' => 'creator_id',
                'label' => Yii::t('app', 'Creator'),
                'value' => 'creator.full_name',
                'filter' => ArrayHelper::map(\app\models\users\Users::find()->all(), 'id', 'full_name'),
            ],
            [
                'attribute' => 'address_id',
                'label' => Yii::t('app', 'Full Address'),
                'value' => function ($model) {
                    return $model->addresses ? $model->addresses->full_name . ', ' . $model->addresses->address . ', ' . $model->addresses->region->name : null;
                },
            ],
            [
                'attribute' => 'status_id',
                'label' => Yii::t('app', 'Status'),
                'value' => 'status.name',
                'filter' => ArrayHelper::map(\app\models\status\Status::find()->all(), 'id', 'name'),
            ],
            'shopping_price',
            'sub_total',
            'profit',
            'total',
            'shipping.name',
            'note:ntext',
            [
                'attribute' => 'orderItems',
                'label' => Yii::t('app', 'Order Items'),
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->orderItems) {
                        $items = array_map(function ($item) {
                            $image = Html::img($item->product->imageUrl, ['alt' => $item->product->name, 'style' => 'width:50px; height:auto;']);
                            $details = Html::encode($item->product->name . ' (Qty: ' . $item->quantity . ')');
                            return $image . '<br>' . $details;
                        }, $model->orderItems);
                        return implode('<br><br>', $items);
                    }
                    return null;
                },
            ],
            [
                'attribute' => 'created_at',
                'label' => Yii::t('app', 'Created At'),
                'format' => 'datetime',
                'filter' => DateRangePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'created_at',
                    'convertFormat' => true,
                    'pluginOptions' => [
                        'timePicker' => true,
                        'timePickerIncrement' => 30,
                        'locale' => [
                            'format' => 'Y-m-d H:i:s',
                        ],
                    ],
                ]),
            ],
            'updated_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, $model, $key, $index) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                },
            ],
        ],
        'responsive' => true,
        'hover' => true,
        'panel' => [
            'type' => 'primary',
            'heading' => Yii::t('app', 'Orders List'),
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>

<?php
$printUrl = Url::to(['orders/print-selected']);
$changeStatusUrl = Url::to(['orders/change-status']);
$script = <<<JS
    $('#print-selected').on('click', function () {
        var keys = $('#w0').yiiGridView('getSelectedRows');
        if (keys.length === 0) {
            alert('Please select at least one order.');
            return;
        }
        window.location.href = '$printUrl' + '?ids=' + JSON.stringify(keys);
    });

    $('#change-status').on('click', function () {
        $('#status-modal').modal('show');
    });

    $('#apply-status').on('click', function () {
        var keys = $('#w0').yiiGridView('getSelectedRows');
        var newStatus = $('#new-status').val();
        if (keys.length === 0) {
            alert('Please select at least one order.');
            return;
        }
        if (!newStatus) {
            alert('Please select a new status.');
            return;
        }
        $.post('$changeStatusUrl', {ids: keys, status: newStatus}, function (response) {
            if (response.success) {
                alert('Status updated successfully.');
                location.reload();
            } else {
                alert('Failed to update status.');
            }
        });
    });
JS;
$this->registerJs($script);
?>
