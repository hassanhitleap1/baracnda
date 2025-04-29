<?php



/** @var yii\web\View $this */
/** @var app\models\orders\Orders $model */

$this->title = Yii::t('app', 'Order #') . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\yii\web\YiiAsset::register($this);

// Register Vue.js and Axios
$this->registerJsFile('https://cdn.jsdelivr.net/npm/vue@3.2.47/dist/vue.global.min.js', ['position' => \yii\web\View::POS_HEAD]);
$this->registerJsFile('https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js', ['position' => \yii\web\View::POS_HEAD]);
$this->registerJsFile('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', ['position' => \yii\web\View::POS_HEAD]);

// Pass the order ID to JavaScript
$orderId = $model->id;
?>

<div class="orders-view" id="order-app">
    <!-- Shipping Address Modal -->
<!-- <div class="modal fade" id="shippingAddressModal" tabindex="-1" aria-labelledby="shippingAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shippingAddressModalLabel">Edit Shipping Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="shippingAddressForm">
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="full_name" v-model="shippingAddress.full_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" v-model="shippingAddress.address" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" class="form-control" id="city" v-model="shippingAddress.city" required>
                    </div>
                    <div class="mb-3">
                        <label for="region_id" class="form-label">Region</label>
                        <select class="form-control" id="region_id" v-model="shippingAddress.region_id" required>
                            <option v-for="region in regions" :value="region.id">{{ region.name }}</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="postal_code" class="form-label">Postal Code</label>
                        <input type="text" class="form-control" id="postal_code" v-model="shippingAddress.postal_code" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" v-model="shippingAddress.phone" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" @click="saveShippingAddress">Save Changes</button>
            </div>
        </div>
    </div>
</div> -->
    <h1>{{ title }}</h1>
    {{shippingAddress}}
   <!-- Add this inside your form, typically near the submit button -->
    <!-- Order Status -->
    <div class="order-status mb-4">
        <span class="badge bg-primary">Order Status: {{ order.status?.name || 'N/A' }}</span>
        <span class="badge bg-secondary ms-2">Payment Status: {{ order.payment_status || 'N/A' }}</span>
        <span class="badge bg-success ms-2">Delivery Status: {{ order.delivery_status || 'N/A' }}</span>
    </div>

    <div class="row">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Add Variants</h3>
            </div>
            <div class="card-body">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" v-model="variantSearch"  @input="searchVariants" placeholder="Search for variants">
                    <button class="btn btn-primary" @click="searchVariants" >Search</button>
                </div>
                <div v-if="searchResults.length">
                    <ul class="list-group">
                        <li v-for="variant in searchResults" :key="variant.id" class="list-group-item d-flex justify-content-between align-items-center">
                            <img v-if="variant.image" :src="variant.image" class="img-fluid me-2" style="max-height: 50px;">
                            <span>{{ variant.name }}</span>
                            <button 
                            class="btn btn-success btn-sm" 
                            :class='isLoading ? "loader" : ""' 
                            :disabled="order.orderItems.some(item => item.variant_id === variant.id) || isLoading" 
                            @click="addVariantToOrder(variant)"
                            >
                            Add
                            </button>
                        </li>
                    </ul>
                </div>
                <div v-else class="text-muted">No variants found</div>
            </div>
        </div>
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
                                <div class="col-md-4">
                                    <h5>{{ item.product?.name || 'Product not found' }}</h5>
                                    <div v-if="item.variant">
                                        <small class="text-muted">Variant: {{ item.variant.name }}</small>
                                    </div>

                                </div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <span class="input-group-text">Qty</span>
                                        <input type="number" class="form-control" v-model="item.quantity" min="1" @change="updateQuantity(item.id, item.quantity)" :disabled="isLoading">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <h5>${{ (item.price * item.quantity).toFixed(2) }}</h5>
                                    <small class="text-muted">${{ item.price }} each</small>
                                </div>
                                <div class="col-1 text-end">
                                        <i class="fas fa-trash"  @click="removeVariant(item.id)" title="Remove Item"></i>
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
                    <h3 class="card-title">Shipping Address</h3> <i class="fas fa-edit float-end" title="Edit Address" @click="editShippingAddress"></i>
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
const { createApp, reactive, ref, onMounted, computed } = Vue;

