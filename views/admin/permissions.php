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

<div class="admin-permissions">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['id' => 'permissions-form']); ?>

    <h2>Roles</h2>
    <ul>
        <?php foreach ($roles as $role): ?>
            <li>
                <strong><?= Html::encode($role->name === 'ROLE_SUPER_ADMIN' ? 'Super Admin' : $role->name) ?>:</strong> <?= Html::encode($role->description) ?>
                <ul>
                    <?php foreach ($permissions as $permission): ?>
                        <li>
                            <?= Html::checkbox(
                                "rolePermissions[{$role->name}][]",
                                in_array($permission->name, $rolePermissions[$role->name] ?? []),
                                ['value' => $permission->name]
                            ) ?>
                            <?= Html::encode($permission->name) ?>: <?= Html::encode($permission->description) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>
        <?php endforeach; ?>
    </ul>

    <div class="form-group">
        <?= Html::submitButton('Save Changes', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
