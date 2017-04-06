<?php

namespace backend\controllers;

use common\models\RestaurantImages;
use common\models\RestaurantImagesSearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\filters\AccessControl;
use yii\helpers\Url;
use dmstr\bootstrap\Tabs;
use Yii;
use yii\helpers\CommonHelper;

/**
 * RestaurantImagesController implements the CRUD actions for RestaurantImages model.
 */
class RestaurantImagesController extends Controller {

    /**
     * @var boolean whether to enable CSRF validation for the actions in this controller.
     * CSRF validation is enabled only when both this property and [[Request::enableCsrfValidation]] are true.
     */
    public $enableCsrfValidation = false;

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'create', 'update', 'delete'],
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action) {
        if (parent::beforeAction($action)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Lists all RestaurantImages models.
     * @return mixed
     */
    public function actionIndex() {
        $params = Yii::$app->request->queryParams;
        if ($params) {
            $id = $params['RestaurantImageSearch']['restaurant_id'];
            if ($id) {
                CommonHelper::restaurantAccessControl($id);
                $searchModel = new RestaurantImagesSearch;
                $dataProvider = $searchModel->search($_GET);

                Tabs::clearLocalStorage();

                Url::remember();
                \Yii::$app->session[
                        '__crudReturnUrl'] = null;

                return $this->render('index', [
                            'dataProvider' => $dataProvider,
                            'searchModel' => $searchModel,
                ]);
            }
        } else {
            $this->redirect(Url::previous());
        }
    }

    /**
     * Creates a new RestaurantImages model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $params = Yii::$app->request->queryParams;
        if ($params) {
            $id = $params['RestaurantImage']['restaurant_id'];
            if ($id) {
                CommonHelper::restaurantAccessControl($id);
                $model = new RestaurantImages;
                try {
                    if ($model->load(Yii::$app->request->post())) {
                        // process uploaded image file instance
                        $image = $model->uploadImage();
                        if ($model->save()) {
                            // upload only if valid uploaded file instance found
                            if ($image !== false) {
                                $path = $model->getImageFile();
                            }
                            $image->saveAs($path);
                            $destinationPath = Yii::$app->params['RImagePath'] . "resize/" . $model->image;
                            CommonHelper::resize($path, $destinationWidth = 250, $destinationHeight = 250, $destinationPath);
                            return $this->redirect(Url::previous());
                            //return $this->redirect(['index', 'modId' => $model->modId]);
                        } else {
                            // error
                        }
                    } elseif (!\Yii::$app->request->isPost) {
                        $model->load($_GET);
                    }
                } catch (\Exception $e) {
                    $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e
                                    ->getMessage();
                    $model->addError('_exception', $msg);
                }
                return $this->render('create', ['model' => $model, 'restaurant_id' => $id]);
            }
        } else {
            $this->redirect(Url::previous());
        }
    }

    /**
     * Updates an existing RestaurantImages model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $oldFile = $model->getImageFile();
        $oldRFile = $model->getResizeImageFile();
        $oldAvatar = $model->image;
        if ($model->load(Yii::$app->request->post())) {
            // process uploaded image file instance
            $image = $model->uploadImage();
            // revert back if no valid file instance uploaded
            if ($image === false) {
                $model->image = $oldAvatar;
            }
            if ($model->save()) {
                // upload only if valid uploaded file instance found
                if ($image !== false && unlink($oldFile) && unlink($oldRFile)) {  // delete old and overwrite
                    $path = $model->getImageFile();
                    $image->saveAs($path);

                    $destinationPath = Yii::$app->params['RImagePath'] . "resize/" . $model->image;
                    CommonHelper::resize($path, $destinationWidth = 250, $destinationHeight = 250, $destinationPath);
                }
                return $this->redirect(Url::previous());
                //return $this->redirect(['view', 'id' => $model->_id]);
            } else {
                // error in saving model
            }
        } else {
            return $this->render('update', ['model' => $model, 'restaurant_id' => $id]);
        }
    }

    /**
     * Deletes an existing RestaurantImages model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */ public function actionDelete($id) {
        try {
            $model = $this->findModel($id);
            // validate deletion and on failure process any exception 
            // e.g. display an error message 
            if ($model->delete()) {
                if (!$model->deleteImage()) {
                    Yii::$app->session->setFlash('error', 'Error deleting image');
                }
            }
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            \Yii::$app->getSession()->setFlash('error', $msg);
            return $this->redirect(Url::previous());
        }

        // TODO: improve detection
        $isPivot = strstr('$id', ',');
        if ($isPivot == true) {
            $this->redirect(Url::previous());
        } elseif (isset(\Yii::$app->session['__crudReturnUrl']) && \Yii::$app->session['__crudReturnUrl'] != '/') {
            Url::remember(null);
            $url = \

                    Yii::$app->session['__crudReturnUrl'];
            \Yii::$app->session['__crudReturnUrl'] = null;

            $this->redirect(
                    $url);
        } else {
            $this->redirect(['index']);
        }
    }

    /**
     * Finds the RestaurantImages model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RestaurantImages the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = RestaurantImages::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }

}
