<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\countries\Countries $model */

$this->title = Yii::t('app', 'Create Countries');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Countries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="countries-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
