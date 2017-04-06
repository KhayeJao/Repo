<?php

namespace backend\controllers;

use common\models\SignupForm;
use common\models\base\Ordertable;
use common\models\base\Ordertablestatus;
use common\models\OrdertablestatusSearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use dmstr\bootstrap\Tabs;
use yii;
use yii\data\ActiveDataProvider;

/**
 * UserController implements the CRUD actions for User model. ordertablestatus
 */
class CallcenterController extends Controller { 

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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex() { 
		  $user_id=\yii::$app->user->identity->id;
		  $restaurant = \common\models\base\Restaurant::findOne(['user_id' => $user_id]); 
		  $restaurant_id = $restaurant['id'];  
		  $date = Date('Y-m-d');
          $connection = \Yii::$app->db;
		  $table_model = $connection->createCommand("SELECT table_no FROM tbl_ordertablestatus where restaurant_id='".$restaurant_id."' and Date(date)=Date('".$date."') and status='Pending' ")->queryAll();
		 // $table_model = implode($table_model['0'], ',');
		 foreach($table_model as $val){
		  $arr[]=$val['table_no'];
		 } 
		  
          $data = $connection->createCommand("SELECT id,table_no FROM tbl_ordertable where table_no NOT IN ('" .implode($arr, "','"). "') ")->queryAll();  
          $searchModel  = new OrdertablestatusSearch();
          $dataProvider = $searchModel->search($_GET); 
          Tabs::clearLocalStorage(); 
          Url::remember();
          \Yii::$app->session['__crudReturnUrl'] = null;
         //echo "<pre>";
        // print_r($searchModel);die;
        return $this->render('index', [ 
                    'tableModel' => $data,
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel
        ]);
    }

 

 
    protected function findModel($id) {
        if (($model = Ordertablestatus::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }

}
