<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\orders\Orders $model */

$this->title = Yii::t('app', 'Update Orders: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="orders-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
