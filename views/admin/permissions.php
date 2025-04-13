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

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin(['id' => 'permissions-form']); ?>
            <div class="row">
                <?php foreach ($roles as $role): ?>
                    <div class="col-3">
                        <li>
                            <strong><?= Html::encode($role->name) ?>:</strong> <?= Html::encode($role->description) ?>
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
                    </div>
                
                <?php endforeach; ?>
            </div>  
            <div class="form-group">
                <?= Html::submitButton('Save Changes', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <ul>

    </ul>


</div>