<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\variants\Variants $model */

$this->title = Yii::t('app', 'Create Variants');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Variants'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="variants-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
