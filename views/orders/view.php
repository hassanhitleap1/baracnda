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
    <ul>
        <li v-for="(value, key) in order" :key="key">
            <strong>{{ key }}:</strong> {{ value }}
        </li>
    </ul>
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