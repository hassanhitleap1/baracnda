<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

/**
 * CategoriesController implements the CRUD actions for Categories model.
 */
class BaseController extends Controller
{
    public function beforeAction($action)
    {
        $this->layout = "admin";
        // if (Yii::$app->user->isGuest) {
        //     return $this->redirect(['site/login']);
        // }
        return $action;
    }


    protected function getErrorMessages($model)
    {
        $errors = [];
        foreach ($model->getErrors() as $attribute => $messages) {
            $errors[] = implode('<br>', $messages);
        }
        return implode('<br>', $errors);
    }
}