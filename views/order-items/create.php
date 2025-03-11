<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\orderItems\OrderItems $model */

$this->title = Yii::t('app', 'Create Order Items');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Order Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-items-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
