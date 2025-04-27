<?php
namespace app\modules\api\controllers;

use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use app\models\orders\Orders;
use Yii;

class OrdersController extends Controller
{
    public function actionView($id)
    {
        // Use with() for eager loading
        $order = Orders::find()
            ->with(['orderItems', 'addresses' ,"addresses.region", 'creator',"status" ,"user"])
            ->where(['orders.id' => $id])
            ->one();

        if (!$order) {
            throw new NotFoundHttpException('Order not found.');
        }

        return $order->toArray([], ['orderItems', 'addresses', 'creator','status','user']);
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
            return ['success' => true, 'message' => 'Delivery status updated successfully.','data' => $order->toArray([], ['orderItems', 'addresses', 'creator','status','user'])];
        }
 
        return ['success' => false, 'message' => 'Failed to update delivery status.'];
  
      
 
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
        ->with(['orderItems', 'addresses' ,"addresses.region", 'creator',"status" ,"user"])
        ->where(['orders.id' => $id])
        ->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
}