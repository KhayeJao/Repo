<?php

namespace backend\controllers;

use common\models\ComboDish;
use common\models\ComboDishSearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use dmstr\bootstrap\Tabs;
use Yii;
use yii\helpers\CommonHelper;

/**
 * ComboDishController implements the CRUD actions for ComboDish model.
 */
class ComboDishController extends Controller {

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
     * Lists all ComboDish models.
     * @return mixed
     */
    public function actionIndex() {
        $params = Yii::$app->request->queryParams;
        if ($params) {
            $id = $params['ComboDishSearch']['combo_id'];
            if ($id) {
                CommonHelper::comboAccessControl($id);
                $searchModel = new ComboDishSearch;
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
     * Displays a single ComboDish model.
     * @param integer $id
     *
     * @return mixed
     */
    public function actionView($id) {
        CommonHelper::comboAccessControl($id);
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
     * Creates a new ComboDish model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $params = Yii::$app->request->queryParams;
        if ($params) {
            $id = $params['ComboDish']['combo_id'];
            if ($id) {
                CommonHelper::comboAccessControl($id);
                $model = new ComboDish;

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
                return $this->render('create', ['model' => $model]);
            }
        } else {
            $this->redirect(Url::previous());
        }
    }

    /**
     * Updates an existing ComboDish model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        CommonHelper::comboAccessControl($id);
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
     * Deletes an existing ComboDish model.
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
     * Finds the ComboDish model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ComboDish the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = ComboDish::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }

}
