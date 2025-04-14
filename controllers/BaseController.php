<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;

/**
 * CategoriesController implements the CRUD actions for Categories model.
 */
class BaseController extends Controller
{
    public function behaviors()
    {
        $action = Yii::$app->controller->action;
        $permissionName = $action->controller->id . '/' . $action->id;
       
        // return 
        // dd(  $permissionName = $action->controller->id . '/' . $action->id);
        return [
            'access' => [
                'class' => AccessControl::class,
                'denyCallback' => function ($rule, $action) {
                    return $this->redirect(['site/index']); // Redirect unauthorized users to the home page
                },
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // Allow authenticated users
                        'matchCallback' => function ($rule, $action) {
                            $permissionName = $action->controller->id . '/' . $action->id;
                            return Yii::$app->user->can($permissionName) ;
                        },
                    ],
                ],
            ],
        ];
    }

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