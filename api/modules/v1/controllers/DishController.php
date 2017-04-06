<?php

namespace api\modules\v1\controllers;

use api\modules\v1\models\Dish;
use api\modules\v1\models\DishesSearch;
use yii\rest\Controller;
use yii\web\HttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use dmstr\bootstrap\Tabs;
use Yii;
use yii\helpers\CommonHelper;
use yii\helpers\Json;
use api\modules\v1\models\FavDish;
use yii\web\Response;

/**
 * DishController implements the CRUD actions for Dish model.
 */
class DishController extends Controller {

    public $modelClass = 'api\modules\v1\models\Dish';
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
                    'actions' => ['autocomplete', 'view', 'search', 'addtofavourite'],
//                        'roles' => ['@']
                ]
            ]
        );
        return $behaviors;
    }

    public function actionAutocomplete($q) {
        $query = Dish::find();
        $query->select('tbl_dish.title, tbl_dish.id')
                ->orFilterWhere(['like', 'tbl_dish.title', $q])
                ->andFilterWhere(['=', 'tbl_dish.status', 'Active'])
                ->andFilterWhere(['=', 'tbl_restaurant.status', 'Active'])
                ->groupBy('title');
        $query->distinct(TRUE);
        $query->joinWith('restaurant');
        $result = $query->all();

        $dish_arr = array();
        foreach ($result as $key => $value) {
            array_push($dish_arr, array(
                'id' => $value->title,
                'name' => $value->title,
                'data-id' => $value->id,
                'type' => 'dish',
            ));
        }
        return $dish_arr;
    }

    public function actionAddtofavourite() {
        $user_id = Yii::$app->request->post('user_id');
        $dish_id = Yii::$app->request->post('dish_id');
        if (!$dish_id) {
            $this->response['status'] = 0;
            $this->response['message'] = 'Invalid dish';
        }
        $already_fav_dish_model = FavDish::findOne(['user_id' => $user_id, 'dish_id' => $dish_id]);
        if (!$already_fav_dish_model) {
            $fav_dish_model = new FavDish();
            $fav_dish_model->user_id = $user_id;
            $fav_dish_model->dish_id = $dish_id;
            $fav_dish_model->created_on = date('Y-m-d H:i:s', time());
            $fav_dish_model->insert(FALSE);
            $this->response['status'] = 1;
            $this->response['message'] = "Dish added to favourite";
        } else {
            $already_fav_dish_model->delete();
            $this->response['status'] = 0;
            $this->response['message'] = "Dish removed from favourite";
        }
        $total_fav_count = FavDish::find()->where(['dish_id' => $dish_id])->count();
        $this->response['total_fav_count'] = $total_fav_count;
        return $this->response;
    }

    /**
     * Finds the Dish model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Dish the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Dish::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }

}
