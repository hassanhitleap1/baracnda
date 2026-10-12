<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = Yii::t('app', 'Sign in');
?>
<main class="auth-shell">
    <div>
        <div class="auth-card">
            <?= Html::img(Yii::getAlias('@web') . '/logo.png', ['alt' => Yii::$app->name, 'class' => 'auth-logo']) ?>

            <h1><?= Html::encode($this->title) ?></h1>
            <p class="auth-sub"><?= Yii::t('app', 'Use your staff username and password.') ?></p>

            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

            <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'autocomplete' => 'username']) ?>

            <?= $form->field($model, 'password')->passwordInput(['autocomplete' => 'current-password']) ?>

            <?= $form->field($model, 'rememberMe')->checkbox() ?>

            <?= Html::submitButton(Yii::t('app', 'Sign in'), ['class' => 'btn btn-primary btn-lg w-100 mt-2', 'name' => 'login-button']) ?>

            <?php ActiveForm::end(); ?>
        </div>
        <p class="auth-foot">&copy; <?= date('Y') ?> <?= Html::encode(Yii::$app->name) ?></p>
    </div>
</main>
