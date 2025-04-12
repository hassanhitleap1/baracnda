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
    <h1 id="order-id" data-id="<?= $model->id ?>"><?= Html::encode($this->title) ?></h1>

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
                                'visible' =>  $model->status_order === Orders::STATUS_PROCESSING,
                            ],
                            'payment_method',
                            'payment.name',
                            [
                                'attribute' => 'shipping_price',
                                'value' => $model->shipping_price,
                                'contentOptions' => ['id' => 'shipping-price'], 
                            ],
                            [
                                'attribute' => 'profit',
                                'value' => $model->profit,
                                'contentOptions' => ['id' => 'profit'], 
                            ],
                            [
                                'attribute' => 'discount',
                                'value' => $model->discount,
                                'contentOptions' => ['id' => 'discount'], 
                            ],
                            [
                                'attribute' => 'sub_total',
                                'value' => $model->sub_total,
                                'contentOptions' => ['id' => 'subtotal'], 
                            ],
                            [
                                'attribute' => 'total',
                                'value' => $model->total,
                                'contentOptions' => ['id' => 'total'], 
                            ],
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
                    <div class="form-group">
                        <label for="variantSearchInputInView"><?= Yii::t('app', 'Search Variants') ?></label>
                        <input type="text" id="variantSearchInputInView" class="form-control" placeholder="<?= Yii::t('app', 'Enter variant name...') ?>">
                        <div id="variantSearchResultsView" class="dropdown-menu show" style="width: 100%;"></div>
                    </div>
                    <div id="orderItems" class="mt-3">
                    </div>

                </div>
                <div class="card-body">
                    <?php if ($model->orderItems): ?>
                        <ul class="list-group list-group-products">
                            <?php foreach ($model->orderItems as $item): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?= Html::img($item->product->imageUrl, ['alt' => $item->product->name, 'style' => 'width:50px; height:auto;']) ?>
                                    <?= Html::encode($item->product->name . ' (Qty: ' . $item->quantity . ')') ?>
                                    <?php if (in_array($model->status_order, [Orders::STATUS_PROCESSING, Orders::STATUS_RESERVED])): ?>
                                        <?= Html::button('Delete', ['class' => 'btn btn-danger btn-sm delete-item-btn', 'data-id' => $item->id]) ?>

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
               <?= Html::a(Yii::t('app', 'START PROCESS'), ['process', 'id' => $model->id], [
                'class' => 'btn btn-warning',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to start processing this order?'),
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