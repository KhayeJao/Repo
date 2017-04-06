<?php

namespace backend\controllers;

use common\models\base\DeliveryBoy;
use common\models\DeliveryBoySearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\filters\AccessControl;
use yii\helpers\Url;
use dmstr\bootstrap\Tabs;
use Yii;
use yii\helpers\CommonHelper;

/**
 * CuisineController implements the CRUD actions for Cuisine model.
 */
class DeliveryBoyController extends Controller {

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
     * 
     * @return mixed
     */
    public function actionIndex() {
		 
        $searchModel = new DeliveryBoySearch;
        $dataProvider = $searchModel->search($_GET);
       // print_r($searchModel);die;
        Tabs::clearLocalStorage(); 
        Url::remember();
        \Yii::$app->session['__crudReturnUrl'] = null;
        
        $con = \Yii::$app->db;
        $rows = $con->createCommand("SELECT *  from  tbl_delivery_boy where 1=1")->queryAll();
        $rows_live = $con->createCommand("SELECT *  from  tbl_delivery_boy where status=10 and is_active='1' ")->queryAll(); //AND is_active=1

        return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
                    'data' => $rows,
                    'live_data' => $rows_live
        ]);
    }

    /**
     * Displays a single Cuisine model.
     * @param integer $id
     *
     * @return mixed
     */
    public function actionView($id) {
        $resolved = \Yii::$app->request->resolve();
        $resolved[1]['_pjax'] = null;
        $url = Url::to(array_merge(['/' . $resolved[0]], $resolved[1]));
        \Yii::$app->session['__crudReturnUrl'] = Url::previous();
        Url::remember($url);
        Tabs::rememberActiveState();

        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Cuisine model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new DeliveryBoy;

        try {
            if ($model->load(Yii::$app->request->post())) {
                // process uploaded image file instance
                
                $profile_pic = $model->uploadImage();
                $imageF      = $model->uploadFImage();
                if ($model->save()) {
                    // upload only if valid uploaded file instance found
                    if ($profile_pic !== false) {
                        $path = $model->getImageFile();
                    }
                     if ($imageF !== false) {
                        $path2 = $model->getImageFileImage();
                    }
                    if ($imageF) {
                        $imageF->saveAs($path2);
                    }
                    if($profile_pic){
						$profile_pic->saveAs($path);
				    }
                   
                    if ($profile_pic !== false) {
						
						 $destinationPath = Yii::$app->params['CImagePath'] . "resize/" . $model->profile_pic; 
                         CommonHelper::resize($path, $destinationWidth = 360, $destinationHeight = 360, $destinationPath);
                    }
                    if ($imageF !== false) { 
						
						$destinationPath2 = Yii::$app->params['CImagePath'] . "resize/" . $model->license_image;
                        CommonHelper::resize($path2, $destinationWidth = 350, $destinationHeight = 350, $destinationPath2);
                    }
                   
                   
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
        return $this->render('create', ['model' => $model]);
    }

    /**
     * Updates an existing Cuisine model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id); 
        $oldFile = $model->getImageFile();
        $oldRFile = $model->getResizeImageFile();
        $oldAvatar = $model->profile_pic;
        /* get lisence number image  */
        $oldFIFile  = $model->getImageFileImage();
        $oldFIRFile = $model->getResizeImageFileImage();
        $oldFAvatar = $model->license_image;
       // print_r($_POST);die;
        if ($model->load(Yii::$app->request->post())) {
            // process uploaded image file instance
            
            $profile_pic = $model->uploadImage();
            $imageF      = $model->uploadFImage();
            // revert back if no valid file instance uploaded
            if ($profile_pic === false && $imageF === false) {
                $model->profile_pic = $oldAvatar;
                $model->license_image = $oldFAvatar;
            }
            if ($model->save()) {
                // upload only if valid uploaded file instance found
                if ($profile_pic !== false) {  // delete old and overwrite
                    if (is_file($oldFile)) {
                       unlink($oldFile);
                    }

                    if (is_file($oldRFile)) {
                        unlink($oldRFile);
                    }
                    $path = $model->getImageFile();
                    $profile_pic->saveAs($path);
                    $destinationPath = Yii::$app->params['CImagePath'] . "resize/" . $model->profile_pic;
                      
                    CommonHelper::resize($path, $destinationWidth = 360, $destinationHeight = 360, $destinationPath);
                }
                
                if ($imageF !== false) {
                    if (is_file($oldFIFile)) {
                        unlink($oldFIFile);
                    }

                    if (is_file($oldFIRFile)) {
                        unlink($oldFIRFile);
                    }

                    $path2 = $model->getImageFileImage();
                    $imageF->saveAs($path2);
                    $destinationPath2 = Yii::$app->params['CImagePath'] . "resize/" . $model->license_image;
                    CommonHelper::resize($path2, $destinationWidth = 350, $destinationHeight = 350, $destinationPath2);
                }
                return $this->redirect(Url::previous());
                //return $this->redirect(['view', 'id' => $model->_id]);
            } else {
                return $this->render('update', ['model' => $model]);
                // error in saving model
            }
        } else {
            return $this->render('update', ['model' => $model]);
        }
    }

    /**
     * Deletes an existing Cuisine model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        try {
            $model = $this->findModel($id);
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
            $url = \Yii::$app->session['__crudReturnUrl'];
            \Yii::$app->session['__crudReturnUrl'] = null;

            $this->redirect($url);
        } else {
            $this->
                    redirect(['index']);
        }
    }

    /**
     * Finds the Cuisine model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Cuisine the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = DeliveryBoy::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }

}
