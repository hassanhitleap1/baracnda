<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.js"></script>

<!-- include summernote css/js-->
<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.2/summernote.css" rel="stylesheet">
<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.js" defer></script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>



<?php

use app\models\orders\Orders;
use app\models\payments\Payments;
use app\models\regions\Regions;
use app\models\shippings\Shippings;
use app\models\status\Status;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;




$region_id = $model->isNewRecord || !$model->addresses ? null : $model->addresses->region_id;
$full_name = $model->isNewRecord || !$model->addresses ? null : $model->addresses->full_name;
$address = $model->isNewRecord || !$model->addresses ? null : $model->addresses->address;
$phone = $model->isNewRecord || !$model->addresses ? null : $model->addresses->phone;

// These seem to be direct properties of the Orders model, so the original check is likely sufficient
$subtotal = $model->isNewRecord ? null : $model->sub_total;
$shipping_price = $model->isNewRecord ? null : $model->shipping_price;
$total = $model->isNewRecord ? null : $model->total;

/** @var yii\web\View $this */
/** @var app\models\orders\Orders $model */
/** @var yii\widgets\ActiveForm $form */
?>


<div class="orders-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-10">
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
                        $order = Yii::$app->request->post('Orders');

                        if (Yii::$app->request->isPost && isset($order['OrderItems'])) {

                            foreach ($order['OrderItems'] as  $key => $orderItem) {

                                echo '<div class="row mb-3 variant-item" data-id="' . $orderItem['variant_id'] . '">';
                                echo '<div class="col-2">';
                                echo '<input type="hidden" name="Orders[OrderItems][' . $key . '][variant_id]" value="' . $orderItem['variant_id'] . '">';
                                echo '<input type="hidden" name="Orders[OrderItems][' . $key . '][product_id]" value="' . $orderItem['product_id'] . '">';
                                echo '<input type="hidden" name="Orders[OrderItems][' . $key . '][variant_image]" value="' . $orderItem['variant_image'] . '">';
                                echo '<img src="' . "/" . $orderItem['variant_image'] . '" alt="' . $orderItem['variant_image'] . '" class="img-thumbnail w-40">';
                                echo '</div>';
                                echo '<div class="col-4">';
                                echo '<input type="text" class="form-control" name="Orders[OrderItems][' . $key . '][variant_name]" value="' . $orderItem['variant_name'] . '" readonly>';
                                echo '</div>';
                                echo '<div class="col-2">';
                                echo '<input type="number" class="form-control variant-quantity" name="Orders[OrderItems][' . $key . '][variant_quantity]" value="' . $orderItem['variant_quantity'] . '" min="1">';
                                echo '</div>';
                                echo '<div class="col-2">';
                                echo '<input type="text" class="form-control variant-price" name="Orders[OrderItems][' . $key . '][variant_price]" value="' . $orderItem['variant_price'] . '" readonly>';
                                echo '</div>';
                                echo '<div class="col-2">';
                                echo '<button class="btn btn-danger btn-sm delete-variant-btn">Delete</button>';
                                echo '</div>';
                                echo '</div>';
                            }
                        }

                        if (!Yii::$app->request->isPost) {

                            foreach ($model->orderItems as $key => $orderItem) {
                                echo '<div class="row mb-3 variant-item" data-id="' . $orderItem->id . '">';
                                echo '<div class="col-2">';
                                echo '<input type="hidden" name="Orders[OrderItems][' . $key . '][variant_id]" value="' . $orderItem->variant_id . '">';
                                echo '<input type="hidden" name="Orders[OrderItems][' . $key . '][product_id]" value="' . $orderItem->product_id . '">';
                                echo '<input type="hidden" name="Orders[OrderItems][' . $key . '][variant_image]" value="' . $orderItem->product->image_path . '">';
                                echo '<img src="' . "/" . $orderItem->product->image_path . '" alt="' . $orderItem->product->image_path . '" class="img-thumbnail w-40">';
                                echo '</div>';
                                echo '<div class="col-4">';
                                echo '<input type="text" class="form-control" name="Orders[OrderItems][' . $key . '][variant_name]" value="' . $orderItem->product->name . '" readonly>';
                                echo '</div>';
                                echo '<div class="col-2">';
                                echo '<input type="number" class="form-control variant-quantity" name="Orders[OrderItems][' . $key . '][variant_quantity]" value="' . $orderItem->quantity . '" min="1">';
                                echo '</div>';
                                echo '<div class="col-2">';
                                echo '<input type="text" class="form-control variant-price" name="Orders[OrderItems][' . $key . '][variant_price]" value="' . $orderItem->price . '" readonly>';
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
                                'options' => ['placeholder' => 'Select a attribute', 'id' => 'region-id', 'value' => $region_id],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'id' => 'region-id'
                                ],
                            ]); ?>
                        </div>
                        <div class="col-6">
                            <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'value' => $phone, 'id' => 'phone']) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <?= $form->field($model, 'full_name')->textInput(['maxlength' => true, 'value' => $full_name]) ?>
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
                            <?= $form->field($model, 'shipping_id')->dropDownList(ArrayHelper::map(Shippings::find()->all(), 'id', 'name'), ['prompt' => 'Select a shipping', 'id' => 'select2-orders-shipping_id', 'disabled' => true]) //['prompt' => 'Select a shipping']) 
                            ?>
                        </div>
                        <div class="col-6">
                            <?= $form->field($model, 'address')->textInput(['maxlength' => true, 'value' => $address]) ?>
                        </div>


                        <div class="col-6">
                            <?= $form->field($model, 'note')->textarea(['rows' => 6]) ?>
                        </div>

                        <div class="col-6">
                            <?= $form->field($model, 'payment_id')->widget(Select2::classname(), [
                                'data' => ArrayHelper::map(Payments::find()->all(), 'id', 'name'),
                                'options' => ['placeholder' => 'Select a payments', 'value' => $model->payment_id ?? 1],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>
        <div class="col-2" id="totals-container">
            <div class="card-header">
                <?= Yii::t('app', 'Order Summary') ?>
            </div>
            <div class="card-body">
                <p><strong>Subtotal:</strong> <span id="subtotal"><?= $subtotal ?></span></p>
                <p><strong>Shipping Price:</strong> <span id="shipping-price"><?= $shipping_price ?></span></p>
                <p><strong>Total:</strong> <span id="total"><?= $total ?></span></p>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>