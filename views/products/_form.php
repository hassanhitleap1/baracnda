<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.js"></script>

<!-- include summernote css/js-->
<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.2/summernote.css" rel="stylesheet">
<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.js" defer></script>

<?php

use app\models\attributeOptions\AttributeOptions;
use app\models\attributes\Attributes;
use app\models\categories\Categories;

use app\models\warehouses\Warehouses;
use Dom\Attr;
use kartik\editors\Summernote;
use kartik\file\FileInput;
use kartik\select2\Select2;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

use kartik\form\ActiveForm;
use yii\helpers\Url;

$categories = ArrayHelper::map(Categories::find()->all(), 'id', 'name');

$warehouses = ArrayHelper::map(Warehouses::find()->all(), 'id', 'name');


$dataImages = [];
$images_path_product = [];
$initialPreviewConfigs = [];
foreach ($model->images as $key => $value) {
    $images_path_product[] = Html::img(Yii::getAlias('@web/' . $value->image_path), ['class' => 'file-preview-image', 'alt' => 'Uploaded Image']);
    $initialPreviewConfigs[] = ['caption' => 'Image 1', 'url' => Url::to(['remove-image', 'id' => $value->id])];
}


$dataImages = [
    'showCaption' => false,
    'showRemove' => false,
    'showUpload' => false,
    'browseClass' => 'btn btn-primary btn-block',
    'browseLabel' => 'Select Image',
    'allowedFileTypes' => ['image'],
    // 'initialPreviewAsData' => true,
    'initialPreview' => $images_path_product,
    'initialPreviewConfig' => $initialPreviewConfigs,
    // 'overwriteInitial' => true,

];
// if (!$model->isNewRecord) {
//     foreach ($model->images as $key => $image) {
//         $images_path[] = $image->imageUrl;
//     }
//     if (count($model->images) === 0) {
//         $dataImages = [
//             'showCaption' => true,
//             'showRemove' => true,
//             'showUpload' => false
//         ];
//     } else {
//         $dataImages = [
//             'showCaption' => true,
//             'showRemove' => true,
//             'showUpload' => false,
//             'initialPreview' => $images_path,
//             'initialPreviewAsData' => true,
//             // 'initialCaption' => Yii::getAlias('@web') . '/' . $model->thumbnail,
//             'initialPreviewConfig' => [
//                 ['caption' => $model->name],
//             ],
//             'overwriteInitial' => true

//         ];
//     }
// }

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


    <div class="card">
        <div class="card-header">
            <?= Yii::t('app', 'Product_Information') ?>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-3">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-3">
                    <?= $form->field($model, 'category_id')->widget(Select2::classname(), [
                        'data' => $categories,
                        'options' => ['placeholder' => 'Select a categories'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
                </div>
                <div class="col-3">
                    <?= $form->field($model, 'warehouse_id')->widget(Select2::classname(), [
                        'data' => $warehouses,
                        'options' => ['placeholder' => 'Select a warehouses'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
                </div>
                <div class="col-3">
                    <?= $form->field($model, 'cost')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-3">
                    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-3">
                    <?= $form->field($model, 'quantity')->textInput(['maxlength' => true]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <?= $form->field($model, 'description')->textarea(['id' => 'summernote']) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-3">
            <?= $form->field($model, 'type')->dropDownList(['simple' => 'Simple']) ?>
        </div>

        <div class="col-3">

            <button type="button" class="btn btn-primary" id="btn-modal-open" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@mdo0" style="display: none;">Select Attributes & Generate Variants</button>

        </div>
    </div>
    

    <div class="card-body">
            <div class="card-header">
                                                
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <?= $form->field($model, 'files[]')->widget(FileInput::classname(), [
                            'options' => ['accept' => 'image/*', 'multiple' => true],
                            'pluginOptions' => $dataImages
                        ]); ?>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    <?php ActiveForm::end(); ?>


    <script>
        $(document).ready(function() {
            $('#summernote').summernote({
                lang: 'fr-FR', // <= nobody is perfect :)
                height: 300,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['link', ['link']],
                    ['picture', ['picture']]
                ],
                callbacks: {
                    onImageUpload: function(image) {
                        uploadImage(image[0]);
                    }
                }
            });

            // Capture the selected variant_is_default index
            $('.variant-default-radio').on('change', function() {
                const selectedIndex = $(this).val();
                console.log('Selected variant_is_default index:', selectedIndex);
            });
        });

        function uploadImage(image) {
            var data = new FormData();
            data.append("image", image);
            $.ajax({
                data: data,
                type: "POST",
                url: `${SITE_URL}/index.php?r=medialibrary/upload`,
                // returns a chain containing the path
                cache: false,
                contentType: false,
                processData: false,
                success: function(url) {

                    var image = document.location.origin + url;
                    setTimeout(function() {
                        $('#summernote').summernote("insertImage", image);
                    }, 500);

                },
                error: function(data) {
                    console.log(data);
                }
            });
        }
    </script>