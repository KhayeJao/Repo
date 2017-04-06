<?php

namespace backend\controllers;

use common\models\DishTopping;
use common\models\DishToppingSearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use dmstr\bootstrap\Tabs;
use Yii;
use yii\helpers\CommonHelper;

/**
 * DishToppingController implements the CRUD actions for DishTopping model.
 */
class DishToppingController extends Controller {

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
     * Lists all DishTopping models.
     * @return mixed
     */
    public function actionIndex() {
        $params = Yii::$app->request->queryParams;
        if ($params) {
            $id = $params['DishToppingSearch']['topping_group_id'];
            if ($id) {
                CommonHelper::dishtoppingAccessControl($id);
                $searchModel = new DishToppingSearch;
                $dataProvider = $searchModel->search($_GET);

                Tabs::clearLocalStorage();

                Url::remember();
                \Yii::$app->session['__crudReturnUrl'] = null;

                return $this->render('index', [
                            'dataProvider' => $dataProvider,
                            'searchModel' => $searchModel,
                ]);
            }
        } else {
            $this->redirect(Url::previous());
        }
    }

    /**
     * Displays a single DishTopping model.
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
     * Creates a new DishTopping model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $params = Yii::$app->request->queryParams;
        if ($params) {
            $id = $params['DishTopping']['topping_group_id'];
            if ($id) {
                $toppingGroupModel = \common\models\base\ToppingGroup::findOne(['id' => $id]);
                $restaurant_id = $toppingGroupModel->dish->restaurant_id;
                CommonHelper::dishtoppingAccessControl($id);
                $model = new DishTopping;

                try {
                    if ($model->load($_POST) && $model->save()) {
                        return $this->redirect(Url::previous());
                    } elseif (!\Yii::$app->request->isPost) {
                        $model->load($_GET);
                    }
                } catch (\Exception $e) {
                    $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
                    $model->addError('_exception', $msg);
                }
                return $this->render('create', ['model' => $model, 'restaurant_id' => $restaurant_id]);
            }
        } else {
            $this->redirect(Url::previous());
        }
    }

    /**
     * Updates an existing DishTopping model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $toppingGroupModel = \common\models\base\ToppingGroup::findOne(['id' => $model->toppingGroup->id]);
        $restaurant_id = $toppingGroupModel->dish->restaurant_id;
        if ($model->load($_POST) && $model->save()) {
            $this->redirect(Url::previous());
        } else {
            return $this->render('update', [
                        'model' => $model,
                        'restaurant_id' => $restaurant_id
            ]);
        }
    }

    /**
     * Deletes an existing DishTopping model.
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
     * Finds the DishTopping model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DishTopping the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = DishTopping::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }

}
