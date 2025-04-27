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

    <div class="row d-flex justify-content-center align-items-center">
            <div class="col-12">
                <div class="card card-registration card-registration-2" style="border-radius: 15px;">
                <div class="card-body p-0">
                    <div class="row g-0">
                    <div class="col-lg-8">
                        <div class="p-5">
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <h1 class="fw-bold mb-0">{{ title }}</h1>
                            <h6 class="mb-0 text-muted">{{ order.orderItems?.length}} items</h6>
                        </div>


                        <hr class="my-4">

                        <div class="row mb-4 d-flex justify-content-between align-items-center">
                            <div class="col-md-2 col-lg-2 col-xl-2">
                            <img
                                src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-shopping-carts/img5.webp"
                                class="img-fluid rounded-3" alt="Cotton T-shirt">
                            </div>
                            <div class="col-md-3 col-lg-3 col-xl-3">
                            <h6 class="text-muted">Shirt</h6>
                            <h6 class="mb-0">Cotton T-shirt</h6>
                            </div>
                            <div class="col-md-3 col-lg-3 col-xl-2 d-flex">
                            <button data-mdb-button-init data-mdb-ripple-init class="btn btn-link px-2"
                                onclick="this.parentNode.querySelector('input[type=number]').stepDown()">
                                <i class="fas fa-minus"></i>
                            </button>

                            <input id="form1" min="0" name="quantity" value="1" type="number"
                                class="form-control form-control-sm" />

                            <button data-mdb-button-init data-mdb-ripple-init class="btn btn-link px-2"
                                onclick="this.parentNode.querySelector('input[type=number]').stepUp()">
                                <i class="fas fa-plus"></i>
                            </button>
                            </div>
                            <div class="col-md-3 col-lg-2 col-xl-2 offset-lg-1">
                            <h6 class="mb-0">€ 44.00</h6>
                            </div>
                            <div class="col-md-1 col-lg-1 col-xl-1 text-end">
                            <a href="#!" class="text-muted"><i class="fas fa-times"></i></a>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row mb-4 d-flex justify-content-between align-items-center">
                            <div class="col-md-2 col-lg-2 col-xl-2">
                            <img
                                src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-shopping-carts/img6.webp"
                                class="img-fluid rounded-3" alt="Cotton T-shirt">
                            </div>
                            <div class="col-md-3 col-lg-3 col-xl-3">
                            <h6 class="text-muted">Shirt</h6>
                            <h6 class="mb-0">Cotton T-shirt</h6>
                            </div>
                            <div class="col-md-3 col-lg-3 col-xl-2 d-flex">
                            <button data-mdb-button-init data-mdb-ripple-init class="btn btn-link px-2"
                                onclick="this.parentNode.querySelector('input[type=number]').stepDown()">
                                <i class="fas fa-minus"></i>
                            </button>

                            <input id="form1" min="0" name="quantity" value="1" type="number"
                                class="form-control form-control-sm" />

                            <button data-mdb-button-init data-mdb-ripple-init class="btn btn-link px-2"
                                onclick="this.parentNode.querySelector('input[type=number]').stepUp()">
                                <i class="fas fa-plus"></i>
                            </button>
                            </div>
                            <div class="col-md-3 col-lg-2 col-xl-2 offset-lg-1">
                            <h6 class="mb-0">€ 44.00</h6>
                            </div>
                            <div class="col-md-1 col-lg-1 col-xl-1 text-end">
                            <a href="#!" class="text-muted"><i class="fas fa-times"></i></a>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row mb-4 d-flex justify-content-between align-items-center">
                            <div class="col-md-2 col-lg-2 col-xl-2">
                            <img
                                src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-shopping-carts/img7.webp"
                                class="img-fluid rounded-3" alt="Cotton T-shirt">
                            </div>
                            <div class="col-md-3 col-lg-3 col-xl-3">
                            <h6 class="text-muted">Shirt</h6>
                            <h6 class="mb-0">Cotton T-shirt</h6>
                            </div>
                            <div class="col-md-3 col-lg-3 col-xl-2 d-flex">
                            <button data-mdb-button-init data-mdb-ripple-init class="btn btn-link px-2"
                                onclick="this.parentNode.querySelector('input[type=number]').stepDown()">
                                <i class="fas fa-minus"></i>
                            </button>

                            <input id="form1" min="0" name="quantity" value="1" type="number"
                                class="form-control form-control-sm" />

                            <button data-mdb-button-init data-mdb-ripple-init class="btn btn-link px-2"
                                onclick="this.parentNode.querySelector('input[type=number]').stepUp()">
                                <i class="fas fa-plus"></i>
                            </button>
                            </div>
                            <div class="col-md-3 col-lg-2 col-xl-2 offset-lg-1">
                            <h6 class="mb-0">€ 44.00</h6>
                            </div>
                            <div class="col-md-1 col-lg-1 col-xl-1 text-end">
                            <a href="#!" class="text-muted"><i class="fas fa-times"></i></a>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="pt-5">
                            <h6 class="mb-0"><a href="#!" class="text-body"><i
                                class="fas fa-long-arrow-alt-left me-2"></i>Back to shop</a></h6>
                        </div>
                        </div>
                    </div>
                    <div class="col-lg-4 bg-body-tertiary">
                        <div class="p-5">
                        <h3 class="fw-bold mb-5 mt-2 pt-1">Summary</h3>
                        <hr class="my-4">

                        <div class="d-flex justify-content-between mb-4">
                            <h5 class="text-uppercase">items 3</h5>
                            <h5>€ 132.00</h5>
                        </div>

                        <h5 class="text-uppercase mb-3">Shipping</h5>

                        <div class="mb-4 pb-2">
                            <select data-mdb-select-init>
                            <option value="1">Standard-Delivery- €5.00</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                            <option value="4">Four</option>
                            </select>
                        </div>

                        <h5 class="text-uppercase mb-3">Give code</h5>

                        <div class="mb-5">
                            <div data-mdb-input-init class="form-outline">
                            <input type="text" id="form3Examplea2" class="form-control form-control-lg" />
                            <label class="form-label" for="form3Examplea2">Enter your code</label>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between mb-5">
                            <h5 class="text-uppercase">Total price</h5>
                            <h5>€ 137.00</h5>
                        </div>

                        <button  type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-dark btn-block btn-lg"
                            data-mdb-ripple-color="dark">Register</button>

                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            </div>

    <div class="order-status">
        <span class="badge badge-primary">Order Status: {{ order.status?.name }}</span>
        <span class="badge badge-secondary">Payment Status: {{ order.payment_status }}</span>
        <span class="badge badge-success">Delivery Status: {{ order.delivery_status }}</span>
    </div>
    <div class="order-address">
        <h2>Shipping Address</h2>
        <p>{{ order.addresses ? order.addresses.full_name + ', ' + order.addresses.address + ', ' + order.addresses.region?.name : "" }}</p>
        <p>Phone: {{ order.addresses ? order.addresses.phone : "" }}</p>
        <p>Postal Code: {{ order.addresses ? order.addresses.postal_code : "" }}</p>
        <p>City: {{ order.addresses ? order.addresses.city : "" }}</p>
        <p>Country: {{ order.addresses ? order.addresses.country : "" }}</p>
        <p>Created At: {{ order.addresses ? order.addresses.created_at : "" }}</p>
        <p>Updated At: {{ order.addresses ? order.addresses.updated_at : "" }}</p>
        <p>Creator: {{ order.addresses ? order.addresses.creator?.name : "" }}</p>
        <p>Updated By: {{ order.addresses ? order.addresses.updater?.name : "" }}</p>        
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in order.items" :key="item.id">
                <td>
                    <div class="product-info">
                        <img :src="item.product.image" alt="Product Image" class="product-image">
                        <div>
                            <strong>{{ item.product.name }}</strong><br>
                            {{ item.product.sku }}
                        </div>
                    </div>
                </td>
                <td>
                    <div class="quantity-control">
                        <button class="btn btn-sm btn-outline-secondary">-</button>
                        <span>{{ item.quantity }}</span>
                        <button class="btn btn-sm btn-outline-secondary">+</button>
                    </div>
                </td>
                <td>${{ item.price }}</td>
                <td>
                    <button class="btn btn-danger btn-sm">🗑</button>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="order-summary">
        <h2>Order Summary</h2>
        <div class="summary-details">
            <p>Subtotal Price: <span>${{ order.sub_total }}</span></p>
            <p>Shipping Cost (+): <span>${{ order.shipping_price }}</span></p>
            <p>Discount (-): <span>${{ order.discount }}</span></p>
            <p><strong>Total Payable:</strong> <span>${{ order.total }}</span></p>
        </div>
    </div>


    <dl class="row">
        <dt class="col-sm-3">id</dt>
        <dd class="col-sm-9">{{ order.id }}</dd>

        <dt class="col-sm-3">user</dt>
        <dd class="col-sm-9">{{ order.user?.name ?? "" }} </dd>


        <dt class="col-sm-3"> creator </dt>
        <dd class="col-sm-9">{{ order.creator?.name ?? ""}}</dd>

        <dt class="col-sm-3">status</dt>
        <dd class="col-sm-9">{{ order.status?.name??""}}</dd>

        <dt class="col-sm-3">payment_status</dt>
        <dd class="col-sm-9">{{ order.payment_status}}</dd>
        <dt class="col-sm-3">delivery_status</dt>
        <dd class="col-sm-9">{{ order.delivery_status}}</dd>



        <dt class="col-sm-3">address</dt>
        <dd class="col-sm-9">{{ order.addresses ?   order.addresses.phone + "   "+ order.addresses.full_name + ', ' + order.addresses.address + ', ' + order.addresses.region?.name??""  : ""}}</dd>

        <dt class="col-sm-3">shipping_price</dt>
        <dd class="col-sm-9">{{ order.shipping_price }}</dd>
        <dt class="col-sm-3">subtotal</dt>
        <dd class="col-sm-9">{{ order.sub_total }}</dd>
        <dt class="col-sm-3">total</dt>
        <dd class="col-sm-9">{{ order.total }}</dd>
        <dt class="col-sm-3">created_at</dt>
        <dd class="col-sm-9">{{ order.created_at }}</dd>
        <dt class="col-sm-3">updated_at</dt>
        <dd class="col-sm-9">{{ order.updated_at }}</dd>

    </dl>

    <button class="btn btn-primary" @click="processOrder">process order</button>
</div>

<?php
$js = <<<JS
const { createApp, reactive, ref, onMounted } = Vue;

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
  

        // Fetch data when the component is mounted
        onMounted(fetchOrder);

        return {
            order,
            title
        };

  
    }
}).mount('#order-app');
JS;

$this->registerJs($js, \yii\web\View::POS_END);
?>