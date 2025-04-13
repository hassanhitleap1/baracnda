<?php

use app\models\roles\Roles;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\users\Users $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="users-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-6">
        <?= $form->field($model, 'username')->textInput(['maxlength' => true, 'placeholder' => 'Enter your username']) ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'email')->input('email', ['maxlength' => true, 'placeholder' => 'Enter your email']) ?>
        </div>
    </div>
      
    <div class="row">
        <div class="col-6">
            <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'placeholder' => 'Enter your phone number']) ?>
        </div>
        <div class="col-6">
            <?= $form->field($model, 'password')->passwordInput(['maxlength' => true, 'placeholder' => 'Enter your password']) ?>
        </div>
    </div>

         
    <div class="row">
        <div class="col-6">
        <?= $form->field($model, 'full_name')->textInput(['maxlength' => true, 'placeholder' => 'Enter your full name']) ?>
        </div>
        <div class="col-6">
         <?= $form->field($model, 'birth_date')->input('date') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <?= $form->field($model, 'role_id')->dropDownList(ArrayHelper::map(Roles::find()->all(), 'id', 'name'), ['prompt' => 'Select Role']) ?>
        </div>
        <div class="col-6">
           <?= $form->field($model, 'address_id')->textInput(['placeholder' => 'Enter address ID']) ?>
        </div>
    </div>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary btn-lg']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>