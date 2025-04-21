<?php
/** @var app\models\orders\Orders $model */

use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="invoice-container" style="font-family: Arial, sans-serif; width: 210mm; margin: auto; border: 1px solid #ddd; padding: 20px; border-radius: 8px; box-sizing: border-box;">
    <div class="header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h2 style="margin: 0; color: #555;">Invoice >> ID: #<?= Html::encode($model->id) ?></h2>
        </div>
        <div>
            <h3 style="margin: 0; color: #888;">Company Name</h3>
        </div>
    </div>

    <div class="customer-info" style="margin-bottom: 20px;">
        <p><strong>To:</strong> <?= Html::encode($model->creator->full_name) ?></p>
        <p><?= Html::encode($model->addresses->full_name ?? 'N/A') ?></p>
        <p><strong>Phone:</strong> <?= Html::encode($model->creator->phone ?? 'N/A') ?></p>
    </div>

    <div class="invoice-info" style="margin-bottom: 20px;">
        <p><strong>Invoice:</strong></p>
        <p><strong>ID:</strong> #<?= Html::encode($model->id) ?></p>
        <p><strong>Creation Date:</strong> <?= Yii::$app->formatter->asDate($model->created_at) ?></p>
        <p><strong>Status:</strong> <span style="color: #fff; background-color: #f39c12; padding: 2px 6px; border-radius: 4px;">Unpaid</span></p>
    </div>

    <div class="order-items" style="margin-bottom: 20px;">
        <h3 style="border-bottom: 1px solid #ddd; padding-bottom: 5px;">Order Items:</h3>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <thead>
                <tr>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Image</th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Product</th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Quantity</th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Price</th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($model->orderItems as $item): ?>
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">
                            <img src="<?= Html::encode($item->product->image_url ?? 'placeholder.jpg') ?>" alt="Product Image" style="width: 50px; height: 50px; object-fit: cover;">
                        </td>
                        <td style="border: 1px solid #ddd; padding: 8px;"><?= Html::encode($item->product->name) ?></td>
                        <td style="border: 1px solid #ddd; padding: 8px; text-align: center;"><?= Html::encode($item->quantity) ?></td>
                        <td style="border: 1px solid #ddd; padding: 8px;"><?= Yii::$app->formatter->asCurrency($item->price, 'USD') ?></td>
                        <td style="border: 1px solid #ddd; padding: 8px;"><?= Yii::$app->formatter->asCurrency($item->price * $item->quantity, 'USD') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="notes" style="margin-bottom: 20px;">
        <p>Add additional notes and payment information</p>
    </div>

    <div class="totals" style="border-top: 1px solid #ddd; padding-top: 10px;">
        <p style="display: flex; justify-content: space-between;">
            <span>SubTotal:</span>
            <span><?= Yii::$app->formatter->asCurrency($model->sub_total, 'USD') ?></span>
        </p>
        <p style="display: flex; justify-content: space-between;">
            <span>Shipping:</span>
            <span><?= Yii::$app->formatter->asCurrency($model->shipping_price, 'USD') ?></span>
        </p>
        <p style="display: flex; justify-content: space-between; font-weight: bold; font-size: 18px;">
            <span>Total Amount:</span>
            <span><?= Yii::$app->formatter->asCurrency($model->total, 'USD') ?></span>
        </p>
    </div>
</div>