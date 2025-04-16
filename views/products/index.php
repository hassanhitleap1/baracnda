<?php

use app\models\products\Products;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\daterange\DateRangePicker;
/** @var yii\web\View $this */
/** @var app\models\products\ProductsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Products');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="products-index">

    <h1><?= Html::encode($this->title) ?></h1>
  
    <?php if (Yii::$app->user->can('products/index')): ?>   
    <p>
        <?= Html::a(Yii::t('app', 'Create Products'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php endif; ?>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            [
                'attribute' => 'creator_id',
                'label' => Yii::t('app', 'Creator'),
                'value' => function ($model) {
                    return $model->creator ? $model->creator->username : null;
                },
            ],
            'name',
            [
                'attribute' => 'description',
                'format' => 'html',
            ],
            'price',
            'cost',
            [
                'attribute' => 'category_id',
                'label' => Yii::t('app', 'Category'),
                'value' => function ($model) {
                    return $model->category ? $model->category->name : null;
                },
                'filter' => ArrayHelper::map(\app\models\categories\Categories::find()->all(), 'id', 'name'),
            ],
            [
                'attribute' => 'warehouse_id',
                'label' => Yii::t('app', 'Warehouse'),
                'value' => function ($model) {
                    return $model->warehouse ? $model->warehouse->name : null;
                },
                'filter' => ArrayHelper::map(\app\models\warehouses\Warehouses::find()->all(), 'id', 'name'),
            ],
            [
                'attribute' => 'image_path',
                'label' => Yii::t('app', 'Image'),
                'format' => 'html',
                'value' => function ($model) {
                    return Html::img($model->getImageUrl(), ['style' => 'width:50px; margin-right:10px;']);
                },
            ],
            'quantity',
            [
                'attribute' => 'variants',
                'label' => Yii::t('app', 'Variants'),
                'format' => 'html',
                'value' => function ($model) {
                    $variants = $model->variants;
                    $html = '<ul>';
                    foreach ($variants as $variant) {
                        $html .= '<li>' . Html::encode($variant->name) . ' - ' . Yii::$app->formatter->asCurrency($variant->price, 'JOD') .' Quantity:  '. $variant->quantity . '</li>';
                    }
                    $html .= '</ul>';
                    return $html;
                },
            ],
            [
                'attribute' => 'created_at',
                'label' => Yii::t('app', 'Created At'),
                'format' => 'datetime',
                'filter' => DateRangePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'created_at',
                    'convertFormat' => true,
                    'pluginOptions' => [
                        'timePicker' => true,
                        'timePickerIncrement' => 30,
                        'locale' => [
                            'format' => 'Y-m-d H:i:s',
                        ],
                    ],
                ]),
            ],
            'updated_at',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return Yii::$app->user->can('products/update') ? Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['class' => 'btn btn-primary']) : '';
                    },
                    'delete' => function ($url, $model, $key) {
                        return Yii::$app->user->can('products/delete') ? Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, ['class' => 'btn btn-danger']) : '';
                    },
                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
