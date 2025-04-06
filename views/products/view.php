<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\products\Products $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="products-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
         
            [
                'label' => Yii::t('app', 'Creator'),
                'value' => $model->creator ? $model->creator->username : null,
            ],
            'name',
            'description:html',
            'price',
            'cost',
            'category_id',
            [
                'label' => Yii::t('app', 'Category'),
                'value' => $model->category ? $model->category->name : null,
            ],

            [
                'label' => Yii::t('app', 'Warehouse'),
                'value' => $model->warehouse ? $model->warehouse->name : null,
            ],
        
            [
                'label' => Yii::t('app', 'Images'),
                'format' => 'html',
                'value' => function ($model) {
                    $images = $model->images;
                    $html = '';
                    foreach ($images as $image) {
                        $html .= Html::img($image->getImageUrl(), ['style' => 'width:100px; margin-right:10px;']);
                    }
                    return $html;
                },
            ],
            [
                'label' => Yii::t('app', 'Variants'),
                'format' => 'html',
                'value' => function ($model) {
                    $variants = $model->variants;
                    $html = '<ul>';
                    foreach ($variants as $variant) {
                        $html .= '<li>' . Html::encode($variant->name) . ' - ' . Yii::$app->formatter->asCurrency($variant->price, 'USD') . '</li>';
                    }
                    $html .= '</ul>';
                    return $html;
                },
            ],
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
