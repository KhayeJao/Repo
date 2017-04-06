<?php

namespace backend\controllers;

use common\models\Dish;
use common\models\DishesSearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use dmstr\bootstrap\Tabs;
use Yii;
use yii\helpers\CommonHelper;
use unclead\widgets\MultipleInput;

/**
 * DishController implements the CRUD actions for Dish model.
 */
class DishController extends Controller {

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
                        'actions' => ['index', 'view', 'create','create1', 'update', 'delete'],
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
     * Lists all Dish models.
     * @return mixed
     */
    public function actionIndex() {
        $params = Yii::$app->request->queryParams;
        if ($params) {
            $id = $params['DishesSearch']['restaurant_id'];
            if ($id) {
                CommonHelper::restaurantAccessControl($id);
                $searchModel = new DishesSearch;
                $dataProvider = $searchModel->search($_GET);
                $filter = $_GET;
                $resraurant_id = $filter['DishesSearch']['restaurant_id'];
                $restaurant_model = \common\models\base\Restaurant::findOne($resraurant_id);
                if ($restaurant_model) {
                    Tabs::clearLocalStorage();

                    Url::remember();
                    \Yii::$app->session['__crudReturnUrl'] = null;

                    return $this->render('index', [
                                'dataProvider' => $dataProvider,
                                'searchModel' => $searchModel,
                                'resraurant_id' => $resraurant_id,
                    ]);
                } else {
                    \Yii::$app->getSession()->setFlash('error', "Invalid restaurant");
                    return $this->redirect(Url::previous());
                }
            }
        } else {
            $this->redirect(Url::previous());
        }
    }

    /**
     * Displays a single Dish model.
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
     * Creates a new Dish model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $params = Yii::$app->request->queryParams;
       
        if ($params) {
            $id = $params['Dish']['restaurant_id'];
            
            if ($id) {
                CommonHelper::restaurantAccessControl($id);
                $model = new Dish;

                try {
                    if ($model->load($_POST) && $model->save()) {
						  \Yii::$app->getSession()->setFlash('success', "Data saved!");
                        return $this->redirect(Url::current());
                    } elseif (!\Yii::$app->request->isPost) {
                        $model->load($_GET);
                    }
                }catch (\Exception $e) {
                    $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
                    $model->addError('_exception', $msg);
                }
                 
                return $this->render('create', ['model' => $model]);
                
            }
        } else {
            $this->redirect(Url::previous());
        }
    }
    
    public function actionCreate1() {
        $params = Yii::$app->request->queryParams;
       
        if ($params) {
            $id = $params['Dish']['restaurant_id'];
            
            if ($id) {
                CommonHelper::restaurantAccessControl($id);
                $model = new Dish;
                $connection = \Yii::$app->db; 
                try {
                    if ($model->load($_POST) && $model->validateMultiple('Dish',$_POST['Dish'])) {  

						foreach ($_POST['Dish'] as $items);
						
							$restaurant_id =  $_POST['Dish'][restaurant_id]; 
							for($i=0;$i<count($items);$i++){  
							     $connection->createCommand()->insert('tbl_dish', ['menu_id' => $items[$i]['menu_id'],'restaurant_id' => $restaurant_id,'title' => $items[$i]['title'],'description' => $items[$i]['description'],'ingredients' => $items[$i]['ingredients'],'price' => $items[$i]['price'],'status' => $items[$i]['status'],])->execute();
							}
							// $id = Yii::$app->db->getLastInsertID();
							  
						  \Yii::$app->getSession()->setFlash('success', "Data saved!");
                        return $this->redirect(Url::current());
                    } elseif (!\Yii::$app->request->isPost) {
                        $model->load($_GET);
                    }
                }catch (\Exception $e) {
                    $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
                    $model->addError('_exception', $msg);
                }
                 
                return $this->render('create1', ['model' => $model]);
                
            }
        } else {
            $this->redirect(Url::previous());
        }
    }

    /**
     * Updates an existing Dish model.
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
     * Deletes an existing Dish model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        try {
//            $this->findModel($id)->delete();
            /* NOTE :  WE DON'T HAVE TO DELETE DISH AS WE HAVE IT'S FOREIGN KEY TO OLD ORDERS, SO WE MARK IT AS DELETED AND KEEP IT IN SYSTEM FOREVER! */
            $dish_model = $this->findModel($id);
            $dish_model->is_deleted = 'Yes';
            $dish_model->save(FALSE);
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
