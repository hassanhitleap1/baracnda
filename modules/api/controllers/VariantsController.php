<?php

namespace app\modules\api\controllers;

use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use app\models\orders\Orders;
use app\models\variants\Variants;
use Yii;

class VariantsController extends Controller
{


    public function actionSearch()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $query = Yii::$app->request->get('query');
        $variants = Variants::find()
            ->where(['like', 'name', $query])
            ->limit(10)
            ->all();

        return array_map(function ($variant) {
            return [
                'id' => $variant->id,
                'name' => $variant->name,
                'image' => $variant->product->imageUrl,
                'price' => $variant->price,
                'cost' => $variant->cost,
                'product_id' => $variant->product_id,
            ];
        }, $variants);
    }
}
