<?php

namespace app\modules\api\controllers;

use yii\rest\Controller;
use app\models\regions\Regions;
use Yii;

class RegionsController extends Controller
{
    public function actionIndex()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $regions = Regions::find()->all();

        return array_map(function ($region) {
            return [
                'id' => $region->id,
                'name' => $region->name,
            ];
        }, $regions);
    }
}
