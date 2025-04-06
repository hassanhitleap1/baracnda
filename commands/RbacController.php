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
            'orders/index' => 'View Orders',
            'orders/view' => 'View Order Details',
            'orders/create' => 'Create Orders',
            'orders/update' => 'Update Orders',
            'orders/delete' => 'Delete Orders',
            'products/index' => 'View Products',
            'products/view' => 'View Product Details',
            'products/create' => 'Create Products',
            'products/update' => 'Update Products',
            'products/delete' => 'Delete Products',
            // Add more permissions for other controllers and actions
        ];

        foreach ($permissions as $name => $description) {
            $permission = $auth->createPermission($name);
            $permission->description = $description;
            $auth->add($permission);
        }

        // Create roles
        $manager = $auth->createRole('ROLE_MANAGER');
        $auth->add($manager);

        $admin = $auth->createRole('ROLE_ADMIN');
        $auth->add($admin);

        // Assign all permissions to ROLE_ADMIN
        foreach ($auth->getPermissions() as $permission) {
            $auth->addChild($admin, $permission);
        }

        // Assign specific permissions to ROLE_MANAGER
        $auth->addChild($manager, $auth->getPermission('orders/index'));
        $auth->addChild($manager, $auth->getPermission('orders/view'));
        $auth->addChild($manager, $auth->getPermission('products/index'));
        $auth->addChild($manager, $auth->getPermission('products/view'));

        // Assign roles to users (adjust user IDs as needed)
        $auth->assign($admin, 1); // Assign admin role to user ID 1
        $auth->assign($manager, 2); // Assign manager role to user ID 2
    }
}
