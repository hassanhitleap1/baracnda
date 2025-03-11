<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\shippings\Shippings $model */

$this->title = Yii::t('app', 'Create Shippings');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Shippings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shippings-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
