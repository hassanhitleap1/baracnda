<?php

use kartik\file\FileInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\pages\Pages $model */
/** @var yii\widgets\ActiveForm $form */


$dataImage = [
    'showCaption' => false,
    'showRemove' => false,
    'showUpload' => false,
    'browseClass' => 'btn btn-dark btn-block mt-2 btn-file',
    'browseLabel' => 'Select Image',
    'allowedFileTypes' => ['image'],
    'initialPreview' => [
        $model->image ? Html::img(Yii::getAlias('@web/' . $model->image), ['class' => 'file-preview-image', 'alt' => 'Uploaded Image']) : null,
    ],
    'initialCaption' => $model->file ? basename($model->image) : '',


];



?>

<div class="pages-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-6">
        <?= $form->field($model, 'key')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-6">
        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>
    </div>


    <div class="row">
        <div class="col-6">
        <?= $form->field($model, 'desc')->textarea(['rows' => 6]) ?>
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
