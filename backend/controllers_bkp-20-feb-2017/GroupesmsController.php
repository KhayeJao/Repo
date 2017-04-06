<?php

namespace backend\controllers;

use common\models\User;
use common\models\Groupe;
use common\models\Template;
use common\models\TemplateSearch;
use common\models\CsvForm;
use common\models\Marketing;
use yii\web\Controller;
use yii\web\HttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use dmstr\bootstrap\Tabs;
use common\components\Sms;

/**
 * ServiceController implements the CRUD actions for Service model.
 */
class GroupesmsController extends Controller {

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
                        'actions' => ['index', 'view', 'create','upload','marketingsms'],
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
     * Creates a new Groupesms model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
     
    public function getUser() {  
         $connection = \Yii::$app->db;
         $model3 = $connection->createCommand("SELECT first_name,last_name,mobile_no FROM user where mobile_no!='' and mobile_no REGEXP '[0-9]+' ORDER BY first_name asc")->queryAll(); 
         return $model3;
    }
     public function geMarketingMobile() {  
         $connection = \Yii::$app->db;
         $model_marketing = $connection->createCommand("SELECT name,mobile FROM marketing where mobile!='' and mobile REGEXP '[0-9]+' ORDER BY name asc ")->queryAll(); 
         return $model_marketing;
    }
    public function getSmsTemplate(){  
         $connection = \Yii::$app->db;
         $template = $connection->createCommand("SELECT sms,title FROM tbl_sms_template where sms!='' && status='Active' ")->queryAll(); 
         return $template;
    }
    
    public function actionCreate() { 
        $model  = new groupe;
        $users  = $this->getUser();  
        $marketing =    $this->geMarketingMobile();
        $smsTemplate = $this->getSmsTemplate();
        
        try {
            if ($model->load($_POST)) {
				  $sendSms  = new sms; 
				  $massage   =  $_POST['Groupe']['sms'];
				if($_POST['mobile_no']){
					\Yii::$app->session->setFlash('success','Your have been successfully send message!');
				}else{
				   \Yii::$app->session->setFlash('warning','Please Select Mobile Number!');
			    }
				 foreach($_POST['mobile_no'] as $mobile){
				    $sendSms->send($mobile,$massage);
				 } 
                return $this->redirect(Url::current());
            } elseif (!\Yii::$app->request->isPost) {
                $model->load($_GET);
            }
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e->getMessage();
            $model->addError('_exception', $msg);
        }
        return $this->render('create', ['model' => $model,'model1'=>$users,'marketing'=>$marketing,'template'=>$smsTemplate]);
    }

    /**
     * Updates an existing Service model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
  public function   actionMarketingsms(){ 

                       
	     
			if($_POST) {
				$sendSms  = new sms; 
				$massage   = $_POST['marketing'];
				
				if($_POST['mobile_no_m']!='' && $massage!='' ){ 
					  set_time_limit(500); 
					 foreach($_POST['mobile_no_m'] as $mobile){ 
						$sendSms->send($mobile,$massage);
					 } 
                                       $msg="<div class='alert alert-success' id='alert-mgs'>Your have been successfully send message!</div>";
				 
				 }else{
				    $msg= "<div class='alert alert-warning' id='alert-mgs'>Please Select Mobile Number! </div>";
			    }
			echo  $msg;
                //return $this->redirect(Url::current());
			
			}
 
		 
 }
     
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
     * Deletes an existing Smstemplate model.
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

// for csv  file upload  start //

    function clean($string) { 
    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }
function cleanNumber($string) { 
    return preg_replace('/[^0-9\-]/', '', $string); // Removes special chars.
    }
    
    public function actionUpload(){
    $model = new CsvForm; 
    
    $csv_array = Array();  
    $data = Array(); 
    $arrFileName = explode('.',$_FILES['file-0']['name']); 

  if($arrFileName[1] =='csv'){  
	  $file = fopen($_FILES['file-0']['tmp_name'], 'r');
			if($file){
				while (($line = fgetcsv($file)) !== FALSE) {
				  //$line is an array of the csv elements
				  array_push($csv_array,$line);
				}
				fclose($file);
			}  
        if($csv_array){ 
				 $connection = \Yii::$app->db;
				 $connection->createCommand("TRUNCATE TABLE marketing")->query();
				foreach($csv_array as $data){ 
					    $modelnew = new Marketing;
					    $mobile_exist=$modelnew::find()->where( [ 'mobile' => $data[1] ] )->exists(); 
					    $modelnew->name = $this->clean($data[0]);
					    $modelnew->mobile = $this->cleanNumber($data[1]); 
					if(!$mobile_exist){$modelnew->save(); }
				}  
		 $connection = \Yii::$app->db;
         $data['mobileData'] = $connection->createCommand("SELECT name,mobile FROM marketing")->queryAll();  
         $data['SmsTemplate'] =  $this->getSmsTemplate(); 
				return $this->renderAjax('marketing_view', $data);
                break; 
        }
        
     }else{
		$mag= "<div class='alert alert-warning' id='alert-mgs'>Please select csv file! </div>";
	 }
   
    
    
    // return $this->render('create', ['model' => $model,'model1'=>$users,'template'=>$smsTemplate]);
    
}

// end  //



    /**
     * Finds the Service model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Service the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Template::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }

}
