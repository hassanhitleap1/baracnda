<?php

use app\models\orders\Orders;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\orders\Orders $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="orders-view">
    

<div class="row">
        <div class="card">
            <div class="card-header">
                <?= Yii::t('app', 'order status') ?>
            </div>
            <div class="card-body">
                <?php if ($model->status_order == Orders::STATUS_RESERVED) : ?>
                    <?= Html::a(Yii::t('app', 'Confirm'), ['confirm', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
                <?php elseif ($model->status_order == Orders::STATUS_PROCESSING) : ?>    
                <?php endif ?>
            </div>
        </div>
    </div>
    
    <p>
        <?php
            if($model->status_order =='reserved'){
             echo Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ;
            }
        ?>

        <?php 
            if($model->status_order =='reserved'){
                echo Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]);
            }
        ?>
    </p>

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
                'value' => $model->status ? $model->status->name : null,
            ],
            'total',
            'shipping_price',
            'sub_total',
            'profit',
            'discount',
            [
                'label' => Yii::t('app', 'Shipping'),
                'value' => $model->shippings ? $model->shippings->name : null,
            ],
            'note:ntext',
            [
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
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
