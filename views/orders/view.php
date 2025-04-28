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

// Register Vue.js and Axios
$this->registerJsFile('https://cdn.jsdelivr.net/npm/vue@3.2.47/dist/vue.global.min.js', ['position' => \yii\web\View::POS_HEAD]);
$this->registerJsFile('https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js', ['position' => \yii\web\View::POS_HEAD]);

// Pass the order ID to JavaScript
$orderId = $model->id;
?>

<div class="orders-view" id="order-app">
    <h1>{{ title }}</h1>

    <!-- Order Status -->
    <div class="order-status mb-4">
        <span class="badge bg-primary">Order Status: {{ order.status?.name || 'N/A' }}</span>
        <span class="badge bg-secondary ms-2">Payment Status: {{ order.payment_status || 'N/A' }}</span>
        <span class="badge bg-success ms-2">Delivery Status: {{ order.delivery_status || 'N/A' }}</span>
    </div>

    <div class="row">
        <div class="col-8">
            <!-- Order Items -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Order Items ({{ order.orderItems?.length || 0 }})</h3>
                </div>
                <div class="card-body">
                    <div v-if="order.orderItems?.length">
                        
                        <div v-for="item in order.orderItems" :key="item.id" class="order-item mb-3 pb-3 border-bottom">
                            <div class="row">
                                <div class="col-md-2">
                                    <img v-if="item.product?.image_path" :src="'/'+ item.product.image_path" class="img-fluid" style="max-height: 100px;">
                                    <div v-else class="bg-light text-center p-4">No Image</div>
                                </div>
                                <div class="col-md-6">
                                    <h5>{{ item.product?.name || 'Product not found' }}</h5>
                                    <div v-if="item.variant">
                                        <small class="text-muted">Variant: {{ item.variant.name }}</small>
                                    </div>

                                </div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <span class="input-group-text">Qty</span>
                                        <input type="number" class="form-control" v-model="item.quantity" min="1" disabled>
                                    </div>
                                </div>
                                <div class="col-md-2 text-end">
                                    <h5>${{ (item.price * item.quantity).toFixed(2) }}</h5>
                                    <small class="text-muted">${{ item.price }} each</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else class="text-center py-4">
                        <p class="text-muted">No items found in this order</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4">

            <!-- Shipping Address -->
            <div class="card mb-4" v-if="order.addresses">
                <div class="card-header">
                    <h3 class="card-title">Shipping Address</h3>
                </div>
                <div class="card-body">
                    <address>
                        <strong>{{ order.addresses.full_name }}</strong><br>
                        {{ order.addresses.address }}<br>
                        {{ order.addresses.city }}, {{ order.addresses.region?.name }}<br>
                        {{ order.addresses.postal_code }}<br>
                        Phone: {{ order.addresses.phone }}
                    </address>
                </div>
            </div>
            <!-- Order Summary -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Order Summary</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 ">
                            <table class="table table-borderless">
                                <tr>
                                    <td>Subtotal:</td>
                                    <td class="text-end">${{ order.sub_total || '0.00' }}</td>
                                </tr>
                                <tr>
                                    <td>Shipping:</td>
                                    <td class="text-end">${{ order.shipping_price || '0.00' }}</td>
                                </tr>
                                <tr v-if="order.discount > 0">
                                    <td>Discount:</td>
                                    <td class="text-end text-danger">- ${{ order.discount|| '0.00' }}</td>
                                </tr>
                                <tr class="border-top">
                                    <th>Total:</th>
                                    <th class="text-end">${{ order.total|| '0.00' }}</th>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-4">
        <button class="btn btn-primary" @click="processOrder" v-if="order.status?.name !== 'processing'">
            Process Order
        </button>
        <a :href="'/orders/index'" class="btn btn-outline-secondary ms-2">
            Back to Orders
        </a>
    </div>
</div>


<?php
$js = <<<JS
const { createApp, reactive, ref, onMounted,computed } = Vue;

createApp({
    setup() {
        const order = reactive({});
        const title = ref('Loading...');
        const orderId = $model->id; // Safely pass PHP variable to JS

    

        // Fetch order data using Axios
        const fetchOrder = async () => {
            try {
                console.log(orderId);
                const response = await axios.get(`/api/orders/view?id=${orderId}`);
                Object.assign(order, response.data);
                title.value = `Order #1`;
            } catch (error) {
                console.error('Error fetching order data:', error);
                title.value = 'Error loading order';
            }
        };

        const processOrder = async () => {
            try {
                console.log(orderId);
                const response = await axios.post(`/api/orders/view?id=${orderId}`);
                
            } catch (error) {
                console.error('Error fetching order data:', error);
                title.value = 'Error loading order';
            }
        };
  

        const isEditable = computed(() => {
            return order.order_status !== 'processing';
        });
        
        
        onMounted(fetchOrder);
        // Computed property to check if the order can be edited
        // canEdit: computed(() => order.status?.name !== 'processing'),
   


        return {
            order,
            title,
            processOrder,
            isEditable,
        };

  
    }
}).mount('#order-app');
JS;

$this->registerJs($js, \yii\web\View::POS_END);
?>