<?php

use app\models\categories\Categories;
use app\models\users\Users;
use app\models\warehouses\Warehouses;
use kartik\file\FileInput;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$categories=ArrayHelper::map(Categories::find()->all(),'id','name');
$users=ArrayHelper::map(Users::find()->all(),'id','name');
$warehouses=ArrayHelper::map(Warehouses::find()->all(),'id','name');
$dataImages = [];

if (!$model->isNewRecord) {
    foreach ($model->images as $key => $value) {
        $images_path[] = Yii::getAlias('@web') . '/' . $value['image_path'];
    }
    if (count($model->images) === 0) {
        $dataImages = [
            'showCaption' => true,
            'showRemove' => true,
            'showUpload' => false
        ];
    } else {
        $dataImages = [
            'showCaption' => true,
            'showRemove' => true,
            'showUpload' => false,
            'initialPreview' => $images_path,
            'initialPreviewAsData' => true,
            // 'initialCaption' => Yii::getAlias('@web') . '/' . $model->thumbnail,
            'initialPreviewConfig' => [
                ['caption' => $model->name],
            ],
            'overwriteInitial' => true

        ];
    }
}

/** @var yii\web\View $this */
/** @var app\models\products\Products $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="products-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-6">
        <?=  $form->field($model, 'creator_id')->widget(Select2::classname(), [
                'data' => $users,
                'options' => ['placeholder' => 'Select a creator'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
        ]);?>
        </div>
        <div class="col-6">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
        </div>
        <div class="col-6">
        <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    
    <div class="row">
        <div class="col-6">
        <?= $form->field($model, 'cost')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-6">
            <?=  $form->field($model, 'category_id')->widget(Select2::classname(), [
                'data' => $categories,
                'options' => ['placeholder' => 'Select a categories'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
        ]);?>

        </div>
    </div>
    <div class="row">
        <div class="col-6">
        <?=  $form->field($model, 'warehouse_id')->widget(Select2::classname(), [
                'data' => $warehouses,
                'options' => ['placeholder' => 'Select a warehouses'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
        ]);?>
        
        </div>
        <div class="col-6">
        <?= $form->field($model, 'image_path')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="row">
            <div class="col-6">
            <?= $form->field($model, 'images[]')->widget(FileInput::classname(), [
                'options' => ['accept' => 'image/*', 'multiple' => true],
            ]); ?>
            </div>
           </div>

    </div>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
