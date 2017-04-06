<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;
use yii\db\Query;
use yii\data\ActiveDataProvider;

/**
 * Site controller
 */
class SiteController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex() {
        $params = Yii::$app->request->queryParams;
        if ($params) {
            $date = $params['site']['date'];
        } else {
            $date = Yii::$app->formatter->asDatetime('now');
        }

        $month = date("m", strtotime($date));
        $year = date("Y", strtotime($date));
        if (Yii::$app->user->identity->type == "restaurant") {
            $rs = \common\models\Restaurant::findAll(['user_id' => Yii::$app->user->identity->id]);
            $count = \common\models\Restaurant::find()->where(['user_id' => Yii::$app->user->identity->id])->count();
            $i = 0;
            $arr = "";
            $arr2 = "";
            foreach ($rs as $key => $value) {
                $arr .= "restaurant_id = '" . $value->id . "'";
                $arr2 .= "r.id = '" . $value->id . "'";
                if ($count > $key + 1) {
                    $arr .= " OR ";
                    $arr2 .= " OR ";
                } 
                $i++;
            }

            $restaurant = \common\models\Restaurant::find()->where(['MONTH(created_date)' => $month, 'YEAR(created_date)' => $year, 'id' => Yii::$app->user->identity->id])->count();
            $order = \common\models\Order::find()->where('MONTH(booking_time) = "' . $month . '" AND YEAR(booking_time) = "' . $year . '" AND (' . $arr . ') AND status = "Completed"')->count();
            $customer = \common\models\User::find()->where(['MONTH(FROM_UNIXTIME(created_at))' => $month, 'YEAR(FROM_UNIXTIME(created_at))' => $year, 'type' => 'customer'])->count();
            $orderAmount = \common\models\Order::find()->where('MONTH(booking_time) = "' . $month . '" AND YEAR(booking_time) = "' . $year . '" AND (' . $arr . ') AND status = "Completed"')->sum('grand_total');
            $orderAvg = \common\models\Order::find()->where('MONTH(booking_time) = "' . $month . '" AND YEAR(booking_time) = "' . $year . '" AND (' . $arr . ') AND status = "Completed"')->average('grand_total');
            $query = new Query;
            $query->from('tbl_order')
                    ->join('INNER JOIN', 'tbl_restaurant', 'tbl_order.restaurant_id = tbl_restaurant.id')
                    ->where('MONTH(tbl_order.booking_time) = "' . $month . '" AND YEAR(tbl_order.booking_time) = "' . $year . '" AND (' . $arr . ') AND tbl_order.status = "Completed"')
                    ->select('SUM(tbl_restaurant.kj_share*grand_total/100) as income');
            $command = $query->createCommand();
            $kIncome = $command->queryOne();

            $con = \Yii::$app->db;
            $rows = $con->createCommand("SELECT r.title as Restaurent, o.booking_time as date, COUNT(o.id) as Orders, SUM(o.grand_total) as Total, SUM(r.kj_share*grand_total/100) as Income, r.kj_share as Per FROM tbl_order as o INNER JOIN tbl_restaurant as r ON o.restaurant_id = r.id WHERE MONTH(o.booking_time) = '" . $month . "' AND YEAR(o.booking_time) = '" . $year . "' AND  (" . $arr2 . ") AND o.status = 'Completed' group by r.id")->queryAll();
        } else {

            $restaurant = \common\models\Restaurant::find()->where(['MONTH(created_date)' => $month, 'YEAR(created_date)' => $year])->count();
            $order = \common\models\Order::find()->where(['MONTH(booking_time)' => $month, 'YEAR(booking_time)' => $year])->count();
            $customer = \common\models\User::find()->where(['MONTH(FROM_UNIXTIME(created_at))' => $month, 'YEAR(FROM_UNIXTIME(created_at))' => $year, 'type' => 'customer'])->count();
            $orderAmount = \common\models\Order::find()->where(['MONTH(booking_time)' => $month, 'YEAR(booking_time)' => $year])->sum('grand_total');
            $orderAvg = \common\models\Order::find()->where(['MONTH(booking_time)' => $month, 'YEAR(booking_time)' => $year])->average('grand_total');
            $query = new Query;
            $query->from('tbl_order')
                    ->join('INNER JOIN', 'tbl_restaurant', 'tbl_order.restaurant_id = tbl_restaurant.id')
                    ->where('MONTH(tbl_order.booking_time) = "' . $month . '" AND YEAR(tbl_order.booking_time) = "' . $year . '"')
                    ->select('SUM(tbl_restaurant.kj_share*grand_total/100) as income');
            $command = $query->createCommand();
            $kIncome = $command->queryOne();

            $con = \Yii::$app->db;
            $rows = $con->createCommand("SELECT r.title as Restaurent, COUNT(o.id) as Orders, SUM(o.grand_total) as Total, SUM(r.kj_share*grand_total/100) as Income, r.kj_share as Per FROM tbl_order as o INNER JOIN tbl_restaurant as r ON o.restaurant_id = r.id WHERE MONTH(o.booking_time) = '" . $month . "' AND YEAR(o.booking_time) = '" . $year . "' group by r.id")->queryAll();
        }
        return $this->render('index', [
                    'restCount' => $restaurant,
                    'orderCount' => $order,
                    'custCount' => $customer,
                    'orderAmount' => round($orderAmount, 2),
                    'orderAvg' => round($orderAvg, 2),
                    'kIncome' => round($kIncome['income'], 2),
                    'dataProvider' => $rows
        ]);
    }

    public function actionLogin() {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $this->layout = 'single';
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                        'model' => $model,
            ]);
        }
    }

    public function actionLogout() {
        $this->layout = 'single';
        Yii::$app->user->logout();
        return $this->goHome();
    }

}
