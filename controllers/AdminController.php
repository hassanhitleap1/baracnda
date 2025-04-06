<?php

namespace app\controllers;

use app\models\categories\Categories;
use app\models\contactus\Contactus;
use app\models\products\Products;
use app\models\users\Users;
use app\models\orders\Orders;
use yii\filters\VerbFilter;
use Yii;


/**
 * CategoriesController implements the CRUD actions for Categories model.
 */
class AdminController extends BaseController
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Categories models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $countProduct = Products::find()->count();
        $countCategories = Categories::find()->count();
        $countRequastUnreaded = Contactus::find()->where(['readed' => 0])->count();
        $countUsers = Users::find()->count();
        $countOrders = Orders::find()->count();

        // Example data for charts (replace with actual queries)
        $ordersData = [
            'labels' => ['January', 'February', 'March', 'April'],
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => [10, 20, 30, 40],
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1,
                ],
            ],
        ];

        $productsData = [
            'labels' => ['Electronics', 'Clothing', 'Home Appliances'],
            'datasets' => [
                [
                    'label' => 'Products',
                    'data' => [50, 30, 20],
                    'backgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56'],
                ],
            ],
        ];

        $usersData = [
            'labels' => ['Admin', 'Manager', 'Client'],
            'datasets' => [
                [
                    'label' => 'Users',
                    'data' => [
                        Users::find()->where(['role_id' => Users::ROLE_SUPER_ADMIN])->count(),
                        Users::find()->where(['role_id' => Users::ROLE_MANAGER])->count(),
                        Users::find()->where(['role_id' => Users::ROLE_CLIENT])->count(),
                    ],
                    'backgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56'],
                ],
            ],
        ];

        return $this->render('index', [
            'countProduct' => $countProduct,
            'countCategories' => $countCategories,
            'countRequastUnreaded' => $countRequastUnreaded,
            'totalUsers' => $countUsers,
            'totalOrders' => $countOrders,
            'totalProducts' => $countProduct,
            'ordersData' => $ordersData,
            'productsData' => $productsData,
            'usersData' => $usersData,
        ]);
    }

    public function actionPermissions()
    {
        $auth = Yii::$app->authManager;

        $roles = $auth->getRoles();
        $permissions = $auth->getPermissions();

        $rolePermissions = [];
        foreach ($roles as $role) {
            $rolePermissions[$role->name] = array_keys($auth->getPermissionsByRole($role->name));
        }

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post('rolePermissions', []);
            foreach ($roles as $role) {
                $auth->removeChildren($role);
                if (isset($post[$role->name])) {
                    foreach ($post[$role->name] as $permissionName) {
                        $permission = $auth->getPermission($permissionName);
                        if ($permission) {
                            $auth->addChild($role, $permission);
                        }
                    }
                }
            }
            Yii::$app->session->setFlash('success', 'Permissions updated successfully.');
            return $this->refresh();
        }

        return $this->render('permissions', [
            'auth' => $auth,
            'roles' => $roles,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions,
        ]);
    }
}