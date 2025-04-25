<?php
namespace app\modules\api\controllers;

use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use app\models\orders\Orders;

class OrdersController extends Controller
{
    public function actionView($id)
    {
        // Use with() for eager loading
        $order = Orders::find()
            ->with(['orderItems', 'addresses', 'creator'])
            ->where(['orders.id' => $id])
            ->one();

        if (!$order) {
            throw new NotFoundHttpException('Order not found.');
        }

        return $order->toArray([], ['orderItems', 'address', 'creator']);
    }
}