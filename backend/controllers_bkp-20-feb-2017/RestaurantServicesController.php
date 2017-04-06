<?php

namespace backend\controllers;

use common\models\RestaurantServices;
use common\models\RestaurantServicesSearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use dmstr\bootstrap\Tabs;
use yii\helpers\CommonHelper;
use yii\web\ForbiddenHttpException;
use Yii;

/**
 * RestaurantServicesController implements the CRUD actions for RestaurantServices model.
 */
class RestaurantServicesController extends Controller {

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
     * Lists all RestaurantServices models.
     * @return mixed
     */
    public function actionIndex() {
        $params = Yii::$app->request->queryParams;
        if ($params) {
            $id = $params['RestaurantServicesSearch']['restaurant_id'];
            if ($id) {
                CommonHelper::restaurantAccessControl($id);
                $searchModel = new RestaurantServicesSearch;
                $dataProvider = $searchModel->search($_GET);

                Tabs::clearLocalStorage();

                Url::remember();
                \Yii::$app->session['__crudReturnUrl'] = null;

                return $this->render('index', [
                            'dataProvider' => $dataProvider,
                            'searchModel' => $searchModel,
                            'restaurant_id' => $id,
                ]);
            }
        } else {
            $this->redirect(Url::previous());
        }
    }

    /**
     * Updates an existing RestaurantServices model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($restaurant_id) {
        CommonHelper::restaurantAccessControl($restaurant_id);
        $model = new RestaurantServices;
        if ($model->load($_POST)) {
            $str = implode(',', $model->service_id);
            $model->deleteAll('restaurant_id = :restaurant_id AND service_id NOT IN (' . $str . ')', [':restaurant_id' => $restaurant_id]);
            foreach ($model->service_id as $key => $value) {
                \Yii::$app->db->createCommand('INSERT IGNORE INTO ' . $model->tableName() . ' (restaurant_id, service_id) VALUES(' . $model->restaurant_id . ', ' . $value . ')')->execute();
            }
            $this->redirect(Url::previous());
        } else {
            $model = RestaurantServices::findOne(['restaurant_id' => $restaurant_id]);
            if (!$model) {
                $model = new RestaurantServices;
            }

            return $this->render('update', [
                        'model' => $model,
                        'restaurant_id' => $restaurant_id,
            ]);
        }
    }

    /**
     * Finds the RestaurantServices model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RestaurantServices the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = RestaurantServices::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }

}
