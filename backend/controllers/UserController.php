<?php

namespace backend\controllers;

use common\models\SignupForm;
use common\models\base\User;
use common\models\UserSearch;
use common\models\base\LogisticUser;
use common\models\LogisticUserSearch;
use common\models\LogisticResturantUserSearch;
use common\models\LogisticCallcenterUserSearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use dmstr\bootstrap\Tabs;
use yii;
use yii\data\ActiveDataProvider;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller {

    public function __construct($id, $module, $config = array()) {
        parent::__construct($id, $module, $config);
        if ((!\Yii::$app->user->can('manageUsers')) AND ( !\Yii::$app->user->can('placeOrder') AND ( !\Yii::$app->user->can('logisticsUsers')) )) {
            $this->redirect(Url::to(['/']));
        }
    }

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

        $searchModel = new UserSearch;
        $dataProvider = $searchModel->search($_GET);
        $dataProviderCust = $searchModel->exportCustomer();
        
         $searchModel_l      = new LogisticUserSearch;
        
         $dataProvider_l     = $searchModel_l->search($_GET);
         $dataProviderCust_l = $searchModel_l->exportCustomer();
		 $searchModel_r      = new LogisticResturantUserSearch;
		 $dataProvider_r     = $searchModel_r->search($_GET);
         $dataProviderCust_r = $searchModel_r->exportCustomer();
         $searchModel_c      = new LogisticCallcenterUserSearch;
		 $dataProvider_c     = $searchModel_c->search($_GET);
         $dataProviderCust_c = $searchModel_c->exportCustomer();
        Tabs::clearLocalStorage();

        Url::remember();
        \Yii::$app->session['__crudReturnUrl'] = null;

        return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
                    'dataProviderCust' => $dataProviderCust,
                    'dataProvider_l' => $dataProvider_l,
                    'searchModel_l' => $searchModel_l,
                    'dataProviderCust_l' => $dataProviderCust_l,
					 'dataProvider_r' => $dataProvider_r,
                    'searchModel_r' => $searchModel_r,
                    'dataProviderCust_r' => $dataProviderCust_r,
					 'dataProvider_c' => $dataProvider_c,
                    'searchModel_c' => $searchModel_c,
                    'dataProviderCust_c' => $dataProviderCust_c
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     *
     * @return mixed
     */
    public function actionView($id) { 
		
		 $arr = explode('&',$_GET['id']);   
		 
		 if($arr['1']=='k'){
			 $id=$arr['0'];
			 
			 $model= $this->findModel($id);
			 $type ='K';
			  $user_to ='';
		 }else{ 
			 $id=$arr['0'];
			 $model= $this->findLModel($id);
			 $type ='L';
			 $user_to =$model->user_from;
		 }
			 
			
		 
	 
        $resolved = \Yii::$app->request->resolve();
        $resolved[1]['_pjax'] = null;
        $url = Url::to(array_merge(['/' . $resolved[0]], $resolved[1]));
        \Yii::$app->session['__crudReturnUrl'] = Url::previous();
        Url::remember($url);
        Tabs::rememberActiveState();

        return $this->render('view', [
                    'model' => $model,
                    'user_type' => $type,
					'user_to' => $user_to,
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

        if ($model->load($_POST) && $model->save()) {
            $this->redirect(Url::previous());
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
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
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }
    
     protected function findLModel($id) {
        if (($model = LogisticUser::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }

}
