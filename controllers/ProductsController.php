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
                            $file_path = "$folder_path/images/$key" . "." . $image_product->extension;
                            $modelImagesProduct->product_id = $newId;
                            $modelImagesProduct->path = $file_path;
                            $image_product->saveAs($file_path);

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

                    return $this->redirect(['view', 'id' => $model->id]);
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        } else {
            $model->loadDefaultValues();
            return $this->render('create', [
                'model' => $model,
            ]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }




    protected function saveVariantsAndAttributes($model)
    {
        // Assuming you have a form that submits variants and attributes data
        $variantsData = Yii::$app->request->post('Variants', []);
        $attributesData = Yii::$app->request->post('VariantAttributes', []);

        // Save variants
        foreach ($variantsData as $variantData) {
            $variant = new Variants();
            $variant->load($variantData, '');
            $variant->product_id = $model->id;
            if (!$variant->save()) {
                throw new \Exception('Failed to save variant: ' . implode(', ', $variant->getFirstErrors()));
            }

            // Save variant attributes
            // foreach ($attributesData as $attributeData) {
            //     $variantAttribute = new VariantAttributes();
            //     $variantAttribute->load($attributeData, '');
            //     $variantAttribute->variant_id = $variant->id;
            //     if (!$variantAttribute->save()) {
            //         throw new \Exception('Failed to save variant attribute: ' . implode(', ', $variantAttribute->getFirstErrors()));
            //     }
            // }
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


        if ($this->request->isPost && $model->load($this->request->post()) && $model->validate()) {

            $file = UploadedFile::getInstance($model, 'file');

            if (!empty($file)) {
                $folder_path = "images/products/$newId";
                FileHelper::createDirectory(
                    "$folder_path",
                    $mode = 0775,
                    $recursive = true
                );

                $file_path = "$folder_path/" . "covor." . $file->extension;
                $file->saveAs($file_path);
                $model->image = $file_path;
            }

            $model->save();


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
}
