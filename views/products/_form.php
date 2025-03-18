<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<?php

use app\models\categories\Categories;
use app\models\users\Users;
use app\models\warehouses\Warehouses;
use kartik\file\FileInput;
use kartik\select2\Select2;
use yii\bootstrap5\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>


<?= $this->render('@app/views/products/_modal-dialog') ?>

<div class="products-form" id="products-form" data-product-id="<?= $model->id ?>">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-3">
        <?=  $form->field($model, 'creator_id')->widget(Select2::classname(), [
                'data' => $users,
                'options' => ['placeholder' => 'Select a creator'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
        ]);?>
        </div>
        <div class="col-3">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-3">
        <?=  $form->field($model, 'category_id')->widget(Select2::classname(), [
                'data' => $categories,
                'options' => ['placeholder' => 'Select a categories'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
        ]);?>
        </div>
        <div class="col-3">
        <?=  $form->field($model, 'warehouse_id')->widget(Select2::classname(), [
                'data' => $warehouses,
                'options' => ['placeholder' => 'Select a warehouses'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
        ]);?>
        </div>
    </div>
    <div class="row">
        <div class="col-3">
        <?= $form->field($model, 'cost')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-3">
        <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-3">
        <?= $form->field($model, 'type')->dropDownList([ 'simple' => 'Simple', 'variant' => 'variant']) ?>
        </div>

        <div class="col-3">

            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@mdo">Select Attributes & Generate Variants</button>

        </div>
    </div>
        <div class="row" id="variants-generated">
        </div>
    <div class="row">
        <div class="col-6">
           <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
        </div>
        <div class="col-6">
        <?= $form->field($model, 'images[]')->widget(FileInput::classname(), [
                'options' => ['accept' => 'image/*', 'multiple' => true],
            ]); ?>
        </div>
    </div>
  
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


