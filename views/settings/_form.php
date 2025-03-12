<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\settings\Settings $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="settings-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-6">
         <?= $form->field($model, 'facebook')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-6">
        <?= $form->field($model, 'instagram')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-6">
        <?= $form->field($model, 'snapchat')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-6">
        <?= $form->field($model, 'tiktok')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-6">
        <?= $form->field($model, 'youtube')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-6">
        <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-6">
        <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-6">
        <?= $form->field($model, 'fax')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-6">
        <?= $form->field($model, 'lag')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-6">
        <?= $form->field($model, 'lat')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-6">
        <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-6">
        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
