<?php

use app\models\attributes\Attributes;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/** @var yii\widgets\ActiveForm $form */
/** @var app\models\Product $model */
$attributes= ArrayHelper::map(Attributes::find()->all(), 'id', 'name');
?>

<div class="variant-form">
    <?php $form = ActiveForm::begin([
        'id' => 'variant-generation-form',
        'action' => Url::to(['product/generate-variants']),
        'options' => ['data-pjax' => 1]
    ]); ?>

    <?= Html::dropDownList('attributes[]', null, $attributes, [
        'class' => 'form-control',
        'multiple' => true
    ]) ?>

    <div class="form-group mt-3">
        <?= Html::submitButton('Generate Variants', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
