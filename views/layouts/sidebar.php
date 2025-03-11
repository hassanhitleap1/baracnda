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
                <a href="#" class="d-block">Admin</a>
            </div>
        </div>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="<?= Url::to(['admin/index']) ?>"
                        class="nav-link  <?= Yii::$app->controller->id == 'admin' ? 'active' : '' ?>">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p>
                            admin
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= Url::to(['products/index']) ?>"
                        class="nav-link  <?= Yii::$app->controller->id == 'products' ? 'active' : '' ?>">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p>
                            Products
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= Url::to(['categories/index']) ?>"
                        class="nav-link <?= Yii::$app->controller->id == 'categories' ? 'active' : '' ?>">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p>
                            Categories
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= Url::to(['pages/index']) ?>"
                        class="nav-link <?= Yii::$app->controller->id == 'pages' ? 'active' : '' ?>">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p>
                            Pages

                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= Url::to(['slider/index']) ?>"
                        class="nav-link <?= Yii::$app->controller->id == 'slider' ? 'active' : '' ?>">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p>
                            Slider

                        </p>
                    </a>
                </li>
                <?php if (Yii::$app->user->identity->type == User::SUPER_ADMIN): ?>
                    <li class="nav-item">
                        <a href="<?= Url::to(['user/index']) ?>"
                            class="nav-link  <?= Yii::$app->controller->id == 'user' ? 'active' : '' ?>">
                            <i class="nav-icon far fa-calendar-alt"></i>
                            <p>
                                users
                            </p>
                        </a>
                    </li>

                <?php endif; ?>
                <li class="nav-item">
                    <a href="<?= Url::to(['settings/index']) ?>"
                        class="nav-link  <?= Yii::$app->controller->id == 'settings' ? 'active' : '' ?>">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p>
                            Settings

                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <?php echo Html::beginForm(['/site/logout'], 'post')
                            . Html::submitButton('log out', ['class' => 'p-0 logout '])
                            . \yii\helpers\Html::endForm();
                        ?>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>