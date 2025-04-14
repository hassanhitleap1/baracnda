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
        $countUsers = Users::find()->count();
        $countOrders = Orders::find()->count();
        $countProducts = Products::find()->count();

        $ordersData = [
            'labels' => ['Last Month'],
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => [Orders::find()->where(['>=', 'created_at', date('Y-m-d', strtotime('-1 month'))])->count()],
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1,
                ],
            ],
        ];

        $productsData = [
            'labels' => ['Last Month'],
            'datasets' => [
                [
                    'label' => 'Products',
                    'data' => [Products::find()->where(['>=', 'created_at', date('Y-m-d', strtotime('-1 month'))])->count()],
                    'backgroundColor' => ['#FF6384'],
                ],
            ],
        ];

        $profitsData = [
            'labels' => ['Last Month'],
            'datasets' => [
                [
                    'label' => 'Profits',
                    'data' => [Orders::find()->where(['>=', 'created_at', date('Y-m-d', strtotime('-1 month'))])->sum('profit')],
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 1,
                ],
            ],
        ];

        $salesData = [
            'labels' => ['Last Month'],
            'datasets' => [
                [
                    'label' => 'Sales',
                    'data' => [Orders::find()->where(['>=', 'created_at', date('Y-m-d', strtotime('-1 month'))])->sum('total')],
                    'backgroundColor' => 'rgba(255, 206, 86, 0.2)',
                    'borderColor' => 'rgba(255, 206, 86, 1)',
                    'borderWidth' => 1,
                ],
            ],
        ];

        $usersData = [
            'labels' => ['Admin', 'Manager', 'Client'],
            'datasets' => [
                [
                    'label' => 'Users',
                    'data' => [
                        Users::find()->count(),
                        Users::find()->count(),
                        Users::find()->count(),
                    ],
                    'backgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56'],
                ],
            ],
        ];

        return $this->render('index', [
            'totalUsers' => $countUsers,
            'totalOrders' => $countOrders,
            'totalProducts' => $countProducts,
            'ordersData' => $ordersData,
            'productsData' => $productsData,
            'profitsData' => $profitsData,
            'salesData' => $salesData,
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