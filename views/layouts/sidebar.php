<?php
use app\models\User;
use yii\helpers\Url;
use yii\bootstrap5\Html;

?>
<style>
    .nav-link form {
        display: inline-block;
    }

    .logout {
        background: none;
        border: 0;
        color: #c2c7d0;
    }
</style>
<?php if (Yii::$app->user->isGuest): ?>
    <?php Yii::$app->response->redirect(['site/index'])->send(); ?>
<?php else: ?>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="<?= Url::to(['site/index']) ?>" class="brand-link">
            <?= Html::img(Yii::getAlias('@web') . '/logo-white.png', ['alt' => 'Neutron sys logo white"', 'class' => 'px-3']) ?>
        </a>
        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="<?= Yii::getAlias('@web') . '/AdminLTE/dist/img/user2-160x160.jpg' ?>"
                        class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block"><?= Yii::$app->user->identity->full_name ?? 'Guest' ?></a>
                </div>
            </div>
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a href="<?= Url::to(['admin/index']) ?>"
                            class="nav-link <?= Yii::$app->controller->id == 'site' ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= Url::to(['admin/reports']) ?>"
                            class="nav-link <?= Yii::$app->controller->id == 'admin' ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Reports</p>
                        </a>
                    </li>

                    <?php if (Yii::$app->user->can('admin/permissions')): ?>
                    <li class="nav-item">
                        <a href="<?= Url::to(['admin/permissions']) ?>"
                            class="nav-link <?= Yii::$app->controller->id == 'site' ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Permissions</p>
                        </a>
                    </li>
                    <?php endif; ?>
                    <!-- Orders -->
                    <?php if (Yii::$app->user->can('orders/index')): ?>
                        <li class="nav-item">
                            <a href="<?= Url::to(['orders/index']) ?>"
                                class="nav-link <?= Yii::$app->controller->id == 'orders' ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-shopping-cart"></i>
                                <p>Orders</p>
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- Products -->
                    <?php if (Yii::$app->user->can('products/index')): ?>
                        <li class="nav-item">
                            <a href="<?= Url::to(['products/index']) ?>"
                                class="nav-link <?= Yii::$app->controller->id == 'products' ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-box"></i>
                                <p>Products</p>
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- Categories -->
                    <?php if (Yii::$app->user->can('categories/index')): ?>
                        <li class="nav-item">
                            <a href="<?= Url::to(['categories/index']) ?>"
                                class="nav-link <?= Yii::$app->controller->id == 'categories' ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-tags"></i>
                                <p>Categories</p>
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- Users -->
                    <?php if (Yii::$app->user->can('viewUsers')): ?>
                        <li class="nav-item">
                            <a href="<?= Url::to(['users/index']) ?>"
                                class="nav-link <?= Yii::$app->controller->id == 'users' ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Users</p>
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- Settings -->
                    <?php if (Yii::$app->user->can('settings/index')): ?>
                        <li class="nav-item">
                            <a href="<?= Url::to(['settings/index']) ?>"
                                class="nav-link <?= Yii::$app->controller->id == 'settings' ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-cogs"></i>
                                <p>Settings</p>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (Yii::$app->user->can('addresses/index')): ?>
                        <li class="nav-item">
                            <a href="<?= Url::to(['addresses/index']) ?>"
                                class="nav-link <?= Yii::$app->controller->id == 'addresses' ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-map-marker-alt"></i>
                                <p>Addresses</p>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (Yii::$app->user->can('attribute-options/index')): ?>
                        <li class="nav-item">
                            <a href="<?= Url::to(['attribute-options/index']) ?>"
                                class="nav-link <?= Yii::$app->controller->id == 'attribute-options' ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-list-alt"></i>
                                <p>Attribute Options</p>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (Yii::$app->user->can('attributes/index')): ?>
                        <li class="nav-item">
                            <a href="<?= Url::to(['attributes/index']) ?>"
                                class="nav-link <?= Yii::$app->controller->id == 'attributes' ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-tags"></i>
                                <p>Attributes</p>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (Yii::$app->user->can('pages/index')): ?>
                        <li class="nav-item">
                            <a href="<?= Url::to(['pages/index']) ?>"
                                class="nav-link <?= Yii::$app->controller->id == 'pages' ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>Pages</p>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (Yii::$app->user->can('payments/index')): ?>
                        <li class="nav-item">
                            <a href="<?= Url::to(['payments/index']) ?>"
                                class="nav-link <?= Yii::$app->controller->id == 'payments' ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-credit-card"></i>
                                <p>Payments</p>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (Yii::$app->user->can('regions/index')): ?>
                        <li class="nav-item">
                            <a href="<?= Url::to(['regions/index']) ?>"
                                class="nav-link <?= Yii::$app->controller->id == 'regions' ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-globe"></i>
                                <p>Regions</p>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (Yii::$app->user->can('shipping-prices/index')): ?>
                        <li class="nav-item">
                            <a href="<?= Url::to(['shipping-prices/index']) ?>"
                                class="nav-link <?= Yii::$app->controller->id == 'shipping-prices' ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-shipping-fast"></i>
                                <p>Shipping Prices</p>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (Yii::$app->user->can('slider/index')): ?>
                        <li class="nav-item">
                            <a href="<?= Url::to(['slider/index']) ?>"
                                class="nav-link <?= Yii::$app->controller->id == 'slider' ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-images"></i>
                                <p>Slider</p>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (Yii::$app->user->can('warehouses/index')): ?>
                        <li class="nav-item">
                            <a href="<?= Url::to(['warehouses/index']) ?>"
                                class="nav-link <?= Yii::$app->controller->id == 'warehouses' ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-warehouse"></i>
                                <p>Warehouses</p>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (Yii::$app->user->can('roles/index')): ?>
                        <li class="nav-item">
                            <a href="<?= Url::to(['roles/index']) ?>"
                                class="nav-link <?= Yii::$app->controller->id == 'roles' ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-user-shield"></i>
                                <p>Roles</p>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (Yii::$app->user->can('roles/permissions')): ?>
                        <li class="nav-item">
                            <a href="<?= Url::to(['roles/permissions']) ?>"
                                class="nav-link <?= Yii::$app->controller->action->id == 'permissions' ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-key"></i>
                                <p>Permissions</p>
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- Logout -->
                    <li class="nav-item">
                        <a class="nav-link">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <?php echo Html::beginForm(['/site/logout'], 'post')
                                . Html::submitButton('Log Out', ['class' => 'p-0 logout'])
                                . Html::endForm();
                            ?>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>
<?php endif; ?>