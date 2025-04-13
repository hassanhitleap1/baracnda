<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var yii\rbac\ManagerInterface $auth */
/** @var array $roles */
/** @var array $permissions */
/** @var array $rolePermissions */

$this->title = 'Roles and Permissions';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="roles-permissions">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['id' => 'permissions-form']); ?>

    <div class="row">
        <?php foreach ($roles as $role): ?>
            <div class="col-md-4">
                <h4><?= Html::encode($role->name) ?></h4>
                <ul>
                    <?php foreach ($permissions as $permission): ?>
                        <li>
                            <?= Html::checkbox(
                                "rolePermissions[{$role->name}][]",
                                in_array($permission->name, $rolePermissions[$role->name] ?? []),
                                ['value' => $permission->name]
                            ) ?>
                            <?= Html::encode($permission->name) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save Changes', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
