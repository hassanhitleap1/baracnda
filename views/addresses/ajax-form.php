<?php

use app\models\countries\Countries;
use app\models\regions\Regions;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\addresses\Addresses $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="addresses-form">

    <?php $form = ActiveForm::begin([
        'id' => 'address-form',
        'action' => ['addresses/update-address', 'id' => $model->id],
        'options' => [
            'data-pjax' => true,
            'class' => 'form-horizontal',
            'enctype' => 'multipart/form-data',
        ],
    ]); ?>
    <div class="row">
        <div class="col-6">
        <?=   $form->field($model, 'country_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(Countries::find()->all(), 'id', 'name'),
            'options' => ['placeholder' => 'Select a attribute'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]); ?>
        </div>
        <div class="col-6">
        <?=   $form->field($model, 'region_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(Regions::find()->all(), 'id', 'name'),
            'options' => ['placeholder' => 'Select a attribute'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]); ?>

        </div>
    </div>
    <div class="row">
        <div class="col-6">
        <?= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-6">
        <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
 
    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
    <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success btn-block', 'id' => 'save-address']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
