<?php

use app\models\categories\Categories;
use kartik\file\FileInput;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$categories=ArrayHelper::map(Categories::find()->all(),'id','name');

/** @var yii\web\View $this */
/** @var app\models\categories\Categories $model */
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


    <?= $form->field($model, 'file')->widget(FileInput::classname(), [
                'options' => ['accept' => 'image/*'],
                'pluginOptions' => $dataImage
            ]);
            ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
