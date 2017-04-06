<?php

namespace backend\controllers;

use common\models\TableBooking;
use common\models\TableBookingSearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use dmstr\bootstrap\Tabs;
use common\models\base\Table;
use common\models\base\Restaurant;
use kartik\mpdf\Pdf;

/**
 * TablebookingController implements the CRUD actions for TableBooking model.
 */
class TablebookingController extends Controller {

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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'booktable', 'tablebookingoutputinfo'],
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
     * Lists all TableBooking models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TableBookingSearch;
        $dataProvider = $searchModel->search($_GET);

        Tabs::clearLocalStorage();

        Url::remember();
        \Yii::$app->session['__crudReturnUrl'] = null;

        return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single TableBooking model.
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
                    'table_booking' => $this->findModel($id),
        ]);
    }

    public function actionTablebookingoutputinfo($id, $act) {
        $id = base64_decode($id);
        $model = $this->findBookingModel($id);

        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('_tablebooking_view', ['table_booking' => $model]);

        $destination = Pdf::DEST_BROWSER;


        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => $destination,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
            // set mPDF properties on the fly
            'options' => ['title' => 'Order Invoice'],
            // call mPDF methods on the fly
            'methods' => [
                'SetHeader' => ['KhayeJao'],
                'SetFooter' => ['{PAGENO}'],
            ]
        ]);

        if ($act == 'download') {
            $pdf->output($content, "Invoice-" . $model->order_unique_id . ".pdf", Pdf::DEST_DOWNLOAD);
            return;
        }

        // return the pdf output as per the destination setting
        return $pdf->render();
    }

    /**
     * Creates a new TableBooking model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($restaurant_id, $user_id) {
        $model = new TableBooking;

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

    /**
     * Updates an existing TableBooking model.
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
     * Deletes an existing TableBooking model.
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

    public function actionBooktable($restaurant_id, $user_id) {
        $data = array();
        $data['controller'] = $this;
        $data['restaurant_info'] = Restaurant::findOne(['id' => $restaurant_id]);
        $data['two_tables'] = Table::findAll(['restaurant_id' => $restaurant_id, 'no_of_seats' => 2]);
        $data['four_tables'] = Table::findAll(['restaurant_id' => $restaurant_id, 'no_of_seats' => 4]);
        $data['six_tables'] = Table::findAll(['restaurant_id' => $restaurant_id, 'no_of_seats' => 6]);
        $data['eight_tables'] = Table::findAll(['restaurant_id' => $restaurant_id, 'no_of_seats' => 8]);
        return $this->render('book_table', $data);
    }

    /**
     * Finds the TableBooking model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TableBooking the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TableBooking::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }

    protected function findBookingModel($id) {
        if (($model = TableBooking::findOne(['order_unique_id' => $id])) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }

}
