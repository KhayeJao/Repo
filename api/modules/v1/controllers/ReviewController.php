<?php

namespace api\modules\v1\controllers;

use api\modules\v1\models\RestaurantReview;
use api\modules\v1\models\RestaurantReviewSearch;
use yii\rest\Controller;
use yii\web\HttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use dmstr\bootstrap\Tabs;
use yii\helpers\CommonHelper;
use Yii;
use yii\helpers\Json;
use api\modules\v1\models\Restaurant;
use yii\web\Response;

/**
 * RestaurantReviewController implements the CRUD actions for RestaurantReview model.
 */
class ReviewController extends Controller {

    public $modelClass = 'api\modules\v1\models\Review';
    private $response = array(
        'status' => 0,
        'message' => 0,
    );

    public function init() {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE");
        header("Access-Control-Allow-Headers: x-requested-with");
        parent::init();
        Yii::$app->user->enableSession = FALSE;
        if (Yii::$app->request->post('app_token') != Yii::$app->params['application_token']) {
            $this->response['status'] = 0;
            $this->response['message'] = 'Invalid token';
            echo Json::encode($this->response);
            exit;
        }
    }

    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;
        $behaviors['access'] = array(
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['create', 'refreshreviews'],
//                        'roles' => ['@']
                ]
            ]
        );
        return $behaviors;
    }

    /**
     * Lists all RestaurantReview models.
     * @return mixed
     */
    public function actionCreate() {
        $model = new RestaurantReview;
        if ($model->load($_POST)) {
            if (!trim($model->title)) {
                $this->response['status'] = 0;
                $this->response['message'] = 'Please fill in review title';
            } else if (!trim($model->comment)) {
                $this->response['status'] = 0;
                $this->response['message'] = 'Please fill in your comment';
            } else if (!$model->rate) {
                $this->response['status'] = 0;
                $this->response['message'] = 'Please select your rating';
            } else {
                $model->status = "Active";
                $model->created_on = Yii::$app->formatter->asTimestamp(date('Y-d-m h:i:s'));
                if ($model->save()) {
                    $restaurant_model = Restaurant::findOne(['id' => $model->restaurant_id]);
                    $avg_qry = \common\models\RestaurantReview::find()->where(['restaurant_id' => $model->restaurant_id])->average('rate');
                    if ($restaurant_model) {
                        $restaurant_model->avg_rating = $avg_qry;
                        $restaurant_model->save(FALSE);
                    }
                    $this->response['status'] = 1;
                    $this->response['message'] = "Review posted successfully.";
                } else {
                    $this->response['status'] = 0;
                    $this->response['message'] = "Error!!!";
                    $this->response['errors'] = $model->getErrors();
                }
            }
        } else {
            $this->response['status'] = 0;
            $this->response['message'] = "Error!!!";
            $this->response['errors'] = $model->getErrors();
        }
        return $this->response;
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
