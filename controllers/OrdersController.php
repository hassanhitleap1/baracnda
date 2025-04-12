<?php

namespace app\controllers;

use app\models\orderItems\OrderItems;
use app\models\orders\Orders;
use app\models\orders\OrdersSearch;
use app\models\variants\Variants;
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

                    $items = Yii::$app->request->post('Orders')['OrderItems'] ?? [];
                    if(empty($items)){
                        throw new \Exception('Order items cannot be empty.');
                    }
                    $model->calculateSubTotal($items); 
                    $model->setShippingPrice();
                    $model->calculateTotal();



                    if (!$model->setAddress() || !$model->setUser() || !$model->setCreator()) {
                        throw new \Exception('Failed to set related data.');
                    }
                    $model->status_order=Orders::STATUS_RESERVED;

                    if (!$model->save()) {
                        throw new \Exception('Failed to save order.');
                    }

                    // Load and save OrderItems
                    foreach ($items as $item) {
                        if (!$model->addItem((object)$item)) {
                            throw new \Exception('Failed to save order items.');
                        }
                    }

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
                    $items = Yii::$app->request->post('Orders')['OrderItems'] ?? [];
                    if(empty($items)){
                        throw new \Exception('Order items cannot be empty.');
                    }
                    $model->calculateSubTotal($items); 
                    $model->setShippingPrice();           
                    $model->calculateTotal();
                    
                    if (!$model->setAddress()) {
                        throw new \Exception('Failed to update related data.');
                    }

                    if (!$model->save()) {
                        throw new \Exception('Failed to update order.');
                    }

                    foreach ($items as $item) {
                        OrderItems::deleteAll(['order_id' => $id]);
                        if (!$model->addItem((object)$item)) {
                            throw new \Exception('Failed to save order items.');
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
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');
        $statusId = Yii::$app->request->post('status_id');

        $order = Orders::findOne($id);
        if (!$order) {
            return ['success' => false, 'message' => 'Order not found.'];
        }

        $order->status_id = $statusId;
        if ($order->save()) {
            return ['success' => true];
        }

        return ['success' => false, 'message' => 'Failed to update status.'];
    }

    public function actionChangeDeliveryStatus()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');
        $deliveryStatus = Yii::$app->request->post('delivery_status');

        $order = Orders::findOne($id);
        if (!$order) {
            return ['success' => false, 'message' => 'Order not found.'];
        }

        $order->delivery_status = $deliveryStatus;
        if ($deliveryStatus === 'delivered') {
            $order->status_order = Orders::STATUS_COMPLETED;
        } elseif ($deliveryStatus === 'shipped') {
            $order->status_order = Orders::STATUS_CANCELED;
        }

        if ($order->save()) {
            return ['success' => true];
        }

        return ['success' => false, 'message' => 'Failed to update delivery status.'];
    }

    public function actionCalculateTotals()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $shippingId = Yii::$app->request->post('shipping_id');
        $orderItems = Yii::$app->request->post('order_items');

        // Parse order items
        parse_str($orderItems, $parsedItems);

        $subtotal = 0;
        foreach ($parsedItems['Orders']['OrderItems'] as $item) {
            $subtotal += $item['variant_quantity'] * $item['variant_price'];
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

    public function actionGetVariantDetails()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $variantId = Yii::$app->request->get('variant_id');
        $productId = Yii::$app->request->get('product_id');

        $variant = \app\models\variants\Variants::findOne(['id' => $variantId, 'product_id' => $productId]);

        if ($variant) {
            return [
                'success' => true,
                'data' => [
                    'price' => $variant->price,
                    'quantity' => $variant->quantity,
                ],
            ];
        }

        return ['success' => false];
    }

    public function actionGetShippingPrice()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $shippingId = Yii::$app->request->get('shipping_id');
        $regionId = Yii::$app->request->get('region_id');

        $shippingPrice = \app\models\shippingPrices\ShippingPrices::findOne(['shipping_id' => $shippingId, 'region_id' => $regionId]);

        if ($shippingPrice) {
            return [
                'success' => true,
                'data' => [
                    'price' => $shippingPrice->price,
                ],
            ];
        }

        return ['success' => false];
    }

    public function actionDeleteItem($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $item = OrderItems::findOne($id);
        if (!$item) {
            return ['success' => false, 'message' => 'Item not found.'];
        }

        $order = $item->orders;
        if (!in_array($order->status_order, [Orders::STATUS_PROCESSING, Orders::STATUS_RESERVED])) {
            return ['success' => false, 'message' => 'Cannot delete items for this order status.'];
        }

        if ($item->delete()) {
           $order->calculateSubTotalFromOrderItems();
           $order->setShippingPrice();
           $order->calculateProfit();
           $order->calculateTotal();

            return ['success' => true];
        }

        return ['success' => false, 'message' => 'Failed to delete item.'];
    }

    public function actionRecalculateTotals()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $orderId = Yii::$app->request->post('order_id');
        $order = Orders::findOne($orderId);

        if (!$order) {
            return ['success' => false, 'message' => 'Order not found.'];
        }

        $order->sub_total = 0;
        foreach ($order->orderItems as $item) {
            $order->sub_total += $item->quantity * $item->price;
        }

        $order->calculateTotal();
        if ($order->save()) {
            return [
                'success' => true,
                'subtotal' => $order->sub_total,
                'total' => $order->total,
            ];
        }

        return ['success' => false, 'message' => 'Failed to recalculate totals.'];
    }

    public function actionAddVariantToOrder($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $variantId = Yii::$app->request->post('variant_id');
        $quantity = Yii::$app->request->post('variant_quantity');
        $variant = Variants::findOne($variantId);
        if (!$variant) {
            return ['success' => false, 'message' => 'Variant not found.'];
        }
        $order = Orders::findOne($id);
        
        if (!$order) {
            return ['success' => false, 'message' => 'Order not found.'];
        }
      

        $orderItem = new OrderItems();
        $orderItem->order_id = $order->id;
        $orderItem->variant_id = $variant->id;
        $orderItem->quantity = $quantity;
        $orderItem->price = $variant->price;
        $orderItem->product_id = $variant->product_id;
        $orderItem->cost = $variant->cost;
       
        if ($orderItem->save()) {
            $order->calculateSubTotalFromOrderItems();
            $order->setShippingPrice();
            $order->calculateProfit();
            $order->calculateTotal();
            return ['success' => true, 'data' => ['order' => $order->toArray(), 'orderItem' => $orderItem->toArray(),'product' => $orderItem->product->toArray()]]; 
        }

        return ['success' => false, 'message' => 'Failed to add variant to order.'];
    }
}
