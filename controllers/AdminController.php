<?php

namespace app\controllers;

use app\models\categories\Categories;
use app\models\contactus\Contactus;
use app\models\products\Products;
use yii\filters\VerbFilter;


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
        $countProduct = Products::find()->count();
        $countCategories = Categories::find()->count();
        $countRequastUnreaded = Contactus::find()->where(['readed' => 0])->count();
        return $this->render('index', ['countProduct' => $countProduct, 'countCategories' => $countCategories, 'countRequastUnreaded' => $countRequastUnreaded]);
    }
}