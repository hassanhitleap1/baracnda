<?php

use app\models\status\Status;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\widgets\Pjax;
use yii\bootstrap5\Modal; // Use Bootstrap 5 Modal
use kartik\daterange\DateRangePicker;
use yii\helpers\ArrayHelper;
use kartik\dynagrid\DynaGrid;
/** @var yii\web\View $this */
/** @var app\models\orders\OrdersSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Orders');
$this->params['breadcrumbs'][] = $this->title;

// Define the $statusList variable
$statusList = \yii\helpers\ArrayHelper::map(Status::find()->all(), 'id', 'name');

// Set default currency code for the formatter
Yii::$app->formatter->currencyCode = 'JOD'; // Replace 'USD' with your desired currency code

$columns = [
    ['class' => 'kartik\grid\SerialColumn', 'order' => DynaGrid::ORDER_FIX_LEFT],
    [
        'attribute' => 'id',
        'label' => Yii::t('app', 'Order ID'),
        'vAlign' => 'middle',
        'width' => '100px',
    ],
    [
        'attribute' => 'creator_id',
        'label' => Yii::t('app', 'Creator'),
        'value' => 'creator.full_name',
        'filter' => ArrayHelper::map(\app\models\users\Users::find()->all(), 'id', 'full_name'),
        'vAlign' => 'middle',
        'width' => '150px',
    ],
    [
        'attribute' => 'address_id',
        'label' => Yii::t('app', 'Full Address'),
        'value' => function ($model) {
            return $model->addresses ? $model->addresses->full_name . ', ' . $model->addresses->address . ', ' . $model->addresses->region->name : null;
        },
        'vAlign' => 'middle',
        'width' => '200px',
    ],
    [
        'attribute' => 'status_id',
        'label' => Yii::t('app', 'Status'),
        'value' => 'status.name',
        'filter' => ArrayHelper::map(\app\models\status\Status::find()->all(), 'id', 'name'),
        'vAlign' => 'middle',
        'width' => '100px',
    ],
    [
        'attribute' => 'shipping_price',
        'footer' => Yii::$app->formatter->asCurrency($dataProvider->query->sum('shipping_price')),
        'vAlign' => 'middle',
        'width' => '100px',
    ],
    [
        'attribute' => 'sub_total',
        'footer' => Yii::$app->formatter->asCurrency($dataProvider->query->sum('sub_total')),
        'vAlign' => 'middle',
        'width' => '100px',
    ],
    [
        'attribute' => 'total',
        'footer' => Yii::$app->formatter->asCurrency($dataProvider->query->sum('total')),
        'vAlign' => 'middle',
        'width' => '100px',
    ],
    [
        'attribute' => 'orderItems',
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
        'vAlign' => 'middle',
        'width' => '300px',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => '{view} {update} {delete} {invoice}',
        'buttons' => [
            'invoice' => function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-file"></span>', ['orders/invoice', 'id' => $model->id], [
                    'title' => Yii::t('app', 'View Invoice'),
                    'data-pjax' => '0',
                ]);
            },
        ],
    ],
    ['class' => 'kartik\grid\CheckboxColumn', 'order' => DynaGrid::ORDER_FIX_RIGHT],
];
?>
<div class="orders-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?php Pjax::begin(); ?>
    <?= DynaGrid::widget([
        'columns' => $columns,
        'storage' => DynaGrid::TYPE_COOKIE,
        'theme' => 'panel-success',
        'gridOptions' => [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'panel' => [
                'heading' => '<h3 class="panel-title">' . $this->title . '</h3>',
                'before' => '{dynagrid}' .  Html::a("<span class='fas fa-plus' > </span>", ['create'], ['class' => 'btn btn-success', 'title' => Yii::t('app', 'Create_Order')]) .
                    "<button id='print_all_invoice' style='display: none;' class='btn btn-success' title='" . Yii::t('app', 'Print All Invoice') . "' > <i class='fas fa-print'></i> " . Yii::t('app', 'Print All Invoice') . "</button>" .
                    "<button id='export_pdf' class='btn btn-success' title='" . Yii::t('app', 'Export PDF') . "' > <i class='fas fa-file-pdf'></i> " . Yii::t('app', 'Export PDF') . "</button>" .
                    "<button id='change_status' class='btn btn-success' title='" . Yii::t('app', 'Change Status') . "' > <i class='fas fa-exchange-alt'></i> " . Yii::t('app', 'Change Status') . "</button>" .
                    "<button id='change_campany' class='btn btn-success' title='" . Yii::t('app', 'Change Company') . "' > <i class='fas fa-building'></i> " . Yii::t('app', 'Change Company') . "</button>" .
                    "<button id='export_to_driver' class='btn btn-success' title='" . Yii::t('app', 'Export to Driver') . "' > <i class='fas fa-truck'></i> " . Yii::t('app', 'Export to Driver') . "</button>" .
                    "<button id='delete_orders' class='btn btn-danger' title='" . Yii::t('app', 'Delete Orders') . "' > <i class='fas fa-trash'></i> " . Yii::t('app', 'Delete Orders') . "</button>"
            ],
            'showPageSummary' => true,
        ],

        'options' => ['id' => 'dynagrid'],  // a unique identifier is important

    ]); ?>
    <?php Pjax::end(); ?>

</div>

<?php

Modal::begin([
    'id'     => 'model',
    'size'   => 'model-lg',
]);

echo "<div id='modelContent'></div>";

Modal::end();

?>

