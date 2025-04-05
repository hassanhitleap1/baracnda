<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\orders\OrdersSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Orders');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orders-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Orders'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            'id',
            'user.full_name',
            'creator.full_name',
            [
                'attribute' => 'address_id',
                'label' => Yii::t('app', 'Full Address'),
                'value' => function ($model) {
                    return $model->addresses ? $model->addresses->full_name . ', ' . $model->addresses->address . ', ' . $model->addresses->region->name : null;
                },
            ],
            'status.name',
            'shopping_price',
            'sub_total',
            'profit',
            'total',
            'shipping.name',
            'note:ntext',
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
            ],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, $model, $key, $index) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                },
            ],
        ],
        'responsive' => true,
        'hover' => true,
        'panel' => [
            'type' => 'primary',
            'heading' => Yii::t('app', 'Orders List'),
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
