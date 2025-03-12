<?php

namespace app\controllers;

use app\models\categories\Categories;
use app\models\categories\CategoriesSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * CategoriesController implements the CRUD actions for Categories model.
 */
class CategoriesController extends BaseController
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
        $searchModel = new CategoriesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Categories model.
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
     * Creates a new Categories model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */  
    public function actionCreate()
    {
        $model = new Categories();

        $model->scenario = Categories::SCENARIO_CREATE;
        $newId = Categories::find()->max('id') + 1;


        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->validate()) {

                $file = UploadedFile::getInstance($model, 'file');

                if (!empty($file)) {
                    $folder_path = "images/categories/$newId";
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
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    /**
     * Updates an existing Categories model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
       public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $model->scenario = Categories::SCENARIO_UPDATE;
        $newId = $model->id;

        if ($this->request->isPost && $model->load($this->request->post()) && $model->validate()) {

            $file = UploadedFile::getInstance($model, 'file');

            if (!empty($file)) {
                $folder_path = "images/categories/$newId";
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
     * Deletes an existing Categories model.
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
     * Finds the Categories model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Categories the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Categories::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
