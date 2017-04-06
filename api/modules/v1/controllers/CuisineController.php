<?php

namespace api\modules\v1\controllers;

use api\modules\v1\models\Cuisine;
use api\modules\v1\models\CuisineSearch;
use yii\rest\Controller;
use yii\web\HttpException;
use yii\filters\AccessControl;
use Yii;
use yii\helpers\Json;
use yii\web\Response;

/**
 * CuisineController implements the CRUD actions for Cuisine model.
 */
class CuisineController extends Controller {

   public $modelClass = 'api\modules\v1\models\Cuisine';
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
                    'actions' => ['autocomplete', 'view', 'search'],
//                        'roles' => ['@']
                ]
            ]
        );


        return $behaviors;
    }

    public function actionAutocomplete($q) {
        $query = Cuisine::find();
        $query->orFilterWhere(['like', 'title', $q])
                ->andFilterWhere(['=', 'status', 'Active']);
        $query->distinct(TRUE);
        $result = $query->all();
                
        $restaurant_arr = array();
        foreach ($result as $key => $value) {
            array_push($restaurant_arr, array(
                'id' => $value->title,
                'name' => $value->title,
                'data-id' => $value->id,
                'type' => 'cuisine',
            ));
        }
        return $restaurant_arr;
    }

    /**
     * Finds the Cuisine model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Cuisine the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Cuisine::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }

}
