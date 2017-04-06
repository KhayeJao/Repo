<?php

namespace backend\controllers;

use common\models\Table;
use common\models\TableSearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use dmstr\bootstrap\Tabs;
use yii\helpers\CommonHelper;
use Yii;

/**
 * TableController implements the CRUD actions for Table model.
 */
class TableController extends Controller {

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
     * Lists all Table models.
     * @return mixed
     */
    public function actionIndex() {
        $params = Yii::$app->request->queryParams;
        if ($params) {
            $id = $params['TableSearch']['restaurant_id'];
            if ($id) {
                CommonHelper::restaurantAccessControl($id);

                $searchModel = new TableSearch;
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
     * Displays a single Table model.
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
     * Creates a new Table model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $params = Yii::$app->request->queryParams;
        if ($params) {
            $id = $params['Table']['restaurant_id'];
            if ($id) {
                CommonHelper::restaurantAccessControl($id);
                $model = new Table;

                try {
                    if ($model->load($_POST)) {
                        $no_of_tables = \Yii::$app->request->post('no_of_tables');
                        for ($i = 1; $i <= $no_of_tables; $i++) {
                            $model_new = new Table;
                            $model_new->load($_POST);
                            $model_new->table_id = $this->getRandomTableName();
                            $model_new->insert(FALSE);
                        }
                        return $this->redirect(Url::previous());
                    } elseif (!\Yii::$app->request->isPost) {
                        $model->load($_GET);
                    }
                } catch (\Exception $e) {
                    $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
                    $model->addError('_exception', $msg);
                }
                return $this->render('create', ['model' => $model]);
            }
        } else {
            $this->redirect(Url::previous());
        }
    }

    /**
     * Updates an existing Table model.
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
     * Deletes an existing Table model.
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

    private function getRandomTableName() {
        $chars = "0123456789";
        $res = "";
        for ($i = 0; $i < 5; $i++) {
            $res .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $res;
    }

    /**
     * Finds the Table model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Table the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Table::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }

}
