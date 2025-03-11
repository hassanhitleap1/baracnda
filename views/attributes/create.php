<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\attributes\Attributes $model */

$this->title = Yii::t('app', 'Create Attributes');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Attributes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attributes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
