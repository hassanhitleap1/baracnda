<?php

namespace app\controllers;


use app\models\products\Products;
use app\models\users\Users;
use app\models\orders\Orders;
use app\models\orders\OrdersSearch;
use Carbon\Carbon;
use yii\filters\VerbFilter;
use Yii;
use yii\helpers\ArrayHelper;

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
        $countOrders = Orders::find()->byAuthedUser()->count();
        $countProducts = Products::find()->count();
        $totalProfits = Orders::find()->byAuthedUser()->paid()->completed()->sum('profit')??0;
        $startDate = Carbon::now()->firstOfMonth();
        $endDate = Carbon::parse($startDate)->endOfMonth();
        $dates = [];
        $dataOrdersNumber=[];
        $dataOrdersProfit = [];
        $dataTotalSales = [];

        $ordersLastMonth = Orders::find()->select([
            'DATE(created_at) as day',
            'COUNT(*) as number_order',
            "SUM(profit) as total_profit",
            "SUM(total) as total_sales",
        ])
        ->thisMonth()
        ->byAuthedUser()
        ->completed()
            ->groupBy('DATE(created_at)')
            ->orderBy('day')
            ->asArray()
            ->all();

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
            $order = array_values(array_filter($ordersLastMonth, function ($item) use ($date) {
                return $item['day'] === $date->format('Y-m-d');
            }));
           
            if(count($order)){
                $dataOrdersNumber[]=(int)$order[0]['number_order'];
                $dataOrdersProfit[]=(float)$order[0]['total_profit'];
                $dataTotalSales[]=(float)$order[0]['total_sales'];
            }else{
                $dataOrdersNumber[] = 0;
                $dataOrdersProfit[] = 0;
                $dataTotalSales[] = 0;
            }
         

        }

       
           
           
        return $this->render('index', [
            'totalUsers' => $countUsers,
            'totalOrders' => $countOrders,
            'totalProducts' => $countProducts,
            'totalProfits' => $totalProfits,
            'dates'=>  $dates,
            'dataOrdersNumber'=>$dataOrdersNumber,
            'dataOrdersProfit'=>$dataOrdersProfit,
            'dataTotalSales'=>$dataTotalSales
         
        ]);
    }


    public function actionReports()
    {
     
        $searchModel = new OrdersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    
        $totalProfits = Orders::find()->where(['order_status' => 'completed'])->where(['payment_status' => 'paid'])->sum('profit')??0;
        $countOrders = Orders::find()->where(['order_status' => 'completed'])->where(['payment_status' => 'paid'])->count();


        $filteredProfits = Orders::find()
        ->andWhere(['payment_status' => 'paid']);

        $filteredCountOrders = Orders::find();

        if (Yii::$app->request->get('date_range')) {
            list($start_date, $end_date) = explode(' - ', Yii::$app->request->get('date_range'));
            $filteredProfits = $filteredProfits->where(['>=', 'created_at', $start_date])->andWhere(['<=', 'created_at', $end_date]);
            $filteredCountOrders = $filteredCountOrders->where(['>=', 'created_at', $start_date])->andWhere(['<=', 'created_at', $end_date]);

        }


        $filteredProfits = $filteredProfits ->sum('profit')??0;
        $filteredCountOrders= $filteredCountOrders ->count();
       
    
       
        return $this->render('reports', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'totalProfits' => $totalProfits,
            'countOrders' => $countOrders,
            'filteredProfits' => $filteredProfits,
            'filteredCountOrders' => $filteredCountOrders,
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