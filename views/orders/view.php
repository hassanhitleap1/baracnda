<?php

use app\models\orders\Orders;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap5\Modal;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\orders\Orders $model */

$this->title = Yii::t('app', 'Order #') . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\yii\web\YiiAsset::register($this);
?>

<div class="orders-view">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <?= Yii::t('app', 'Order Details') ?>

                </div>
                <div class="card-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            [
                                'label' => Yii::t('app', 'User'),
                                'value' => $model->user ? $model->user->full_name : null,
                            ],
                            [
                                'label' => Yii::t('app', 'Creator'),
                                'value' => $model->creator ? $model->creator->full_name : null,
                            ],
                            [
                                'label' => Yii::t('app', 'Address'),
                                'value' => $model->addresses ? $model->addresses->full_name . ', ' . $model->addresses->address . ', ' . $model->addresses->region->name : null,
                            ],
                            [
                                'label' => Yii::t('app', 'Status'),
                                'format' => 'raw',
                                'value' => Html::a(
                                    $model->status ? $model->status->name : null,
                                    '#',
                                    [
                                        'class' => 'btn btn-link',
                                        'id' => 'change-status-btn',
                                        'data-bs-toggle' => 'modal',
                                        'data-bs-target' => '#status-modal',
                                    ]
                                ),
                            ],
                            [
                                'label' => Yii::t('app', 'Delivery Status'),
                                'format' => 'raw',
                                'value' => Html::dropDownList(
                                    'delivery_status',
                                    $model->delivery_status,
                                    ['delivered' => 'Delivered', 'shipped' => 'Shipped'],
                                    [
                                        'class' => 'form-control',
                                        'id' => 'delivery-status-dropdown',
                                    ]
                                ),
                            ],
                            'total',
                            'shipping_price',
                            'sub_total',
                            'profit',
                            'discount',
                            [
                                'label' => Yii::t('app', 'Shipping'),
                                'value' => $model->shipping ? $model->shipping->name : null,
                            ],
                            'note:ntext',
                            'created_at',
                            'updated_at',
                        ],
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="text-center"><?= Yii::t('app', 'Order Items') ?></h5>
                    <p>
                    <?= Html::a(Yii::t('app', $model->status_order), ['', 'id' => $model->id, 'status' => Orders::STATUS_RESERVED], ['class' => 'btn btn-warning btn-block']) ?>
                    </p>
                  
                </div>
                <div class="card-body">
                    <?php if ($model->orderItems): ?>
                        <ul class="list-group">
                            <?php foreach ($model->orderItems as $item): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?= Html::img($item->product->imageUrl, ['alt' => $item->product->name, 'style' => 'width:50px; height:auto;']) ?>
                                    <?= Html::encode($item->product->name . ' (Qty: ' . $item->quantity . ')') ?>
                                    <?php if (in_array($model->status_order, [Orders::STATUS_PROCESSING, Orders::STATUS_RESERVED])): ?>
                                        <?= Html::button('Delete', [ 'class' => 'btn btn-danger btn-sm delete-item-btn' ,'data-id' => $item->id]) ?>
                                 
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p><?= Yii::t('app', 'No items in this order.') ?></p>
                    <?php endif; ?>
                </div>
            </div>


        </div>
    </div>

    <p>
        <?php if ($model->status_order === Orders::STATUS_RESERVED): ?>
            <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]) ?>
        <?php elseif ($model->status_order === Orders::STATUS_CANCELED): ?>
            <?= Html::a(Yii::t('app', 'Revert to Reserved'), ['change-status', 'id' => $model->id, 'status' => Orders::STATUS_RESERVED], ['class' => 'btn btn-warning']) ?>
        <?php endif; ?>
    </p>
</div>

<?php
Modal::begin([
    'id' => 'status-modal',
    'title' => Yii::t('app', 'Change Status'),
    'footer' => Html::button(Yii::t('app', 'Save'), ['class' => 'btn btn-success', 'id' => 'save-status-btn']),
]);
?>
<div id="status-modal-content">
    <?= Html::dropDownList('status_id', $model->status_id, \yii\helpers\ArrayHelper::map(\app\models\status\Status::find()->all(), 'id', 'name'), [
        'class' => 'form-control',
        'id' => 'status-dropdown',
    ]) ?>
</div>
<?php Modal::end(); ?>

<?php
$changeStatusUrl = Url::to(['change-status']);
$changeDeliveryStatusUrl = Url::to(['change-delivery-status']);
$deleteItemUrl = Url::to(['delete-item']);
$recalculateTotalsUrl = Url::to(['recalculate-totals']);
$script = <<<JS
    // Change status via AJAX
    $('#save-status-btn').on('click', function () {
        var statusId = $('#status-dropdown').val();
        $.post('$changeStatusUrl', {id: {$model->id}, status_id: statusId}, function (response) {
            if (response.success) {
                alert('Status updated successfully.');
                location.reload();
            } else {
                alert('Failed to update status.');
            }
        });
    });

    // Change delivery status via AJAX
    $('#delivery-status-dropdown').on('change', function () {
        var deliveryStatus = $(this).val();
        $.post('$changeDeliveryStatusUrl', {id: {$model->id}, delivery_status: deliveryStatus}, function (response) {
            if (response.success) {
                alert('Delivery status updated successfully.');
                location.reload();
            } else {
                alert('Failed to update delivery status.');
            }
        });
    });

    // Delete item via AJAX and recalculate totals

JS;
$this->registerJs($script);
?>