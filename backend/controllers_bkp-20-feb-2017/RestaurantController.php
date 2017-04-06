<?php

namespace backend\controllers;

use common\models\Restaurant;
use common\models\RestaurantSearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use dmstr\bootstrap\Tabs;
use yii;
use yii\helpers\CommonHelper;

/**
 * RestaurantController implements the CRUD actions for Restaurant model.
 */
class RestaurantController extends Controller {

    public function actions() {
        parent::actions();
        return [
            'thumb' => 'iutbay\yii2imagecache\ThumbAction',
        ];
    }

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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'basicinfo','changestatus','imagedelete'],
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
     * Lists all Restaurant models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new RestaurantSearch;
        $dataProvider = $searchModel->search($_GET);

        Tabs::clearLocalStorage();

        Url::remember();
        \Yii::$app->session['__crudReturnUrl'] = null;

        return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Restaurant model.
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
     * Creates a new Restaurant model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Restaurant;
        $model->scenario = 'create';

        try {
            if ($model->load(Yii::$app->request->post())) {
                $model->created_date = date("Y-m-d H:s:i");
                // process uploaded image file instance uploadAImage
                $image = $model->uploadImage();
                $imageF = $model->uploadFImage();
                $imageA = $model->uploadAImage();

                if ($model->save()) {
                    // upload only if valid uploaded file instance found
                    if ($image !== false) {
                        $path = $model->getImageFile();
                    }
                    if ($imageF !== false) {
                        $path2 = $model->getImageFileImage();
                    }
                    if ($imageA !== false) {
                        $pathA = $model->getAdvertigeImage();
                    }
                    if ($image) {
                        $image->saveAs($path);
                    }
                    if ($imageF) {
                        $imageF->saveAs($path2);
                    }
                    if ($imageA) {
                        $imageA->saveAs($pathA);
                    }

                    $destinationPath = Yii::$app->params['logoPath'] . "resize/" . $model->logo;
                    $destinationPath_A = Yii::$app->params['logoPath'] . "resize/" . $model->advertise;
                    if ($imageF !== false) {
                        $destinationPath2 = Yii::$app->params['logoPath'] . "resize/" . $model->featured_image;
                    }
                    CommonHelper::resize($path, $destinationWidth = 250, $destinationHeight = 250, $destinationPath);
                    if ($imageF !== false) {
                        CommonHelper::resize($path2, $destinationWidth = 350, $destinationHeight = 350, $destinationPath2);
                    }
                    
                    if ($imageA !== false) {
                        CommonHelper::resize($pathA, $destinationWidth = 350, $destinationHeight = 350, $destinationPath_A);
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
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }
        return $this->render('create', ['model' => $model]);

        /*         * ************** */


//        try {
//            if ($model->load($_POST) && $model->save()) {
//                return $this->redirect(Url::previous());
//            } elseif (!\Yii::$app->request->isPost) {
//                $model->load($_GET);
//            }
//        } catch (\Exception $e) {
//            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
//            $model->addError('_exception', $msg);
//        }
//        return $this->render('create', ['model' => $model]);
    }

    /**
     * Updates an existing Restaurant model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $oldFile = $model->getImageFile();
        $oldRFile = $model->getResizeImageFile();
        $oldFIFile = $model->getImageFileImage();
        $oldFIRFile = $model->getResizeImageFileImage();
        $oldAvatar = $model->logo;
        $oldFAvatar = $model->featured_image;
        /* advertise  */
        $oldAFile = $model->getAdvertigeImage();
        $oldARFile = $model->getResizeAdvertigeImage();
        $oldAvAvatar = $model->advertise;
        /* end  */
        if ($model->load(Yii::$app->request->post())) {
            // process uploaded image file instance
            $image  =  $model->uploadImage();
            $imageF = $model->uploadFImage();
            $imageA = $model->uploadAImage();
            // revert back if no valid file instance uploaded
            if ($image === false && $imageF === false && $imageA===false) {
                $model->logo = $oldAvatar;
                $model->featured_image = $oldFAvatar;
                $model->advertise = $oldAvAvatar;
            }

            if ($model->save()) {
                // upload only if valid uploaded file instance found
                if ($image !== false) {  // delete old and overwrite
                    if (is_file($oldFile)) {
                        unlink($oldFile);
                    }
                    if (is_file($oldRFile)) {
                        unlink($oldRFile);
                    }
                    $path = $model->getImageFile();
                    $image->saveAs($path);
                    $destinationPath = Yii::$app->params['logoPath'] . "resize/" . $model->logo;
                    CommonHelper::resize($path, $destinationWidth = 250, $destinationHeight = 250, $destinationPath);
                }
                /* upload advertise image  */
                 // upload only if valid uploaded file instance found
                if ($imageA !== false) {  // delete old and overwrite
                    if (is_file($oldAFile)) {
                        unlink($oldAFile);
                    }
                    if (is_file($oldARFile)) {
                        unlink($oldARFile);
                    }
                    $path_a = $model->getAdvertigeImage();
                    $imageA->saveAs($path_a);
                    $destinationPath_a = Yii::$app->params['logoPath'] . "resize/" . $model->advertise;
                    CommonHelper::resize($path_a, $destinationWidth = 250, $destinationHeight = 250, $destinationPath_a);
                }
                /* enxd */
                
                
                if ($imageF !== false) {
                    if (is_file($oldFIFile)) {
                        unlink($oldFIFile);
                    }

                    if (is_file($oldFIRFile)) {
                        unlink($oldFIRFile);
                    }

                    $path2 = $model->getImageFileImage();
                    $imageF->saveAs($path2);
                    $destinationPath2 = Yii::$app->params['logoPath'] . "resize/" . $model->featured_image;
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

        /*         * ******* */

//        if ($model->load($_POST) && $model->save()) {
//            $this->redirect(Url::previous());
//        } else {
//            return $this->render('update', [
//                        'model' => $model,
//            ]);
//        }
    }

    /**
     * Deletes an existing Restaurant model.
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
            $this->redirect(['index']);
        }
    }
    
    public function actionImagedelete($id){   
        $model = new Restaurant;
			if (isset($id)) {
				$model->updateAll(['advertise' => ''], 'id = ' . $_REQUEST['id']);
			} 
		//$fileAV  = $model->getAdvertigeImage();
		//$RfileAV = $model->getResizeAdvertigeImage();  
		/*
		 if (empty($fileAV) || !file_exists($RfileAV)) {
            return false;
        }

        // check if uploaded file can be deleted on server
        if (!unlink($fileAV) || !unlink($RfileAV)) {
            return false;
        }
       
        * */ 
		$this->redirect(array('restaurant/update','id'=>$id));
	}

    public function actionBasicinfo($restaurant_id) {
        return $this->renderAjax('basic_view', ['model' => $this->findModel($restaurant_id)]);
    }

    public function actionChangestatus() {
        $model = new Restaurant;
        if (isset($_REQUEST['status'])) {
            $model->updateAll(['status' => $_REQUEST['status']], 'id = ' . $_REQUEST['id']);
        }
        $this->redirect(Url::previous());
    }

    /**
     * Finds the Restaurant model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Restaurant the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Restaurant::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }

}
