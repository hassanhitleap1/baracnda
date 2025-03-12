<?php

use app\models\categories\Categories;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$categories=ArrayHelper::map(Categories::find()->all(),'id','name');

/** @var yii\web\View $this */
/** @var app\models\categories\Categories $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="categories-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?=  $form->field($model, 'category_id')->widget(Select2::classname(), [
                'data' => $categories,
                'options' => ['placeholder' => 'Select a categories'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
        ]);?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
