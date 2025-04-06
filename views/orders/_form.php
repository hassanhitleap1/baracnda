<?php

use app\models\countries\Countries;
use app\models\payments\Payments;
use app\models\regions\Regions;
use app\models\shippings\Shippings;
use app\models\status\Status;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\orders\Orders $model */
/** @var yii\widgets\ActiveForm $form */

$calculateTotalsUrl = Url::to(['orders/calculate-totals']);
?>

<div class="orders-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="card">
        <div class="card-header">
            <?= Yii::t('app', 'Variants') ?>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="variantSearchInput"><?= Yii::t('app', 'Search Variants') ?></label>
                <input type="text" id="variantSearchInput" class="form-control" placeholder="<?= Yii::t('app', 'Enter variant name...') ?>">
                <div id="variantSearchResults" class="dropdown-menu show" style="width: 100%;"></div>
            </div>
            <div id="orderItems" class="mt-3">
                <!-- Selected variants will be appended here -->
                 <?php
                    $order= Yii::$app->request->post('Orders');
                
                    if( Yii::$app->request->isPost){
                    
                        foreach ($order['OrderItems'] as  $key => $orderItem) {
                            
                                echo '<div class="row mb-3 variant-item" data-id="'.$orderItem['variant_id'] .'">';
                                echo '<div class="col-2">';
                                echo '<input type="hidden" name="Orders[OrderItems]['. $key .'][variant_id]" value="'.$orderItem['variant_id'].'">';
                                echo '<input type="hidden" name="Orders[OrderItems]['. $key .'][product_id]" value="'.$orderItem['product_id'] .'">';
                                echo '<input type="hidden" name="Orders[OrderItems]['. $key .'][variant_image]" value="'.$orderItem['variant_image'] .'">';
                                echo '<img src="'.$orderItem['variant_image'] .'" alt="'.$orderItem['variant_image'] .'" class="img-thumbnail w-40">';
                                echo '</div>';
                                echo '<div class="col-4">';
                                echo '<input type="text" class="form-control" name="Orders[OrderItems]['. $key .'][variant_name]" value="'.$orderItem['variant_name'] .'" readonly>';
                                echo '</div>';
                                echo '<div class="col-2">';
                                echo '<input type="number" class="form-control" name="Orders[OrderItems]['. $key .'][variant_quantity]" value="'.$orderItem['variant_quantity'] .'" min="1">';
                                echo '</div>';
                                echo '<div class="col-2">';
                                echo '<input type="text" class="form-control" name="Orders[OrderItems]['. $key .'][variant_price]" value="'.$orderItem['variant_price'] .'" readonly>';
                                echo '</div>';
                                echo '<div class="col-2">';
                                echo '<button class="btn btn-danger btn-sm delete-variant-btn">Delete</button>';
                                echo '</div>';
                                echo '</div>';

                        }
                    }

                    if(!Yii::$app->request->isPost){

                        foreach ($model->orderItems as $key => $orderItem) {
                            echo '<div class="row mb-3 variant-item" data-id="'.$orderItem->id .'">';
                            echo '<div class="col-2">';
                            echo '<input type="hidden" name="Orders[OrderItems]['. $key .'][variant_id]" value="'.$orderItem->variant_id.'">';
                            echo '<input type="hidden" name="Orders[OrderItems]['. $key .'][product_id]" value="'.$orderItem->product_id .'">';
                            echo '<input type="hidden" name="Orders[OrderItems]['. $key .'][variant_image]" value="'.$orderItem->product->image_path.'">';
                            echo '<img src="'.$orderItem->product->image_path .'" alt="'.$orderItem->product->image_path .'" class="img-thumbnail w-40">';
                            echo '</div>';
                            echo '<div class="col-4">';
                            echo '<input type="text" class="form-control" name="Orders[OrderItems]['. $key .'][variant_name]" value="'.$orderItem->product->name .'" readonly>';
                            echo '</div>';
                            echo '<div class="col-2">';
                            echo '<input type="number" class="form-control" name="Orders[OrderItems]['. $key .'][variant_quantity]" value="'.$orderItem->quantity .'" min="1">';
                            echo '</div>';
                            echo '<div class="col-2">';
                            echo '<input type="text" class="form-control" name="Orders[OrderItems]['. $key .'][variant_price]" value="'.$orderItem->price .'" readonly>';
                            echo '</div>';
                            echo '<div class="col-2">';
                            echo '<button class="btn btn-danger btn-sm delete-variant-btn">Delete</button>';
                            echo '</div>';
                            echo '</div>';
                        }
                    }
                    
                 ?>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <?= Yii::t('app', 'address') ?>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <?= $form->field($model, 'region_id')->widget(Select2::classname(), [
                        'data' => ArrayHelper::map(Regions::find()->all(), 'id', 'name'),
                        'options' => ['placeholder' => 'Select a attribute'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
                </div>
                <div class="col-6">
                    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <?= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-6">
                    <?= $form->field($model, 'status_id')->widget(Select2::classname(), [
                        'data' => ArrayHelper::map(Status::find()->all(), 'id', 'name'),
                        'options' => ['placeholder' => 'Select a status'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <?= $form->field($model, 'shipping_id')->widget(Select2::classname(), [
                        'data' => ArrayHelper::map(Shippings::find()->all(), 'id', 'name'),
                        'options' => ['placeholder' => 'Select a status'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
                </div>
                <div class="col-6">
                    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
                </div>
            

                <div class="col-6">
                    <?= $form->field($model, 'note')->textarea(['rows' => 6]) ?>
                </div>

                <div class="col-6">
                    <?= $form->field($model, 'payment_id')->widget(Select2::classname(), [
                        'data' => ArrayHelper::map(Payments::find()->all(), 'id', 'name'),
                        'options' => ['placeholder' => 'Select a payments','value' => $model->payment_id ??1],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
    <div id="totals-container" style="margin-top: 20px;">
        <h4>Order Summary</h4>
        <p><strong>Subtotal:</strong> <span id="subtotal">0.00</span></p>
        <p><strong>Shipping Price:</strong> <span id="shipping-price">0.00</span></p>
        <p><strong>Total:</strong> <span id="total">0.00</span></p>
    </div>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$script = <<<JS
function calculateTotals() {
    var shippingId = $('#shipping-select').val();
    var orderItems = $('#order-items-container').find('input, select').serialize();

    $.ajax({
        url: '$calculateTotalsUrl',
        type: 'POST',
        data: {shipping_id: shippingId, order_items: orderItems},
        success: function (response) {
            if (response.success) {
                $('#subtotal').text(response.subtotal.toFixed(2));
                $('#shipping-price').text(response.shipping_price.toFixed(2));
                $('#total').text(response.total.toFixed(2));
            } else {
                alert('Failed to calculate totals.');
            }
        },
        error: function () {
            alert('An error occurred while calculating totals.');
        }
    });
}

$('#shipping-select').on('change', calculateTotals);
$('#order-items-container').on('change', 'input, select', calculateTotals);
JS;

$this->registerJs($script);
?>