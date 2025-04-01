<?php

use app\models\countries\Countries;
use app\models\regions\Regions;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\orders\Orders $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="orders-form">

    <?php $form = ActiveForm::begin(); ?>



    <div class="card">
        <div class="card-header">
            <?= Yii::t('app', 'Variants') ?>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="variantSearchInput"><?= Yii::t('app', 'Search Variants') ?></label>
                <input type="text" id="variantSearchInput" class="form-control" placeholder="<?= Yii::t('app', 'Enter variant name...') ?>">
                <div id="variantSearchResults" class="dropdown-menu show" style="width: 100%;"></div>
            </div>
            <div id="orderItems" class="mt-3">
                <!-- Selected variants will be appended here -->
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <?= Yii::t('app', 'address') ?>
        </div>
        <div class="card-body">
            <div class="row">
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
      
         
    </div>
</div>


   <div class="card">
        <div class="card-header">
            <?= Yii::t('app', 'shipping') ?>
        </div>
        <div class="card-body">
            <div class="row">   
                <div class="col-6">
                  <?= $form->field($model, 'status_id')->textInput() ?>
                </div>
                <div class="col-6">
                  <?= $form->field($model, 'shipping_id')->textInput() ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <?= Yii::t('app', 'address') ?>
        </div>
        <div class="card-body">
            <div class="row">   
                <div class="col-12">
                    <?= $form->field($model, 'note')->textarea(['rows' => 6]) ?>
                </div>
            </div>
        </div>
    </div>
  

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
