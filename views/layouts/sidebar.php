<?php
use yii\helpers\Url;
use yii\bootstrap5\Html;

/**
 * Sidebar navigation, grouped by area of work.
 * Each item: label, icon, route, permission (null = everyone signed in),
 * and an `active` callback receiving controller id and action id.
 */
?>
<?php if (Yii::$app->user->isGuest): ?>
    <?php Yii::$app->response->redirect(['site/index'])->send(); ?>
<?php else: ?>
    <?php
    $controllerId = Yii::$app->controller->id;
    $actionId = Yii::$app->controller->action->id;
    $user = Yii::$app->user;

    $byController = fn(string $id) => fn($c, $a) => $c === $id;

    $groups = [
        'Overview' => [
            ['Dashboard', 'fa-tachometer-alt', 'admin/index', null, fn($c, $a) => $c === 'admin' && $a === 'index'],
            ['Reports', 'fa-chart-bar', 'admin/reports', null, fn($c, $a) => $a === 'reports'],
        ],
        'Sales' => [
            ['Orders', 'fa-shopping-cart', 'orders/index', 'orders/index', $byController('orders')],
            ['Payments', 'fa-credit-card', 'payments/index', 'payments/index', $byController('payments')],
            ['Addresses', 'fa-map-marker-alt', 'addresses/index', 'addresses/index', $byController('addresses')],
        ],
        'Catalog' => [
            ['Products', 'fa-box', 'products/index', 'products/index', $byController('products')],
            ['Categories', 'fa-folder', 'categories/index', 'categories/index', $byController('categories')],
            ['Attributes', 'fa-tags', 'attributes/index', 'attributes/index', $byController('attributes')],
            ['Attribute Options', 'fa-list-alt', 'attribute-options/index', 'attribute-options/index', $byController('attribute-options')],
        ],
        'Logistics' => [
            ['Warehouses', 'fa-warehouse', 'warehouses/index', 'warehouses/index', $byController('warehouses')],
            ['Regions', 'fa-globe', 'regions/index', 'regions/index', $byController('regions')],
            ['Shipping Prices', 'fa-shipping-fast', 'shipping-prices/index', 'shipping-prices/index', $byController('shipping-prices')],
        ],
        'Storefront' => [
            ['Pages', 'fa-file-alt', 'pages/index', 'pages/index', $byController('pages')],
            ['Slider', 'fa-images', 'slider/index', 'slider/index', $byController('slider')],
        ],
        'Access' => [
            ['Users', 'fa-users', 'users/index', 'viewUsers', $byController('users')],
            ['Roles', 'fa-user-shield', 'roles/index', 'roles/index', fn($c, $a) => $c === 'roles' && $a !== 'permissions'],
            ['Role permissions', 'fa-key', 'roles/permissions', 'roles/permissions', fn($c, $a) => $c === 'roles' && $a === 'permissions'],
            ['Permissions', 'fa-lock', 'admin/permissions', 'admin/permissions', fn($c, $a) => $c === 'admin' && $a === 'permissions'],
        ],
        'System' => [
            ['Settings', 'fa-cog', 'settings/index', 'settings/index', $byController('settings')],
        ],
    ];

    $displayName = $user->identity->full_name ?? $user->identity->username ?? '';
    $initial = mb_strtoupper(mb_substr(trim($displayName) ?: '?', 0, 1));
    ?>
    <aside class="main-sidebar sidebar-dark-primary">
        <a href="<?= Url::to(['admin/index']) ?>" class="brand-link">
            <?= Html::img(Yii::getAlias('@web') . '/logo-white.png', ['alt' => Yii::$app->name, 'class' => 'px-3']) ?>
        </a>

        <div class="sidebar">
            <div class="user-panel mt-3 pb-3 mb-2 d-flex">
                <span class="avatar" aria-hidden="true"><?= Html::encode($initial) ?></span>
                <div class="info">
                    <span class="d-block text-white"><?= Html::encode($displayName) ?></span>
                </div>
            </div>

            <nav class="mt-1" aria-label="Main">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <?php foreach ($groups as $groupLabel => $items): ?>
                        <?php
                        $visible = array_filter($items, fn($item) => $item[3] === null || $user->can($item[3]));
                        if (!$visible) {
                            continue;
                        }
                        ?>
                        <li class="nav-header"><?= Html::encode(Yii::t('app', $groupLabel)) ?></li>
                        <?php foreach ($visible as [$label, $icon, $route, $permission, $isActive]): ?>
                            <li class="nav-item">
                                <a href="<?= Url::to([$route]) ?>"
                                    class="nav-link<?= $isActive($controllerId, $actionId) ? ' active' : '' ?>">
                                    <i class="nav-icon fas <?= $icon ?>"></i>
                                    <p><?= Html::encode(Yii::t('app', $label)) ?></p>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php endforeach; ?>

                    <li class="nav-item mt-3">
                        <div class="nav-link">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <?= Html::beginForm(['/site/logout'], 'post')
                                . Html::submitButton(Yii::t('app', 'Log out'), ['class' => 'logout'])
                                . Html::endForm() ?>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>
<?php endif; ?>
