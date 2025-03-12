<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\variantAttributes\VariantAttributes $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="variant-attributes-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'is_default')->textInput() ?>

    <?= $form->field($model, 'variant_id')->textInput() ?>

    <?= $form->field($model, 'attribute_id')->textInput() ?>

    <?= $form->field($model, 'option_id')->textInput() ?>



    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