createApp({
    setup() {
        const order = reactive({});
        const title = ref('Loading...');
        const orderId = $model->id;
        const variantSearch = ref('');
        var isLoading = ref(false);
        const searchResults = reactive([]);
        const shippingAddress = reactive({});
        const regions = reactive([]);
        let shippingAddressModal = null;

        const fetchOrder = async () => {
            try {
                const response = await axios.get(`/api/orders/view?id=${orderId}`);
                Object.assign(order, response.data);

                if (order.addresses) {
                    Object.assign(shippingAddress, order.addresses);
                }
                title.value = `Order #${orderId}`;
            } catch (error) {
                console.error('Error fetching order data:', error);
                title.value = 'Error loading order';
            }
        };

        const searchVariants = async () => {
            try {
                const response = await axios.get(`/api/variants/search`, {
                    params: { query: variantSearch.value }
                });
                searchResults.splice(0, searchResults.length, ...response.data);
            } catch (error) {
                console.error('Error searching variants:', error);
            }
        };

        const addVariantToOrder = async (variant) => {
            try {
                const formData = new FormData();
                if (isLoading.value) return; // prevent multiple clicks
                isLoading.value = true;
                formData.append('variantId', variant.id);
                formData.append('price', variant.price);
                formData.append('quantity', 1);
                axios.post(`/api/orders/add-variant?id=${orderId}`,formData)
                .then(response => {
                    if (response.data.success) {
                    fetchOrder(); // Refresh order data
                        } else {
                            console.error('Failed to add variant to order:', response.data.message);
                            alert(response.data.message)
                        }
                })
                .catch(error => {
                    console.error('Error adding variant to order:', error);
                    alert('Error adding variant to order'); 
                }).finally(() => {
                    isLoading.value = false;
                    variantSearch.value = []; // Clear the search input
                    
                });

            } catch (error) {
                alert('Error adding variant to order'); 
                console.error('Error adding variant to order:', error);
            }
        };

     
        const removeVariant = async (variantId) => {    
            if (confirm('Are you sure you want to remove this variant from the order?')) {
                if (isLoading.value) return; // prevent multiple clicks
                isLoading.value = true;
                const formData = new FormData();
                formData.append('orderItemId', variantId);
                axios.post(`/api/orders/remove-variant?id=${orderId}`, formData)
                    .then(response => {
                        if (response.data.success) {
                            fetchOrder(); // Refresh order data
                        } else {
                            console.error('Failed to remove variant from order:', response.data.message);
                            alert(response.data.message)
                        }
                    })
                    .catch(error => {
                        console.error('Error removing variant from order:', error);
                        alert('Error removing variant from order'); 
                    }).finally(() => {
                        isLoading.value = false;
                    });
            }
        }

        const updateQuantity = async (orderItemId, quantity) => {
            if (isLoading.value) return; // prevent multiple clicks
            isLoading.value = true;
            const formData = new FormData();
            formData.append('orderItemId', orderItemId);
            formData.append('quantity', quantity);
            axios.post(`/api/orders/update-quantity?id=${orderId}`, formData)
                .then(response => {
                    if (response.data.success) {
                        fetchOrder(); // Refresh order data
                    } else {
                        console.error('Failed to update quantity:', response.data.message);
                        alert(response.data.message)
                    }
                })
                .catch(error => {
                    console.error('Error updating quantity:', error);
                    alert('Error updating quantity'); 
                }).finally(() => {
                    isLoading.value = false;
                });
        };    

        const editShippingAddress = () => {
            if (!shippingAddressModal) {
                shippingAddressModal = new bootstrap.Modal(document.getElementById('shippingAddressModal'));
            }
            shippingAddressModal.show();
        };

        const saveShippingAddress = async () => {
            try {
                isLoading.value = true;
                const response = await axios.post(`/api/orders/update-address?id=${orderId}`, shippingAddress);
                
                if (response.data.success) {
                    // Update the order data
                    Object.assign(order.addresses, shippingAddress);
                    if (shippingAddressModal) {
                        shippingAddressModal.hide();
                    }
                } else {
                    alert('Failed to update shipping address: ' + response.data.message);
                }
            } catch (error) {
                console.error('Error updating shipping address:', error);
                alert('Error updating shipping address');
            } finally {
                isLoading.value = false;
            }
        };


        const fetchRegions = async () => {
            try {
                const response = await axios.get('/api/regions');
                regions.splice(0, regions.length, ...response.data);
            } catch (error) {
                console.error('Error fetching regions:', error);
            }
        };

        onMounted(() => {
            fetchOrder();
            fetchRegions();
        });

        return {
            order,
            title,
            variantSearch,
            searchResults,
            searchVariants,
            addVariantToOrder,
            removeVariant,
            isLoading,
            updateQuantity,
            editShippingAddress,
            saveShippingAddress,
            fetchRegions
        };
    }
}).mount('#order-app');
JS;

$this->registerJs($js, \yii\web\View::POS_END);
?>