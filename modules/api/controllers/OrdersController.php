<?php
namespace app\modules\api\controllers;

use app\models\orderItems\OrderItems;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use app\models\orders\Orders;
use Yii;

class OrdersController extends Controller
{
    public function actionView($id)
    {
        // Use with() for eager loading
        $order = $this->findModel($id);
        return  $this->toArray($order);
    }


     /**
     * Deletes an existing Orders model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionProcess($id)
    {

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $order =$this->findModel($id);
        $order->scenario =Orders::SCENARIO_UPDATE;
        $order->status_order = Orders::STATUS_PROCESSING;
     

        if($order->save(false)){
            return ['success' => true, 'message' => 'Delivery status updated successfully.','data' => $this->toArray($order)];
        }
 
        return ['success' => false, 'message' => 'Failed to update delivery status.'];
  
      
 
    }




    public function actionAddVariant($id){
        
   
        // i am send orderId in requast and variantId but i am not able to get it
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $variantId = Yii::$app->request->post('variantId');
        // $variantPrice = Yii::$app->request->post('price');
        $variantModel = \app\models\variants\Variants::findOne($variantId);
        if (!$variantModel) {
            return ['success' => false, 'message' => 'Variant not found.'];
        }
        
        // $variantQuantity = Yii::$app->request->post('quantity');
        $order = Orders::findOne($id);
        if ($order) {
            $orderItem = new OrderItems();
            $orderItem->order_id = $id;
            $orderItem->variant_id = $variantId;
            $orderItem->quantity = 1; // Default quantity, you can change this as needed
            $orderItem->price =  $variantModel->price; // Assuming you have a relation to get the price from the variant
            $orderItem->cost = $variantModel->cost; // Assuming you have a relation to get the cost from the variant
            $orderItem->product_id = $variantModel->product_id; // Assuming you have a relation to get the product ID from the variant
            if ($orderItem->save(false)) {
                $order->calculateSubTotalFromOrderItems();
                $order->setShippingPrice();
                $order->calculateProfit();
                $order->calculateTotal();
                return ['success' => true, 'message' => 'Variant added to order successfully.'];
            } else {
                return ['success' => false, 'message' => 'Failed to add variant to order.'];
            }
        } else {
            return ['success' => false, 'message' => 'Order not found.'];
        }
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
        if (($model = $order = Orders::find()
            ->with(['orderItems', 'addresses' ,"addresses.region", 'creator',"status" ,"user",'orderItems.product','orderItems.variant'])
            ->where(['orders.id' => $id])
            ->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    protected function toArray($model)
    {
    
        return $model->toArray([], ['orderItems', 'addresses', 'creator','status','user' ,'orderItems.product','orderItems.variant' ]);
    }
    
}