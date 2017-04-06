<?php

namespace api\modules\v1\controllers;

use api\modules\v1\models\Area;
use api\modules\v1\models\AreaSearch;
use yii\rest\Controller;
use yii\web\HttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use dmstr\bootstrap\Tabs;
use yii\helpers\Json;
use Yii;
use yii\web\Response;

/**
 * AreaController implements the CRUD actions for Area model.
 */
class AreaController extends Controller {

    public $modelClass = 'api\modules\v1\models\Area';
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
                    'actions' => ['autocomplete', 'view', 'search', 'getarea'],
//                        'roles' => ['@']
                ]
            ]
        );


        return $behaviors;
    }

    public function actionAutocomplete($q) {
//        $q = Yii::$app->request->post('q');
        $query = Area::find();
        $query->orFilterWhere(['like', 'area_name', $q]);
        $query->distinct(TRUE);
        $result = $query->all();
        $area_arr = array();
        foreach ($result as $key => $value) {
            array_push($area_arr, array(
                'id' => $value->area_name,
                'name' => $value->area_name,
                'data-id' => $value->id,
                'type' => 'area',
            ));
        }
        return $area_arr;
    }

    public function actionGetarea() {
        $areas = Area::find()->orderBy('area_name')->all();
        $area_arr = array();
        foreach ($areas as $key => $area) {
            array_push($area_arr, array(
                'id' => $area->id,
                'area_name' => $area->area_name
            ));
        }
        if ($areas) {
            $this->response['status'] = 1;
            $this->response['message'] = "List of Area";
            $this->response['data'] = $area_arr;
        } else {
            $this->response['status'] = 0;
            $this->response['message'] = "No Area found";
        }
        return $this->response;
    }

    /**
     * Finds the Area model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Area the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Area::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }

}
