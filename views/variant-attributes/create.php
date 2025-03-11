<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\variantAttributes\VariantAttributes $model */

$this->title = Yii::t('app', 'Create Variant Attributes');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Variant Attributes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="variant-attributes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
