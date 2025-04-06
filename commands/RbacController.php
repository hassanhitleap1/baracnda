<?php 
namespace app\commands;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        if ($auth === null) {
            throw new \Exception('The authManager component is not configured.');
        }

        // Clear existing data
        $auth->removeAll();

        // Create permissions for all controllers and actions
        $permissions = [
            // Orders
            'orders/index' => 'View Orders',
            'orders/view' => 'View Order Details',
            'orders/create' => 'Create Orders',
            'orders/update' => 'Update Orders',
            'orders/delete' => 'Delete Orders',

            // Products
            'products/index' => 'View Products',
            'products/view' => 'View Product Details',
            'products/create' => 'Create Products',
            'products/update' => 'Update Products',
            'products/delete' => 'Delete Products',

            // Categories
            'categories/index' => 'View Categories',
            'categories/create' => 'Create Categories',
            'categories/update' => 'Update Categories',
            'categories/delete' => 'Delete Categories',

            // Users
            'users/index' => 'View Users',
            'users/create' => 'Create Users',
            'users/update' => 'Update Users',
            'users/delete' => 'Delete Users',

            // Settings
            'settings/index' => 'View Settings',

            // Addresses
            'addresses/index' => 'View Addresses',
            'addresses/create' => 'Create Addresses',
            'addresses/update' => 'Update Addresses',
            'addresses/delete' => 'Delete Addresses',

            // Attribute Options
            'attribute-options/index' => 'View Attribute Options',
            'attribute-options/create' => 'Create Attribute Options',
            'attribute-options/update' => 'Update Attribute Options',
            'attribute-options/delete' => 'Delete Attribute Options',

            // Attributes
            'attributes/index' => 'View Attributes',
            'attributes/create' => 'Create Attributes',
            'attributes/update' => 'Update Attributes',
            'attributes/delete' => 'Delete Attributes',

            // Pages
            'pages/index' => 'View Pages',
            'pages/create' => 'Create Pages',
            'pages/update' => 'Update Pages',
            'pages/delete' => 'Delete Pages',

            // Payments
            'payments/index' => 'View Payments',
            'payments/create' => 'Create Payments',
            'payments/update' => 'Update Payments',
            'payments/delete' => 'Delete Payments',

            // Regions
            'regions/index' => 'View Regions',
            'regions/create' => 'Create Regions',
            'regions/update' => 'Update Regions',
            'regions/delete' => 'Delete Regions',

            // Shipping Prices
            'shipping-prices/index' => 'View Shipping Prices',
            'shipping-prices/create' => 'Create Shipping Prices',
            'shipping-prices/update' => 'Update Shipping Prices',
            'shipping-prices/delete' => 'Delete Shipping Prices',

            // Slider
            'slider/index' => 'View Slider',
            'slider/create' => 'Create Slider',
            'slider/update' => 'Update Slider',
            'slider/delete' => 'Delete Slider',

            // Warehouses
            'warehouses/index' => 'View Warehouses',
            'warehouses/create' => 'Create Warehouses',
            'warehouses/update' => 'Update Warehouses',
            'warehouses/delete' => 'Delete Warehouses',
        ];

        foreach ($permissions as $name => $description) {
            if (!$auth->getPermission($name)) {
                $permission = $auth->createPermission($name);
                $permission->description = $description;
                $auth->add($permission);
            }
        }

        // Create roles
        $dataEntry = $auth->createRole('ROLE_DATA_ENTRY');
        $auth->add($dataEntry);

        $manager = $auth->createRole('ROLE_MANAGER');
        $auth->add($manager);

        $seller = $auth->createRole('ROLE_SELLER');
        $auth->add($seller);

        $superAdmin = $auth->createRole('ROLE_SUPER_ADMIN');
        $auth->add($superAdmin);

        // Create viewOwnOrders permission
        if (!$auth->getPermission('viewOwnOrders')) {
            $viewOwnOrders = $auth->createPermission('viewOwnOrders');
            $viewOwnOrders->description = 'View Own Orders';
            $auth->add($viewOwnOrders);
        }

        // Assign specific permissions to ROLE_DATA_ENTRY
        $this->assignPermission($auth, $dataEntry, 'products/create');
        $this->assignPermission($auth, $dataEntry, 'products/update');
        $this->assignPermission($auth, $dataEntry, 'categories/create');
        $this->assignPermission($auth, $dataEntry, 'categories/update');

        // Assign specific permissions to ROLE_SELLER
        $this->assignPermission($auth, $seller, 'products/index');
        $this->assignPermission($auth, $seller, 'products/view');
        $this->assignPermission($auth, $seller, 'orders/index');
        $this->assignPermission($auth, $seller, 'orders/view');

        // Assign specific permissions to ROLE_MANAGER
        $this->assignPermission($auth, $manager, 'viewOwnOrders');
        $this->assignPermission($auth, $manager, 'orders/index');
        $this->assignPermission($auth, $manager, 'orders/view');
        $this->assignPermission($auth, $manager, 'products/index');
        $this->assignPermission($auth, $manager, 'products/view');
        $auth->addChild($manager, $dataEntry); // Inherit ROLE_DATA_ENTRY permissions

        // Assign all permissions to ROLE_MANAGER
        foreach ($auth->getPermissions() as $permission) {
            $this->assignPermission($auth, $manager, $permission->name);
        }

        // Assign all permissions to ROLE_SUPER_ADMIN
        foreach ($auth->getPermissions() as $permission) {
            $this->assignPermission($auth, $superAdmin, $permission->name);
        }

        // Assign roles to users (adjust user IDs as needed)
        $auth->assign($superAdmin, 1); // Assign super admin role to user ID 1
        $auth->assign($manager, 2); // Assign manager role to user ID 2
        $auth->assign($dataEntry, 3); // Assign data entry role to user ID 3
        $auth->assign($seller, 4); // Assign seller role to user ID 4
    }

    /**
     * Assign a permission to a role if not already assigned.
     *
     * @param \yii\rbac\ManagerInterface $auth
     * @param \yii\rbac\Role $role
     * @param string $permissionName
     */
    private function assignPermission($auth, $role, $permissionName)
    {
        $permission = $auth->getPermission($permissionName);
        if ($permission && !$auth->hasChild($role, $permission)) {
            $auth->addChild($role, $permission);
        }
    }
}
