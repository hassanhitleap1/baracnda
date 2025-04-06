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
                        <a href="<?= Url::to(['site/index']) ?>"
                            class="nav-link <?= Yii::$app->controller->id == 'site' ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <!-- Orders -->
                    <?php if (Yii::$app->user->can('viewAllOrders') || Yii::$app->user->can('viewOwnOrders')): ?>
                        <li class="nav-item">
                            <a href="<?= Url::to(['orders/index']) ?>"
                                class="nav-link <?= Yii::$app->controller->id == 'orders' ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-shopping-cart"></i>
                                <p>Orders</p>
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- Products -->
                    <?php if (Yii::$app->user->can('manageProducts')): ?>
                        <li class="nav-item">
                            <a href="<?= Url::to(['products/index']) ?>"
                                class="nav-link <?= Yii::$app->controller->id == 'products' ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-box"></i>
                                <p>Products</p>
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- Categories -->
                    <?php if (Yii::$app->user->can('manageProducts')): ?>
                        <li class="nav-item">
                            <a href="<?= Url::to(['categories/index']) ?>"
                                class="nav-link <?= Yii::$app->controller->id == 'categories' ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-tags"></i>
                                <p>Categories</p>
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- Users (Admin Only) -->
                    <?php if (Yii::$app->user->identity->role_id == User::SUPER_ADMIN): ?>
                        <li class="nav-item">
                            <a href="<?= Url::to(['users/index']) ?>"
                                class="nav-link <?= Yii::$app->controller->id == 'users' ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Users</p>
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- Settings -->
                    <?php if (Yii::$app->user->identity->role_id == User::SUPER_ADMIN): ?>
                        <li class="nav-item">
                            <a href="<?= Url::to(['settings/index']) ?>"
                                class="nav-link <?= Yii::$app->controller->id == 'settings' ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-cogs"></i>
                                <p>Settings</p>
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
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>
<?php endif; ?>