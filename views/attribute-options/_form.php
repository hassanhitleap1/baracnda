<?php

use app\models\attributes\Attributes;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$attributes=ArrayHelper::map(Attributes::find()->all(),'id','name');
/** @var yii\web\View $this */
/** @var app\models\attributeOptions\AttributeOptions $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="attribute-options-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'value')->textInput(['maxlength' => true]) ?>

    <?=   $form->field($model, 'attribute_id')->widget(Select2::classname(), [
    'data' => ArrayHelper::map(Attributes::find()->all(), 'id', 'name'),
    'options' => ['placeholder' => 'Select a attribute'],
    'pluginOptions' => [
        'allowClear' => true
    ],
]); ?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
