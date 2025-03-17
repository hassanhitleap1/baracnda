<?php

use app\models\attributes\Attributes;
use yii\helpers\Html;

/** @var yii\widgets\ActiveForm $form */
/** @var app\models\Product $model */

$attributes = Attributes::find()->all();
?>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">genarte  variant</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php foreach ($attributes as $attribute): ?>
          <div class="row">
            <h2><?= Html::encode($attribute->name) ?></h2>
            <?php foreach ($attribute->options as $option): ?>
              <?php $checkboxId = 'option_' . $attribute->id . '_' . $option->id; ?>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="<?= $checkboxId ?>" name="attributes[<?= $attribute->id ?>][]" value="<?= Html::encode($option->value) ?>">
                <label class="form-check-label" for="<?= $checkboxId ?>">
                  <?= Html::encode($option->value) ?>
                </label>
              </div>     
            <?php endforeach; ?>
          </div>
        <?php endforeach; ?>
        <button type="button" class="btn btn-primary" id="addVariant">genarte</button>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="addVariant">genarte</button>
      </div>
    </div>
  </div>
</div>
