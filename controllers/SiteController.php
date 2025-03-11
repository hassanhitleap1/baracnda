<?php

namespace app\controllers;

use app\models\categories\Categories;
use app\models\contactus\Contactus;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\pages\Pages;
use app\models\products\Products;
use app\models\settings\Settings;
use app\models\slider\Slider;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $categories = Categories::find()->where(['category_id' => null])->limit(3)->all();
        $sliders = Slider::find()->all();
        $about = Pages::find()->where(['key' => 'aboutus'])->one();
        return $this->render('index', ['categories' => $categories, 'sliders' => $sliders, 'about' => $about]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        $this->layout = "login";
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {

        $model = new Contactus();
        $settings = Settings::find()->one();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                $model->contact(Yii::$app->params['adminEmail']);
                Yii::$app->session->setFlash('contactFormSubmitted');
                return $this->refresh();
            }
        }

        return $this->render('contact', [
            'model' => $model,
            'settings' => $settings,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        $model = Pages::find()->where(['key' => 'aboutus'])->one();
        return $this->render('about', ['model' => $model]);
    }

    public function actionCategories()
    {
        $categories = Categories::find()->where(['category_id' => null])->all();
        return $this->render('categories', ['categories' => $categories]);
    }
    public function actionTermAndConditions()
    {
        $model = Pages::find()->where(['key' => 'termsandconditions'])->one();
        return $this->render('termsandconditions', ['model' => $model]);
    }
    public function actionPrivacPpolicy()
    {
        $model = Pages::find()->where(['key' => 'privacypolicy'])->one();
        return $this->render('privacypolicy', ['model' => $model]);
    }

    public function actionSerach()
    {


        $query = Products::find()
            ->where(['like', 'name', Yii::$app->request->get('query')]);
        $pagination = new Pagination([
            'defaultPageSize' => 6, // Number of products per page
            'totalCount' => $query->count(),
        ]);

        $products = $query
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('products', [
            'products' => $products,
            'pagination' => $pagination,
            'category' => null,
            'slug' => null,
            'slug2' => null
        ]);


    }



    public function actionAutocomplete()
    {

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $productsQuery = Products::find();
        if (Yii::$app->request->get('query')) {
            $productsQuery->where(['like', 'name_en', Yii::$app->request->get('query')]);
        }
        $products = $productsQuery->limit(10)->all();
        return $products;
    }

    public function actionProducts($slug, $slug2 = null)
    {

        $category = null;
        if (is_null($slug2)) {
            $category = Categories::findOne(['slug' => $slug]);
        } else {
            $category = Categories::findOne(['slug' => $slug2]);
        }

        if (!$category) {
            throw new NotFoundHttpException('The requested category does not exist.');
        }
        $query = Products::find()->where(['category_id' => $category->id]);
        $pagination = new Pagination([
            'defaultPageSize' => 6, // Number of products per page
            'totalCount' => $query->count(),
        ]);
        $products = $query
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();


        if (!$products) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }


        return $this->render('products', [
            'products' => $products,
            'pagination' => $pagination,
            'category' => $category,
            'slug' => $slug,
            'slug2' => $slug2
        ]);


    }



    public function actionCategory($slug)
    {
        $category = Categories::findOne(['slug' => $slug]);

        if (!$category) {
            throw new NotFoundHttpException('The requested category does not exist.');
        }
        $categories = Categories::find()->where(['category_id' => $category->id])->all();

        if (count($categories)) {
            return $this->render('category', [
                'category' => $category,
                'categories' => $categories
            ]);
        }


        $query = Products::find()->where(['category_id' => $category->id]);
        $pagination = new Pagination([
            'defaultPageSize' => 6, // Number of products per page
            'totalCount' => $query->count(),
        ]);
        $products = $query
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();


        // if (!$products) {
        //     throw new NotFoundHttpException('The requested page does not exist.');
        // }


        return $this->render('products', [
            'products' => $products,
            'pagination' => $pagination,
            'category' => $category
        ]);



    }

    public function actionProduct($id)
    {

        $model = Products::findOne(['id' => $id]);

        if (!$model) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $this->render('product', [
            'model' => $model,

        ]);


    }


}