<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\images\Images $model */

$this->title = Yii::t('app', 'Create Images');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Images'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="images-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
