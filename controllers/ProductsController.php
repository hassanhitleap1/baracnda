<?php

namespace app\controllers;

use app\models\attributeOptions\AttributeOptions;
use app\models\products\Products;
use app\models\products\ProductsSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductsController implements the CRUD actions for Products model.
 */
class ProductsController extends BaseController
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
     * Lists all Products models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ProductsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Products model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Products model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Products();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Products model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Products model.
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
     * Finds the Products model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Products the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Products::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }




    
    public function actionGenerateVariants()
{
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    $attributes = Yii::$app->request->post('attributes');
    $productId = Yii::$app->request->post('productId');

    if (empty($attributes) || empty($productId)) {
        return ['success' => false, 'message' => 'Invalid input data.'];
    }

    $product = Products::findOne($productId);
    if (!$product) {
        return ['success' => false, 'message' => 'Product not found.'];
    }

    // Generate variants based on selected attributes
    $variants = $this->generateVariants($attributes);

    foreach ($variants as $variantData) {
        $variant = new Variants();
        $variant->product_id = $productId;
        $variant->name = implode(' ', $variantData);
        $variant->price = $product->price; // Set price based on product or other logic
        $variant->quantity = 1; // Default quantity

        if (!$variant->save()) {
            return ['success' => false, 'message' => 'Failed to save variant: ' . json_encode($variant->errors)];
        }

        // Save variant attributes
        foreach ($variantData as $attributeId => $optionValue) {
            $variantAttribute = new VariantAttributes();
            $variantAttribute->variant_id = $variant->id;
            $variantAttribute->attribute_id = $attributeId;
            $variantAttribute->option_id = $this->findOptionId($attributeId, $optionValue);

            if (!$variantAttribute->save()) {
                return ['success' => false, 'message' => 'Failed to save variant attribute: ' . json_encode($variantAttribute->errors)];
            }
        }
    }

    return ['success' => true];
}

            private function generateVariants($attributes)
            {
                // This function should generate all possible combinations of selected attribute options
                // For simplicity, this example assumes a flat structure
                $variants = [];
                $this->combineAttributes($attributes, [], $variants);
                return $variants;
            }

            private function combineAttributes($attributes, $current, &$variants)
            {
                if (empty($attributes)) {
                    $variants[] = $current;
                    return;
                }

                $attributeId = key($attributes);
                $options = array_shift($attributes);

                foreach ($options as $option) {
                    $current[$attributeId] = $option;
                    $this->combineAttributes($attributes, $current, $variants);
                }
            }

            private function findOptionId($attributeId, $optionValue)
            {
                $option = AttributeOptions::findOne(['attribute_id' => $attributeId, 'value' => $optionValue]);
                return $option ? $option->id : null;
            }





}
