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

<div class="products-form">

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
        <?= Html::button('Select Attributes & Generate Variants', [
        'class' => 'btn btn-primary',
        'id' => 'open-variant-modal',
    ]) ?>
        </div>
    </div>

    <div class="row">
      <!-- عرض المتغيرات -->
        <div id="variant-list">
            <!-- سيتم عرض المتغيرات هنا عبر AJAX -->
        </div>
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
    <div class="row">
        <div class="col-6">
 
        
        </div>
        <div class="col-6">
      
        </div>
        <div class="row">
            <div class="col-6">
          
            </div>
           </div>

    </div>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<?php
// نافذة منبثقة لاختيار السمات
Modal::begin([
    'title' => 'Select Attributes',
    'id' => 'variant-modal',
    'size' => 'modal-lg',
]);

Pjax::begin(['id' => 'variant-pjax']);
echo $this->render('_variant_form', ['model' => $model]);
Pjax::end();

Modal::end();
?>
<?php
$this->registerJs("
    $('#open-variant-modal').on('click', function () {
    alert('test');
        $('#variant-modal').modal('show');
    });
");


$this->registerJs("
    $('#variant-generation-form').on('beforeSubmit', function (e) {
        e.preventDefault();
        let form = $(this);
        
        $.post(form.attr('action'), form.serialize(), function (response) {
            if (response.status === 'success') {
                let variantsHtml = '<h3>Generated Variants</h3><ul>';
                response.variants.forEach(variant => {
                    variantsHtml += '<li>' + variant.join(', ') + '</li>';
                });
                variantsHtml += '</ul>';
                
                $('#variant-list').html(variantsHtml);
                $('#variant-modal').modal('hide');
            } else {
                alert(response.message);
            }
        });

        return false;
    });
");
?>

