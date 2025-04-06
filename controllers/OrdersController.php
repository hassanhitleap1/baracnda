<?php

namespace app\controllers;

use app\models\orders\Orders;
use app\models\orders\OrdersSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * OrdersController implements the CRUD actions for Orders model.
 */
class OrdersController extends BaseController
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'denyCallback' => function ($rule, $action) {
                    return $this->redirect(['site/index']); // Redirect to home page
                },
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // Allow authenticated users
                    ],
                    [
                        'allow' => false, // Deny all other users
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Orders models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrdersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Orders model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = Orders::findOne($id);

        if (!$model || (!Yii::$app->user->can('viewAllOrders') && $model->creator_id !== Yii::$app->user->id)) {
            throw new \yii\web\ForbiddenHttpException('You are not allowed to view this order.');
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Orders model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Orders(['scenario' => Orders::SCENARIO_CREATE]);

        if ($this->request->isPost) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($model->load($this->request->post()) && $model->validate()) {
                    if (!$model->setAddress() || !$model->setUser() || !$model->setCreator()) {
                        throw new \Exception('Failed to set related data.');
                    }

                    if (!$model->save()) {
                        throw new \Exception('Failed to save order.');
                    }

                    // Load and save OrderItems
                    if ($items = Yii::$app->request->post('Orders')['OrderItems'] ?? []) {
                        foreach ($items as $item) {
                            if (!$model->addItem((object)$item)) {
                                throw new \Exception('Failed to save order items.');
                            }
                        }
                    }

                    $model->setShippingPrice();
                    $model->calculateTotals();

                    if (!$model->save()) {
                        throw new \Exception('Failed to update order totals.');
                    }

                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    Yii::$app->session->setFlash('error', 'Validation failed.');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    /**
     * Updates an existing Orders model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = Orders::SCENARIO_UPDATE;

        if ($this->request->isPost) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($model->load($this->request->post()) && $model->validate()) {
                    if (!$model->setAddress() || !$model->setUser()) {
                        throw new \Exception('Failed to update related data.');
                    }

                    $model->setShippingPrice();
                    $model->calculateTotals();

                    if (!$model->save()) {
                        throw new \Exception('Failed to update order.');
                    }

                    if ($items = Yii::$app->request->post('Orders')['OrderItems'] ?? []) {
                        foreach ($items as $item) {
                            if (!$model->addItem((object)$item)) {
                                throw new \Exception('Failed to save order items.');
                            }
                        }
                    }

                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    Yii::$app->session->setFlash('error', 'Validation failed.');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', ['model' => $model]);
    }

    /**
     * Deletes an existing Orders model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Orders model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Orders the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Orders::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Print selected orders.
     * @return void
     */
    public function actionPrintSelected()
    {
        $ids = Yii::$app->request->get('ids');
        $ids = json_decode($ids, true);

        if (empty($ids)) {
            Yii::$app->session->setFlash('error', 'No orders selected.');
            return $this->redirect(['index']);
        }

        $orders = Orders::find()->where(['id' => $ids])->all();

        // Render a view or generate a PDF for the selected orders
        return $this->render('print', ['orders' => $orders]);
    }

    /**
     * Change the status of selected orders.
     * @return \yii\web\Response
     */
    public function actionChangeStatus()
    {
        $ids = Yii::$app->request->post('ids');
        $status = Yii::$app->request->post('status');

        if (empty($ids) || !$status) {
            return $this->asJson(['success' => false, 'message' => 'Invalid input.']);
        }

        $orders = Orders::find()->where(['id' => $ids])->all();
        foreach ($orders as $order) {
            $order->status_id = $status;
            if (!$order->save()) {
                return $this->asJson(['success' => false, 'message' => 'Failed to update some orders.']);
            }
        }

        return $this->asJson(['success' => true]);
    }

    public function actionCalculateTotals()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $shippingId = Yii::$app->request->post('shipping_id');
        $orderItems = Yii::$app->request->post('order_items');

        // Parse order items
        parse_str($orderItems, $parsedItems);

        $subtotal = 0;
        foreach ($parsedItems['OrderItems'] as $item) {
            $subtotal += $item['quantity'] * $item['price'];
        }

        $shippingPrice = 0;
        if ($shippingId) {
            $shipping = \app\models\shippingPrices\ShippingPrices::findOne(['shipping_id' => $shippingId]);
            $shippingPrice = $shipping ? $shipping->price : 0;
        }

        $total = $subtotal + $shippingPrice;

        return [
            'success' => true,
            'subtotal' => $subtotal,
            'shipping_price' => $shippingPrice,
            'total' => $total,
        ];
    }
}
