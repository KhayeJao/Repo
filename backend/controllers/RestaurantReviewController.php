<?php

namespace backend\controllers;

use common\models\RestaurantReview;
use common\models\RestaurantReviewSearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use dmstr\bootstrap\Tabs;
use yii\helpers\CommonHelper;
use Yii;

/**
 * RestaurantReviewController implements the CRUD actions for RestaurantReview model.
 */
class RestaurantReviewController extends Controller {

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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'changestatus'],
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
     * Lists all RestaurantReview models.
     * @return mixed
     */
    public function actionIndex() {
        $params = Yii::$app->request->queryParams;
        if ($params) {
            $id = $params['RestaurantReviewSearch']['restaurant_id'];
            if ($id) {
                CommonHelper::restaurantAccessControl($id);
                $searchModel = new RestaurantReviewSearch;
                $dataProvider = $searchModel->search($_GET);

                Tabs::clearLocalStorage();

                Url::remember();
                \Yii::$app->session['__crudReturnUrl'] = null;

                return $this->render('index', [
                            'dataProvider' => $dataProvider,
                            'searchModel' => $searchModel,
                ]);
            }
        } else {
            $this->redirect(Url::previous());
        }
    }

    public function actionChangestatus() {
        $model = new RestaurantReview; // your model can be loaded here
        // Check if there is an Editable ajax request
        if (isset($_REQUEST['status'])) {
            $value = $_REQUEST['status'];
            $model->updateAll(['status' => $_REQUEST['status']], 'id = ' . $_REQUEST['id']);
            echo \yii\helpers\Json::encode(['output' => $value, 'message' => '']);
        } else {
            echo \yii\helpers\Json::encode(['output' => 'error', 'message' => '']);
        }
        $this->redirect(Url::previous());
    }

    /**
     * Finds the RestaurantReview model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RestaurantReview the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = RestaurantReview::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }

}
