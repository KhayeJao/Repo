<?php

namespace backend\controllers;

use common\models\base\DeliveryBoy;
use common\models\DeliveryBoySearch;
use common\models\DeliveryBoyOrder;
use common\models\DeliveryBoyOrderSearch;
use common\models\DeliveryBoyTravelSearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\filters\AccessControl;
use yii\helpers\Url;
use dmstr\bootstrap\Tabs;
use Yii;
use yii\helpers\CommonHelper;

/**
 * CuisineController implements the CRUD actions for Cuisine model.
 */
class DeliveryBoyController extends Controller {

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
                        'actions' => ['index', 'view', 'create', 'update', 'delete','ajaxCheck','location','selectRestaurant'],
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
     * 
     * @return mixed
     */
    public function actionIndex() {
		 
        $searchModel = new DeliveryBoySearch;
        $dataProvider = $searchModel->search($_GET);
       // print_r($searchModel);die;
        Tabs::clearLocalStorage(); 
        Url::remember();
        \Yii::$app->session['__crudReturnUrl'] = null;
		$user_id = yii::$app->user->identity->id;
		$restaurant_model = \common\models\base\Restaurant::findOne(['user_id' => $user_id]);
		
           
        $con = \Yii::$app->db;
		if(!$restaurant_model){
			
			
			 if($_GET['restaurant_id']){
				$restaurant_id=$_GET['restaurant_id'];
				$restaurant_model = \common\models\base\Restaurant::findOne(['id' => $restaurant_id]);
				$rows = $con->createCommand("SELECT *  from  tbl_delivery_boy where restaurant_id=".$restaurant_id."")->queryAll();
				$rows_live = $con->createCommand("SELECT *  from  tbl_delivery_boy where status=10 and is_active='1' and restaurant_id=".$restaurant_id." ")->queryAll(); //AND is_active=1
				$latitude  =$restaurant_model->latitude;
				$longitude =$restaurant_model->longitude;
			 }else{
				$rows = $con->createCommand("SELECT *  from  tbl_delivery_boy where 1=1")->queryAll();
				$rows_live = $con->createCommand("SELECT *  from  tbl_delivery_boy where status=10 and is_active='1' ")->queryAll(); //AND is_active=1 
				$latitude  ="23.0300";
				$longitude ="72.5800";
			 }
               
        }else{
		    $restaurant_id=$restaurant_model->id;
			$rows = $con->createCommand("SELECT *  from  tbl_delivery_boy where restaurant_id=".$restaurant_id."")->queryAll();
			$rows_live = $con->createCommand("SELECT *  from  tbl_delivery_boy where status=10 and is_active='1' and restaurant_id=".$restaurant_id." ")->queryAll(); //AND is_active=1
		    //print_r($rows_live);;
			$latitude  =$restaurant_model->latitude;
			$longitude =$restaurant_model->longitude;
			  
		}
        return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
                    'latitude' => $latitude ,
					'longitude' =>$longitude ,
					'data' => $rows,
                    'live_data' => $rows_live
        ]);
    }
	
	public function actionSelectRestaurant($user_id = 0, $restaurant_id = 0,$user_type='') {
		
        if (!$restaurant_id) {
            \Yii::$app->getSession()->setFlash('error', 'You have to select restaurant before plcing order');
            return $this->redirect(Url::previous());
        }
       // unset($_SESSION['cart']);
        $_SESSION['restaurant_id'] = $restaurant_id;
        $_SESSION['user_id']       = $user_id;
        $restaurant_model          = \common\models\base\Restaurant::findOne(['id' => $restaurant_id]);
        $_SESSION['user_types']    = $user_type;
		if($_SESSION['restaurant_id']){
			 return $this->redirect(Url::previous());
		}
	}

    /**
     * Displays a single Cuisine model.
     * @param integer $id
     *
     * @return mixed
     */
    public function actionView($id) {
		
		$searchModel  = new DeliveryBoyOrderSearch; 
        $dataProvider = $searchModel->search($_GET); 
		$searchModelT = new DeliveryBoyTravelSearch;
		//echo "<pre/>";
		//print_r($searchModelT);die;
		$dataProviderT = $searchModelT->search($_GET);
		
        Tabs::clearLocalStorage(); 
        Url::remember();
		
        $resolved = \Yii::$app->request->resolve();
        $resolved[1]['_pjax'] = null;
        $url = Url::to(array_merge(['/' . $resolved[0]], $resolved[1]));
        \Yii::$app->session['__crudReturnUrl'] = Url::previous();
        Url::remember($url);
        Tabs::rememberActiveState();

        return $this->render('view', [
		            'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
					'dataProviderT' => $dataProviderT,
                    'searchModelT' => $searchModelT,
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Cuisine model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
	 
 public function actionAjaxCheck(){
	 
	  $user_id = yii::$app->user->identity->id;
		    $restaurant_model = \common\models\base\Restaurant::findOne(['user_id' => $user_id]); 
		        if (yii::$app->user->identity->type=='restaurant' && $restaurant_model->is_delivery_boy!='0'){
					$number_of_boy=\common\models\base\DeliveryBoy::findAll(['restaurant_id' => $restaurant_model->id]);
					 
                    if($restaurant_model->number_of_delivery_boy<=count($number_of_boy)){
					  //Yii::$app->session->setFlash('warning', 'You have already added max. Delivery boys . Please contact Khayejao on support@khayejao.com or 7046545454 to increase your limit!');  
					  //return $this->redirect(Url::previous());
					  $response = array(
							'status' => 0,
							'message' => 'You have already added max. Delivery boys . Please contact Khayejao on support@khayejao.com or 7046545454 to increase your limit!',
							);
					}	
					$date=date("d-m-Y");
                    if(date("d-m-Y", $restaurant_model->delivery_boy_validity)<=$date){
						
					  //Yii::$app->session->setFlash('warning', 'Your subscription for DB management is expired. Please contact KhayeJao on support@khayejao.com or 7046545454 to renew it!');  
					  //return $this->redirect(Url::previous());
					  $response = array(
							'status' => 0,
							'message' => 'Your subscription for DB management is expired. Please contact KhayeJao on support@khayejao.com or 7046545454 to renew it!',
							);
					}						
				return Json::encode($response);
				}
	 
	 
 }
 
 public function actionLocation(){
	
		$user_id = yii::$app->user->identity->id;
		$con = \Yii::$app->db;
		$response ='';
		$restaurant_model = \common\models\base\Restaurant::findOne(['user_id' => $user_id]); 
			if (yii::$app->user->identity->type=='restaurant' && $restaurant_model->is_delivery_boy!='0'){
				//$number_of_boy=\common\models\base\DeliveryBoy::findAll(['restaurant_id' => $restaurant_model->id]);
				$rows_live = $con->createCommand("SELECT *  from  tbl_delivery_boy where status=10 and is_active='1' and restaurant_id=".$restaurant_model->id." ")->queryAll(); //AND is_active=1
				if(count($rows_live)>0){
					 
					for($i=0;$i<count($live_data);$i++){
			 
		             $response .= $live_data[$i]['first_name']." ".$live_data[$i]['last_name'].','.$live_data[$i]['latitude'].','.$live_data[$i]['longitude'].', 4,';
		            }
					 
				}else{
					$response = '';
				}
				
				
				
			}else{
				$response = array(
							'status' => 0,
							'message' => 'No delivery boy module sucription!',
							);
			}
			
			echo $response;
	 
 }
    public function actionCreate() {
        $model = new DeliveryBoy;
		
		    $user_id = yii::$app->user->identity->id;
		    $restaurant_model = \common\models\base\Restaurant::findOne(['user_id' => $user_id]); 
		        if (yii::$app->user->identity->type=='restaurant' && $restaurant_model->is_delivery_boy!='0'){
					$number_of_boy=\common\models\base\DeliveryBoy::findAll(['restaurant_id' => $restaurant_model->id]);
					 
                    if($restaurant_model->number_of_delivery_boy<=count($number_of_boy)){
					  Yii::$app->session->setFlash('warning', 'You have already added max. Delivery boys . Please contact Khayejao on support@khayejao.com or 7046545454 to increase your limit!');  
					  return $this->redirect(Url::previous());
					}	
					$date=date("d-m-Y");
                    if(date("d-m-Y", $restaurant_model->delivery_boy_validity)<=$date){
						
					  Yii::$app->session->setFlash('warning', 'Your subscription for DB management is expired. Please contact KhayeJao on support@khayejao.com or 7046545454 to renew it!');  
					  return $this->redirect(Url::previous());
					}						
				
				}
 

        try {
            if ($model->load(Yii::$app->request->post())) {
                 
                
                $profile_pic = $model->uploadImage();
                $imageF      = $model->uploadFImage();
				//$_POST['password_hash']=$model->setPassword($_POST['DeliveryBoy']['password_hash']);
				$model->setPassword($_POST['DeliveryBoy']['password_hash']);
				//echo $_POST['DeliveryBoy']['restaurant_id'];die;
                if ($model->save()) {
                    // upload only if valid uploaded file instance found
                    if ($profile_pic !== false) {
                        $path = $model->getImageFile();
                    }
                     if ($imageF !== false) {
                        $path2 = $model->getImageFileImage();
                    }
                    if ($imageF) {
                        $imageF->saveAs($path2);
                    }
                    if($profile_pic){
						$profile_pic->saveAs($path);
				    }
                   
                    if ($profile_pic !== false) {
						
						 $destinationPath = Yii::$app->params['CImagePath'] . "resize/" . $model->profile_pic; 
                         CommonHelper::resize($path, $destinationWidth = 360, $destinationHeight = 360, $destinationPath);
                    }
                    if ($imageF !== false) { 
						
						$destinationPath2 = Yii::$app->params['CImagePath'] . "resize/" . $model->license_image;
                        CommonHelper::resize($path2, $destinationWidth = 350, $destinationHeight = 350, $destinationPath2);
                    }
                   
                   
                    return $this->redirect(Url::previous());
                    //return $this->redirect(['index', 'modId' => $model->modId]);
                } else {
                    // error
                }
            } elseif (!\Yii::$app->request->isPost) {
                $model->load($_GET);
            }
        } catch (\Exception $e) {
            $msg = (isset($e->errorInfo[2])) ? $e->errorInfo[2] : $e
                            ->getMessage();
            $model->addError('_exception', $msg);
        }
        return $this->render('create', ['model' => $model]);
    }

    /**
     * Updates an existing Cuisine model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
		 
        $model = $this->findModel($id); 
        $oldFile = $model->getImageFile();
        $oldRFile = $model->getResizeImageFile();
        $oldAvatar = $model->profile_pic;
        /* get lisence number image  */
        $oldFIFile  = $model->getImageFileImage();
        $oldFIRFile = $model->getResizeImageFileImage();
        $oldFAvatar = $model->license_image;
		$model->password_hash ='';
       // print_r($_POST);die;
        if ($model->load(Yii::$app->request->post())) {
            // process uploaded image file instance
            
            $profile_pic = $model->uploadImage();
			
            $imageF      = $model->uploadFImage();
            // revert back if no valid file instance uploaded
            if ($profile_pic === false && $imageF === false) {
                $model->profile_pic = $oldAvatar;
                $model->license_image = $oldFAvatar;
            }
			if($_POST['DeliveryBoy']['password_hash']){
				$model->setPassword($_POST['DeliveryBoy']['password_hash']);
			}
            if ($model->save()) {
                // upload only if valid uploaded file instance found
                if ($profile_pic !== false) {  // delete old and overwrite
                    if (is_file($oldFile)) {
                       unlink($oldFile);
                    }

                    if (is_file($oldRFile)) {
                        unlink($oldRFile);
                    }
                    $path = $model->getImageFile();
					
                    $profile_pic->saveAs($path);
                    $destinationPath = Yii::$app->params['CImagePath'] . "resize/" . $model->profile_pic;
                      
                    CommonHelper::resize($path, $destinationWidth = 360, $destinationHeight = 360, $destinationPath);
                }
                
                if ($imageF !== false) {
                    if (is_file($oldFIFile)) {
                        unlink($oldFIFile);
                    }

                    if (is_file($oldFIRFile)) {
                        unlink($oldFIRFile);
                    }

                    $path2 = $model->getImageFileImage();
                    $imageF->saveAs($path2);
                    $destinationPath2 = Yii::$app->params['CImagePath'] . "resize/" . $model->license_image;
                    CommonHelper::resize($path2, $destinationWidth = 350, $destinationHeight = 350, $destinationPath2);
                }
                return $this->redirect(Url::previous());
                //return $this->redirect(['view', 'id' => $model->_id]);
            } else {
                return $this->render('update', ['model' => $model]);
                // error in saving model
            }
        } else {
            return $this->render('update', ['model' => $model]);
        }
    }

    /**
     * Deletes an existing Cuisine model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        try {
            $model = $this->findModel($id);
            if ($model->delete()) {
                if (!$model->deleteImage()) {
                    Yii::$app->session->setFlash('error', 'Error deleting image');
                }
            }
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
            $this->
                    redirect(['index']);
        }
    }

    /**
     * Finds the Cuisine model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Cuisine the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = DeliveryBoy::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }
	
	 

}
