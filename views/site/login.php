<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div>
    <div class="container my-5">
        <!-- <div class="breadcrumbs mb-3">
            <span>
                <a href="/" title="Go to Neutron sys web" class="home">Home</a>
            </span>
            <span><i class="fa fa-angle-right"></i></span>
            <span>
                <?= Html::encode($this->title) ?>
            </span>
        </div> -->
        <a href="<?= Url::to(['site/index']) ?>">
            <?= Html::img(Yii::getAlias('@web') . '/logo.png', ['alt' => 'Neutron sys logo', 'class' => 'animation__shake']) ?>
        </a>
        <div class="row login-page">
            <div class="col-12">
                <h1 class="my-5">
                    <?= Html::encode($this->title) ?>
                </h1>

                <?php $form = ActiveForm::begin([
                    'id' => 'login-form',
                ]); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'rememberMe')->checkbox([
                    'template' => "<div class=\"custom-control custom-checkbox\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
                ]) ?>

                <div class="form-group">
                    <div>
                        <?= Html::submitButton('Login', ['class' => 'btn btn-primary btn-lg w-100', 'name' => 'login-button']) ?>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
            <div class="text-center my-5">
                Copyright © 2024 sys. All rights reserved.
            </div>
        </div>
    </div>
</div>