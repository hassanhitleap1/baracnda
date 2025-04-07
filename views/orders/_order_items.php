<?php

use yii\helpers\Html;

/** @var yii\widgets\ActiveForm $form */
/** @var app\models\orders\Orders $model */

?>

<div class="order-items">
    <h4>Order Items</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($model->orderItems as $index => $item): ?>
                <tr>
                    <td>
                        <?= Html::dropDownList("OrderItems[$index][product_id]", $item->product_id, $productList, ['class' => 'form-control ']) ?>
                    </td>
                    <td>
                        <?= Html::input('number', "OrderItems[$index][quantity]", $item->quantity, ['class' => 'form-control variant-quantity', 'min' => 1]) ?>
                    </td>
                    <td>
                        <?= Html::input('number', "OrderItems[$index][price]", $item->price, ['class' => 'form-control variant-price', 'step' => '0.01']) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
