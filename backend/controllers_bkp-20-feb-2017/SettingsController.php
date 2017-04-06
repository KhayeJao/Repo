<?php

namespace backend\controllers;

use common\models\Settings;
use common\models\SettingsSearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use dmstr\bootstrap\Tabs;
use yii\web\ForbiddenHttpException;
use Yii;
use yii\helpers\CommonHelper;

/**
 * SettingsController implements the CRUD actions for Settings model.
 */
class SettingsController extends Controller {

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
     * Lists all Settings models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new SettingsSearch;
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
     * Updates an existing Settings model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $oldFile = $model->getImageFile();
        $oldRFile = $model->getResizeImageFile();
        $oldAvatar = $model->value;
        
        if ($model->load(Yii::$app->request->post())) {
               if($id=='4' || $id=='5'){  
			  $model->url   = $_POST['Settings']['url']; 
                          $model->title   = $_POST['Settings']['title'];
			  $model->status = $_POST['Settings']['status']; 
		}
            // process uploaded image file instance
            
//            if ($model->type == "file") {
//                $image = $model->uploadImage();
//                // revert back if no valid file instance uploaded
//                if ($image === false) {
//                    $model->value = $oldAvatar;
//                }
//            }
            if ($model->save()) {
                
//                if ($model->type == "file") {
//                    // upload only if valid uploaded file instance found
//                    if ($image !== false) {  // delete old and overwrite
//                        if (file_exists($oldFile)) {
//                            unlink($oldFile);
//                        }
//
//                        if (file_exists($oldRFile)) {
//                            unlink($oldRFile);
//                        }
//                        $path = $model->getImageFile();
//                        $image->saveAs($path);
//                        $destinationPath = Yii::$app->params['ImagePath'] . "resize/" . $model->value;
//                        CommonHelper::resize($path, $destinationWidth = 1600, $destinationHeight = 520, $destinationPath);
//                    }
//                }
                return $this->redirect(Url::previous());
                //return $this->redirect(['view', 'id' => $model->_id]);
            } else {
                // error in saving model
            }
        } else {
            return $this->render('update', ['model' => $model]);
        }
    }

    /**
     * Finds the Settings model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Settings the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Settings::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }

}
