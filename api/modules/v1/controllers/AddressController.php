<?php

namespace api\modules\v1\controllers;

use api\modules\v1\models\Address;
use api\modules\v1\models\AddressSearch;
use yii\rest\ActiveController;
use yii\web\HttpException;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\Json;
use Yii;
use yii\web\Response;

/**
 * AddressController implements the CRUD actions for Address model.
 */
class AddressController extends ActiveController {

    public $modelClass = 'api\modules\v1\models\address';
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

//    public function behaviors() {
//        $behaviors = parent::behaviors();
//        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;
//        $behaviors['access'] = array(
//            'class' => AccessControl::className(),
//            'rules' => [
//                [
//                    'allow' => true,
//                    'actions' => ['autocomplete', 'view', 'search'],
////                        'roles' => ['@']
//                ]
//            ]
//        );
//        return $behaviors;
//    }

    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;
        return $behaviors;
    }

    /**
     * Finds the Address model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Address the loaded model
     * @throws HttpException if the model cannot be found
     */
//    protected function findModel($id) {
//        if (($model = Address::findOne($id)) !== null) {
//            return $model;
//        } else {
//            throw new HttpException(404, 'The requested page does not exist.');
//        }
//    }

    public function actionUseraddresses() {
        $user_id = Yii::$app->request->post('user_id');
        $user_addresses = Address::findAll(['user_id' => $user_id]);
        if ($user_addresses) {
            $this->response['status'] = 1;
            $this->response['message'] = "User Addresses";
            $this->response['addresses'] = array();
            foreach ($user_addresses as $key => $value) {
                array_push($this->response['addresses'], array(
                    'id' => $value->id,
                    'address_line_1' => $value->address_line_1,
                    'address_line_2' => $value->address_line_2,
                    'area' => $value->area,
                    'city' => $value->city,
                    'pincode' => $value->pincode,
                    'country' => $value->country,
                    'area_name' => $value->area0->area_name,
                ));
            }
        } else {
            $this->response['status'] = 0;
            $this->response['message'] = "NO any user Addresses found";
        }
        return $this->response;
    }

    public function actionAddaddress() {
        $model = new Address();
        $model->country = "India";
        $model->created_on = date('Y-m-d H:i:s', time());
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->response['status'] = 1;
            $this->response['message'] = "Address added successfully";
            $this->response['data'] = $model;
        } else {
            if (!empty($model->errors)) {
                $keys = array_keys($model->errors);
                $this->response['status'] = 0;
                $this->response['message'] = $model->errors[$keys[0]][0];
            }
        }

        return $this->response;
    }

    public function actionDeleteaddress() {
        $id = Yii::$app->request->post('id');
        $model = $this->findModel($id);
        if ($model) {
            $model->delete();
            $this->response['status'] = 1;
            $this->response['message'] = "Address removed successfully";
        } else {
            $this->response['status'] = 1;
            $this->response['message'] = "Could not remove address";
        }
        return $this->response;
    }

    /**
     * Finds the Address model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Address the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Address::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }

}
