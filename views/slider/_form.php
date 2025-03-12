<?php

use kartik\file\FileInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\slider\Slider $model */
/** @var yii\widgets\ActiveForm $form */

$dataImage = [
    'showCaption' => false,
    'showRemove' => false,
    'showUpload' => false,
    'browseClass' => 'btn btn-dark btn-block mt-2 btn-file',
    'browseLabel' => 'Select Image',
    'allowedFileTypes' => ['image'],
    'initialPreview' => [
        $model->src ? Html::img(Yii::getAlias('@web/' . $model->src), ['class' => 'file-preview-image', 'alt' => 'Uploaded Image']) : null,
    ],
    'initialCaption' => $model->file ? basename($model->src) : '',


];


?>

<div class="slider-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-6">
        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'sub_title')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
        <?= $form->field($model, 'text')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-6">
        <?= $form->field($model, 'file')->widget(FileInput::classname(), [
                'options' => ['accept' => 'image/*'],
                'pluginOptions' => $dataImage
            ]);
            ?>
        </div>
    </div>
   
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
