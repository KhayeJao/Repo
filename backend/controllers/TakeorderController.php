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
use common\models\Settings;
use yii\data\ActiveDataProvider;

/**
 * UserController implements the CRUD actions for User model. ordertablestatus
 */
class TakeorderController extends Controller { 

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
                        'actions' => ['index', 'view', 'create', 'selectdelivery','update', 'delete'],
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

    /**
     * Displays a single User model.
     * @param integer $id
     *
     * @return mixed
     */
    public function actionView($id) {
		
        $resolved = \Yii::$app->request->resolve();
        $resolved[1]['_pjax'] = null;
        $url = Url::to(array_merge(['/' . $resolved[0]], $resolved[1]));
		
        \Yii::$app->session['__crudReturnUrl'] = Url::previous();
        Url::remember($url);
        Tabs::rememberActiveState();

        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {

        $model = new SignupForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                return $this->redirect(Url::previous());
//                if (Yii::$app->getUser()->login($user)) {
//                    return $this->goHome();
//                }
            }
        }
        return $this->render('create', [
                    'model' => $model,
        ]);

        /* old code */

//        $model = new User;
//
//        try {
//            if ($model->load($_POST) && $model->save()) {
//                return $this->redirect(Url::previous());
//            } elseif (!\Yii::$app->request->isPost) {
//                $model->load($_GET);
//            }
//        } catch (\Exception $e) {
//            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
//            $model->addError('_exception', $msg);
//        }
//        return $this->render('create', ['model' => $model]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) { 
        $model = $this->findModel($id);  
        $table_no=$model['table_no']; 
        $this->redirect(array("tableorder/placeorder?table_no=$table_no"));
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        try {
            $this->findModel($id)->delete();
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            \Yii::$app->getSession()->setFlash('error', $msg);
            return $this->redirect(Url::previous());
        }

        // TODO: improve detection
        $isPivot = strstr('$id', ',');
        if ($isPivot == true) {
            $this->redirect(Url::previous());
        } elseif (isset(\Yii::$app->session['__crudReturnUrl']) && \Yii::$app->session['__crudReturnUrl'] != '/') {
            Url::remember(null);
            $url = \Yii::$app->session['__crudReturnUrl'];
            \Yii::$app->session['__crudReturnUrl'] = null;

            $this->redirect($url);
        } else {
            $this->redirect(['index']);
        }
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Ordertablestatus::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }

}
