<?php

namespace app\controllers;

use app\models\attributeOptions\AttributeOptions;
use app\models\images\Images;
use app\models\products\Products;
use app\models\products\ProductsSearch;
use app\models\variantAttributes\VariantAttributes;
use app\models\variants\Variants;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

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
        $model->scenario = Products::SCENARIO_CREATE;
        $newId = Products::find()->max('id') + 1;

        if ($this->request->isPost) {
         
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($model->load($this->request->post()) && $model->validate()) {
                    $files = UploadedFile::getInstance($model, 'files');

                    if (!empty($files)) {

                        $folder_path = "images/products/$newId";
                        FileHelper::createDirectory(
                            "$folder_path",
                            $mode = 0775,
                            $recursive = true
                        );

                        foreach ($files as $key => $file) {
                            $modelImagesProduct = new  Images();
                            $file_path = "$folder_path/images/$key" . "." . $file->extension;
                            $modelImagesProduct->product_id = $newId;
                            $modelImagesProduct->path = $file_path;
                            $file->saveAs($file_path);

                            if ($key == 0) {
                                $model->image = $file_path;
                            }

                            $modelImagesProduct->save(false);
                        }
                    }
                    if ($model->save(false) ) {
                        // Handle variants and attributes if any
                        if($this->saveVariantsAndAttributes($model)){
                            $transaction->commit();
                            return $this->redirect(['view', 'id' => $model->id]);
                        }
            
                    }else{
                        Yii::$app->session->setFlash('error', $this->getErrorMessages($model));
                    }
                }else{
                  
                    Yii::$app->session->setFlash('error', $this->getErrorMessages($model));
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', $this->getErrorMessages($model));
            }
        } else {
            $model->loadDefaultValues();
        
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }




    protected function saveVariantsAndAttributes($model)
    {
        // Assuming you have a form that submits variants and attributes data
        $variantsData = Yii::$app->request->post('Variants', []);


        $postData = Yii::$app->request->post('Product');
        $variantNames = ArrayHelper::getValue($postData, 'variant_name', []);
        $variantPrices = ArrayHelper::getValue($postData, 'variant_price', []);
        $variantQuantities = ArrayHelper::getValue($postData, 'variant_quantity', []);
        $variantCosts = ArrayHelper::getValue($postData, 'variant_cost', []);
        
        // Ensure at least 2 variants are provided
      
        foreach ($variantNames as $index => $name) {
            $variant = new Variants();
            $variant->name = $name;
            $variant->price = $variantPrices[$index];
            $variant->quantity = $variantQuantities[$index];
            $variant->cost =$variantCosts[$index];
            $variant->product_id = $model->id;
            if (!$variant->save(false)) {
                Yii::$app->session->setFlash('error', $variant->getFirstErrors());
                return false;
            }
            // $attributes = json_decode($variantData->attributes);
            // foreach ($attributes as $attributeData) {
            //     $variantAttribute = new VariantAttributes();
            //     $variantAttribute->attribute_id = $attributeData->attribute_id;
            //     $variantAttribute->option_id = $attributeData->option_id;
            //     $variantAttribute->variant_id = $variant->id;
            //     if (!$variantAttribute->save()) {
            //         throw new \Exception('Failed to save variant attribute: ' . implode(', ', $variantAttribute->getFirstErrors()));
            //     }
            // }
            return true;
        }
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

        $model->scenario = Products::SCENARIO_UPDATE;
        $newId = $model->id;


        if ($this->request->isPost) {
            $transaction = Yii::$app->db->beginTransaction();


            try {


                if ($model->load($this->request->post()) && $model->validate()) {


                    $files = UploadedFile::getInstance($model, 'files');

                    if (!empty($files)) {

                        $folder_path = "images/products/$newId";
                        FileHelper::createDirectory(
                            "$folder_path",
                            $mode = 0775,
                            $recursive = true
                        );

                        foreach ($files as $key => $file) {
                            $modelImagesProduct = new  Images();
                            $file_path = "$folder_path/images/$key" . "." . $file->extension;
                            $modelImagesProduct->product_id = $newId;
                            $modelImagesProduct->path = $file_path;
                            $file->saveAs($file_path);

                            if ($key == 0) {
                                $model->image = $file_path;
                            }

                            $modelImagesProduct->save(false);
                        }
                    }

                    if ($model->save()) {
                        // Handle variants and attributes if any
                        $this->saveVariantsAndAttributes($model);

                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                }
                
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
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
}
