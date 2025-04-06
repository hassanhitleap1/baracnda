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

        // Create permissions
        $manageProducts = $auth->createPermission('manageProducts');
        $manageProducts->description = 'Manage Products';
        $auth->add($manageProducts);

        $viewOwnOrders = $auth->createPermission('viewOwnOrders');
        $viewOwnOrders->description = 'View Own Orders';
        $auth->add($viewOwnOrders);

        $manageOwnOrders = $auth->createPermission('manageOwnOrders');
        $manageOwnOrders->description = 'Manage Own Orders';
        $auth->add($manageOwnOrders);

        $viewAllOrders = $auth->createPermission('viewAllOrders');
        $viewAllOrders->description = 'View All Orders';
        $auth->add($viewAllOrders);

        // Create roles
        $manager = $auth->createRole('ROLE_MANAGER');
        $auth->add($manager);
        $auth->addChild($manager, $manageProducts);
        $auth->addChild($manager, $viewOwnOrders);
        $auth->addChild($manager, $manageOwnOrders);

        $admin = $auth->createRole('ROLE_ADMIN');
        $auth->add($admin);
        $auth->addChild($admin, $manager); // Admin inherits Manager permissions
        $auth->addChild($admin, $viewAllOrders);

        // Assign roles to users (adjust user IDs as needed)
        $auth->assign($admin, 1); // Assign admin role to user ID 1
        $auth->assign($manager, 2); // Assign manager role to user ID 2
    }
}
