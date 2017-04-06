<?php
namespace backend\controllers;
use  yii\web\Session;
use common\models\Order; 
use common\models\Ordertabledetails; 
use common\models\Ordertablestatus; 
use common\models\User;
use common\models\OrderSearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use dmstr\bootstrap\Tabs;
use common\models\base\Dish;
use common\models\base\Combo;
use yii;
use common\models\base\DishTopping;
use yii\helpers\Json;
use common\models\base\RestaurantArea;
use common\models\base\Address;
use common\models\base\Restaurant;
use kartik\mpdf\Pdf;
use common\components\Sms;
use common\models\OrderTopping;

/**  
 * TableorderController implements the CRUD actions for Order model. tableorder
 */
class TableorderController extends Controller {

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
                        'actions' => ['index', 'view','success','create','outputinfo','update', 'delete', 'placeorder', 'adddish', 'refreshcart', 'removedish', 'emptycart', 'checkout', 'checkoutpricess', 'addcomment', 'selectaddress', 'changestatus', 'outputinfo','saveorder'],
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
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new OrderSearch;
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
     * Displays a single Order model.
     * @param integer $id
     *
     * @return mixed
     */
    public function actionView($id) {
		  
       // print_r($model);die;
        $resolved = \Yii::$app->request->resolve();
        $resolved[1]['_pjax'] = null;
        $url = Url::to(array_merge(['/' . $resolved[0]], $resolved[1]));
        \Yii::$app->session['__crudReturnUrl'] = Url::previous();
        Url::remember($url);
        Tabs::rememberActiveState();
        $model = Ordertablestatus::findModel($id);
      
        $content = $this->renderPartial('_order_view', ['model' => $model, 'isView' => TRUE]);
        return $this->render('view', [
                    'model' => $model,
                    'order_view' => $content,
        ]);
    }

    /**
     * Creates a new Order model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Order;

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
     * Updates an existing Order model.
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
     * Deletes an existing Order model.
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

    public function actionPlaceorder($table_no = 0) {
		$user_id=\yii::$app->user->identity->id;
		$restaurant = \common\models\base\Restaurant::findOne(['user_id' => $user_id]); 
		$restaurant_id = $restaurant['id'];  
		$date = Date('Y-m-d');
      /*  if (!$table_no) {
            \Yii::$app->getSession()->setFlash('error', 'You have to select table no before plcing order');
            return $this->redirect(Url::previous());
        }*/
        /* $table_model = Ordertablestatus::findOne(['restaurant_id' => $restaurant_id, 'table_no' => $table_no,'Date(date)'=>$date,'status'=>'Pending']);*/ 
	/*	if($table_model['table_no']){  */
			    //echo $table_model['table_no'];die;
			    $order_details= Json::decode($table_model['order_details'],'true'); 
			    $_SESSION['cart']['restaurant_id']      = $restaurant_id;
				$_SESSION['cart']['table_no']            = $table_no;
				$_SESSION['cart']['delivery_charge']     = $order_details['delivery_charge'];
				$_SESSION['cart']['subtotal']      = $order_details['subtotal'];
				$_SESSION['cart']['discounted_subtotal']      = $order_details['discounted_subtotal'];
				$_SESSION['cart']['tax']      = $order_details['tax'];
				$_SESSION['cart']['vat']      = $order_details['vat'];
				$_SESSION['cart']['service_charge']      = $order_details['service_charge'];
				$_SESSION['cart']['grand_total']      = $order_details['grand_total'];
				$_SESSION['cart']['dishes']      = $order_details['dishes'];
			
		/*	}else{
			  unset($_SESSION['cart']);
				$_SESSION['cart']['restaurant_id'] = $restaurant_id;
				$_SESSION['cart']['table_no']      = $table_no;
			} */
        $restaurant_model = \common\models\base\Restaurant::findOne(['id' => $restaurant_id]);
        $table_model = \common\models\base\Ordertable::findOne(['table_no' => $table_no]);
        return $this->render('place_order', [
                    'restaurant_model' => $restaurant_model,
                    'table_model' => $table_model,
        ]);
    }

    public function actionCheckout( $restaurant_id = 0) {
        $model = new Order;
        
        $restaurant_model = \common\models\base\Restaurant::findOne(['id' => $restaurant_id]);
        //$user_model = \common\models\base\Ordertablestatus::findOne(['id' => $user_id]);
        $addressModel = new \common\models\base\Address();
        $model->city = 'Ahmedabad';
        return $this->render('checkout', [
                    'restaurant_model' => $restaurant_model,
                    //'user_model' => $user_model,
                    'model' => $model,
                    'addressModel' => $addressModel
        ]);
    }
    
  public function actionSaveorder(){  
			  // insert a new row of data  Ordertabledetails  restaurant_id  
		$tablestatus = new Ordertablestatus();  
		 // $date = date('Y-m-d H:i:s');
		    $date  =date('Y-m-d');
		if (($_SESSION['cart']['table_no']!='') && ($_SESSION['cart']['subtotal']>0)){  
		$table_model = Ordertablestatus::findOne(['restaurant_id' => $_SESSION['cart']['restaurant_id'], 'table_no' => $_SESSION['cart']['table_no'],'DATE(date)'=>$date]);
			//$connection = \Yii::$app->db;
		   // $table_model = $connection->createCommand("SELECT table_no FROM tbl_ordertablestatus where restaurant_id='".$_SESSION['cart']['restaurant_id']."' and DATE(date)=('".$date1."')  and table_no='".$_SESSION['cart']['table_no']."' ")->queryAll();
			 if(!$table_model['table_no']){ 
					$tablestatus->restaurant_id = $_SESSION['cart']['restaurant_id']; 
					$tablestatus->table_no = $_SESSION['cart']['table_no'];
					$tablestatus->order_details = Json::encode($_SESSION['cart']);
					$tablestatus->date   = $date;
					$tablestatus->status        = 'Pending'; 
					$tablestatus->save(); 
					$response = array(
						'status' => 1,
						'message' => '<div class="alert alert-success">Order saved successfully</div>',
					);
					//echo $tablestatus->status;die;
            }else{ 
		        $table_model->order_details = Json::encode($_SESSION['cart']); 
		        $table_model->status = 'Pending'; 
		        $table_model->save();
				$response = array(
						'status' => 1,
						'message' => '<div class="alert alert-success">Order update successfully</div>',
					);
					//echo $tablestatus->table_no = $_SESSION['cart']['table_no'];die;
					//echo $tablestatus->status;die;
			}
            
        } else {
            $response = array(
                'status' => 0,
                'message' => '<div class="alert alert-warning">Could not save Order<div>',
            );
        }
        
        return Json::encode($response);
		exit;
       /* $this->redirect(['placeorder']);
		// update an existing row of data
		$customer = Customer::findOne(123);
		$customer->email = 'james@newexample.com';
		$customer->save();
	   */
	}

    public function actionCheckoutpricess() {
        $post_date = yii::$app->request->post();
        $session_date = $_SESSION['cart'];
       
        //CHECK IF COUPON HAS USED AND APPLICABLE TO EXPIRE THAT COUPON
        if (isset($session_date['coupon_data'])) {
            CouponsController::checkForCouponDesctive($session_date['coupon_data']['coupon_code']);
        }


        //PREPARE ORDER DATA
        $order_model = new Order();
        $restaurant_model = Restaurant::findOne(['id' => $session_date['restaurant_id']]);
        $order_model->load(yii::$app->request->post());
        $order_model->order_unique_id = $this->getRandomOrderID()."_D";
        
         
            $order_model->user_full_name     =  $post_date['Order']['user_full_name'];
            $order_model->mobile	         =  $post_date['Order']['mobile'];
            $order_model->delivery_type	     =  "Dining";
            $order_model->delivery_time	     =   date('Y-m-d H:i:s');
            $order_model->dob	             =   date("Y-m-d", strtotime($post_date['Order']['dob']));  
          //  $order_model->annversary_date	 =   date("Y-m-d", strtotime($post_date['Order']['annversary_date']));  
            $order_model->email              =   $post_date['Order']['email'];
            $order_model->comment            =   $post_date['Order']['comment']; 
            
         
        if (isset($session_date['coupon_data'])) {
            $order_model->coupon_code = $session_date['coupon_data']['coupon_code'];
            $order_model->discount_amount = $session_date['coupon_data']['discount_amount'];
        } else {
            $order_model->coupon_code = '';
            $order_model->discount_amount = '';
        }

        $order_model->order_items = count($session_date['dishes']);
        if (isset($session_date['combos'])) {
            $order_model->order_items = $order_model->order_items + count($session_date['combos']);
        }
        $order_model->tax = $session_date['tax'];
        $order_model->tax_text = $restaurant_model->tax . ' % tax';

        $order_model->vat = $session_date['vat'];
        $order_model->vat_text = $restaurant_model->vat . ' % vat';

        $order_model->service_charge = $session_date['service_charge'];
        if ($restaurant_model->scharge_type == 'Percentage') {
            $order_model->service_charge_text = $restaurant_model->service_charge . ' % service charge + ' . $session_date['delivery_charge'] . ' Rs delivery charge';
        } else {
            $order_model->service_charge_text = $restaurant_model->service_charge . ' Rs service charge + ' . $session_date['delivery_charge'] . ' Rs delivery charge';
        }
        $order_model->sub_total = $session_date['subtotal'];
        $order_model->grand_total = $session_date['grand_total'];

        $order_model->booking_time = date('Y-m-d H:i:s', time()); 
        $order_model->status = 'Completed';
        $order_model->placed_via = 'Water/Admin'; 
        $order_model->insert(false);
        $id = \Yii::$app->db->getLastInsertID();
       
       
        //PREPARE DISH DATA AND INSERT TO DB
        foreach ($session_date['dishes'] as $dishKey => $dishValue) {
            $dishes_order_model = new \common\models\base\OrderDish();
            $dish_model = Dish::findOne(['id' => $dishValue['id']]);
            $dishes_order_model->order_id = $order_model->id;
            $dishes_order_model->dish_id = $dishValue['id'];
            $dishes_order_model->dish_title = $dish_model->title;
            $dishes_order_model->dish_price = $dish_model->price;
            $dishes_order_model->dish_qty = $dishValue['qty'];
            $dishes_order_model->comment = $dishValue['comment'];
            $dishes_order_model->insert(FALSE);
            if (isset($dishValue['toppings'])) {
                foreach ($dishValue['toppings'] as $toppingKey => $toppingValue) {
                    $order_topping_model = new \common\models\base\OrderTopping();
                    $order_topping_model->order_id = $order_model->id;
                    $order_topping_model->dish_id = $dishValue['id'];
                    $order_topping_model->topping_id = $toppingValue['topping_id'];
                    $order_topping_model->price = $toppingValue['price'];
                    $order_topping_model->insert(FALSE);
                }
            }
        }
      

        if (isset($session_date['combos'])) {
            //PREPARE COMBO DATA AND INSERT TO DB
            foreach ($session_date['combos'] as $comboKey => $comboValue) {
                $combo_order_model = new \common\models\base\OrderCombo();
                $combo_model = Combo::findOne(['id' => $comboValue['id']]);
                $combo_order_model->order_id = $order_model->id;
                $combo_order_model->combo_id = $comboValue['id'];
                $combo_order_model->combo_qty = $comboValue['qty'];
                $combo_order_model->price = $combo_model->price;
                $combo_order_model->insert(FALSE);
                foreach ($combo_model->comboDishes as $comboDishesKey => $comboDishesValue) {

                    $order_combo_dish_model = new \common\models\base\OrderComboDish();
                    $order_combo_dish_model->order_combo_id = $combo_order_model->id;
                    $order_combo_dish_model->dish_id = $comboDishesValue->dish_id;
                    $order_combo_dish_model->dish_qry = $comboDishesValue->dish_qty;
                    $order_combo_dish_model->insert(FALSE);
                }
            }
        }

   
        /*         * * SENDING SMSs ** */
        $this->sendOdderSms($id); 
       
        /*         * * SENDING EMAILs ** */
        if($post_date['Order']['email']!=''){
			
			$this->sendOrderEmails($id);
       
         }
         
        unset($_SESSION['cart']);
       \Yii::$app->getSession()->setFlash('success', "Order has been completed. Bon Appetit!"); 
       // return $this->redirect(array('takeorder/index')); 
        $model = $this->findOrder($order_model->id);
        $content = $this->renderPartial('_order_view', ['model' => $model, 'isView' => TRUE]);
        
         return $this->render('view', [
                    'model' => $this->findOrder($order_model->id),
                     'order_view' => $content,
        ]); 
        
    }
     public function actionOutputinfo($id, $act) {
		
        $model = $this->findOrder($id);  
        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('_order_view', ['model' => $model]); 
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
    
    
     private function sendOrderEmails($id) {
        $order = $this->findOrder($id); 
        /** EMAIL TO CUSTOMER OWNER * */
        $customer_email_ststus = \Yii::$app->mailer->compose('order/newOrder', ['model' => $order])
                ->setFrom([\Yii::$app->params['adminEmail'] => \Yii::$app->name . ' Admin'])
                ->setTo($order->email)
                ->setSubject('Your order details at ' . \Yii::$app->name)
                ->send();

 /** EMAIL TO Admin * */
$customer_email_ststus = \Yii::$app->mailer->compose('order/newOrder', ['model' => $order])
                ->setFrom([\Yii::$app->params['adminEmail'] => \Yii::$app->name . ' Admin'])
                ->setTo($app->params['adminEmail'])
                ->setSubject('Your order details at ' . \Yii::$app->name)
                ->send();


        return;
    }

    
    
    protected function findOrder($id) {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }
    
    
    

    public function actionAdddish($id, $type, $dish_topping_str = null,$type1=null,$items=null) {
		 
        if (!isset($_SESSION['cart']['dishes'])) {
            $_SESSION['cart']['dishes'] = array();
        }
         
        if ($type == 'dish') {
            $dish = Dish::findOne(['id' => $id]);
            $dish_arr = array('id' => $dish->id, 'price' => $dish->price); 
            $dishes_session = $_SESSION['cart']['dishes'];
           
            if (!($this->checkDishPresent($dish->id, $dishes_session) < 0)) {
               
               if(!$type1){
                $dishes_session[$this->checkDishPresent($dish->id, $dishes_session)]['qty'] = $dishes_session[$this->checkDishPresent($dish->id, $dishes_session)]['qty'] + 1;
			    }else{
					 
				  $dishes_session[$this->checkDishPresent($dish->id, $dishes_session)]['qty'] = $items;	
				}
				
            } else {
                $dish_arr['qty'] = 1;
                $dish_arr['comment'] = '';
                array_push($dishes_session, $dish_arr);
            } 
            $_SESSION['cart']['dishes'] = $dishes_session;
           //  print_r($dishes_session);die;
        } else if ($type == 'dish_with_topping') {
            $dish = Dish::findOne(['id' => $id]);
            $dish_arr = array('id' => $dish->id, 'price' => $dish->price);
            $dishes_session = $_SESSION['cart']['dishes'];
            $dish_arr['qty'] = 1;
            $row_dish_topping_arr = explode('^_^', $dish_topping_str);
            $topping_arr = array();
            foreach ($row_dish_topping_arr as $key => $value) {
                $dish_topping_model = DishTopping::findOne(['id' => $value]);
                array_push($topping_arr, array('dish_topping_id' => $value, 'price' => $dish_topping_model->price, 'topping_id' => $dish_topping_model->topping_id));
            }
            $dish_arr['toppings'] = $topping_arr;
            $dish_arr['comment'] = '';
            array_push($dishes_session, $dish_arr);
            $_SESSION['cart']['dishes'] = $dishes_session;
        } else if ($type == 'combo') {
            if (!isset($_SESSION['cart']['combos'])) {
                $_SESSION['cart']['combos'] = array();
            }
            $combo = Combo::findOne(['id' => $id]);
            $combo_arr = array('id' => $combo->id, 'price' => $combo->price);
            $combos_session = $_SESSION['cart']['combos'];
            if (!($this->checkDishPresent($combo->id, $combos_session) < 0)) {
                $combos_session[$this->checkDishPresent($combo->id, $combos_session)]['qty'] = $combos_session[$this->checkDishPresent($combo->id, $combos_session)]['qty'] + 1;
            } else {
                $combo_arr['qty'] = 1;
                array_push($combos_session, $combo_arr);
            }
            $_SESSION['cart']['combos'] = $combos_session;
        }

        return $this->actionRefreshcart();
    }

    public function actionRemovedish($id, $type, $dish_topping_str = null) {
        if (!isset($_SESSION['cart']['dishes'])) {
            $_SESSION['cart']['dishes'] = array();
        }
        $dishes_session = $_SESSION['cart']['dishes'];
        if ($type == 'dish') {
            $dish_key = $this->checkDishPresent($id, $dishes_session);
            if (!($dish_key < 0)) {
                $dishes_session[$dish_key]['qty'] = $dishes_session[$dish_key]['qty'] - 1;
                if ($dishes_session[$dish_key]['qty'] < 1) {
                    unset($dishes_session[$dish_key]);
                }
            }
            $_SESSION['cart']['dishes'] = $dishes_session;
        } else if ($type == 'combo') {
            if (!isset($_SESSION['cart']['combos'])) {
                $_SESSION['cart']['combos'] = array();
            }
            $combos_session = $_SESSION['cart']['combos'];
            $combo_key = $this->checkDishPresent($id, $combos_session);
            if (!($combo_key < 0)) {
                $combos_session[$combo_key]['qty'] = $combos_session[$combo_key]['qty'] - 1;
                if ($combos_session[$combo_key]['qty'] < 1) {
                    unset($combos_session[$combo_key]);
                }
            }
            $_SESSION['cart']['combos'] = $combos_session;
        }
        return $this->actionRefreshcart();
    }

    public function actionRefreshcart() { 
        //$table_model = \common\models\base\Ordertable::findOne(['table_no' => $_SESSION['cart']['table_no']]);
        //return $this->renderAjax('order_cart', ['table_model' => $table_model]);
        $restaurant_model = \common\models\base\Restaurant::findOne(['id' => $_SESSION['cart']['restaurant_id']]);
        return $this->renderAjax('order_cart', ['restaurant_model' => $restaurant_model]);
    }

    public function actionAddcomment() {
        $id = $_POST['id'];
        $comment = $_POST['comment'];
        $dishes_session = $_SESSION['cart']['dishes'];
        if (!($this->checkDishPresent($id, $dishes_session) < 0)) {
            $dishes_session[$this->checkDishPresent($id, $dishes_session)]['comment'] = trim($comment);
            $_SESSION['cart']['dishes'] = $dishes_session;
            $response = array(
                'status' => 1,
                'message' => 'Comment added successfully',
            );
        } else {
            $response = array(
                'status' => 0,
                'message' => 'Could not add comment',
            );
        }
        return Json::encode($response);
    }
 

    public function actionEmptycart() {
        $restaurant_id = $_SESSION['cart']['restaurant_id'];
        unset($_SESSION['cart']);
        $_SESSION['cart']['restaurant_id'] = $restaurant_id;
        return $this->actionRefreshcart();
    }

    public function actionChangestatus() {
        $sms_message = "";
        if (!isset($_POST['editableKey'])) {
            echo \yii\helpers\Json::encode(['output' => 'error', 'message' => 'Invalid Id']);
            return;
        }
        $model = Order::findOne(['id' => $_POST['editableKey']]); //1063
        if ($model) {
            if (isset($_POST['hasEditable'])) {
                if ($model->load($_POST)) {
                    $model->status = $_POST['Order'][$_POST['editableIndex']]['status'];
                    $status_reason = $_POST['Order']['order_status_change_reason'];
                    $model->order_status_change_reason = Yii::$app->params['order_cancle_reasons'][$status_reason];
                    $value = $model->status;
                    $model->order_status_change_datetime = date('Y-m-d H:i:s', time());
                    if ($model->status == 'Rejected') {
                        $model->accept_reject_datetime = date('Y-m-d H:i:s', time());
                        if ($status_reason == "other") {
                            $model->order_status_change_reason = $_POST['status_other_reason'];
                        }

                        $sms_message = " Your Order(Order ID " . $model->order_unique_id . ") has been cancelled due to " . $model->order_status_change_reason;
                        /** EMAIL TO CUSTOMER  * */
                        $customer_email_ststus = \Yii::$app->mailer->compose('order/cancelOrder', ['model' => $model, 'reason' => $model->order_status_change_reason])
                                ->setFrom([\Yii::$app->params['adminEmail'] => \Yii::$app->name . ' Admin'])
                                ->setTo($model->email)
                                ->setSubject('Regarding your order at ' . \Yii::$app->name)
                                ->send();

                        $customer_email_ststus = \Yii::$app->mailer->compose('order/cancelOrder', ['model' => $model, 'reason' => $model->order_status_change_reason])
                                ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' Admin'])
                                ->setTo(\Yii::$app->params['supportEmail'])
                                ->setSubject('Order Cancellation')
                                ->send();
                    } else {
                        $model->accept_reject_datetime = date('Y-m-d H:i:s', time());
                        if ($model->status == "Approved") {
                            $sms_message = " Your Order(Order ID " . $model->order_unique_id . ") has been confirm at " . \Yii::$app->name;
                            /** EMAIL TO CUSTOMER  * */
                            $customer_email_ststus = \Yii::$app->mailer->compose('order/confirmOrder', ['model' => $model])
                                    ->setFrom([\Yii::$app->params['adminEmail'] => \Yii::$app->name . ' Admin'])
                                    ->setTo($model->email)
                                    ->setSubject('Regarding your order at ' . \Yii::$app->name)
                                    ->send();
                        }
                        $model->order_status_change_reason = "";
                    }
                    if ($model->status == "Completed") {
                        $model->complete_datetime = date('Y-m-d H:i:s', time());
                    }
                    $model->save(FALSE);

                    if ($model->status == "Completed") {

                        $affiliate_orders_count = Order::find()->where(['affiliate_order_id' => $model->affiliate_order_id, 'status' => 'Completed'])->limit(3)->count();
                        if ($affiliate_orders_count == 3 AND trim($model->affiliate_order_id) != "") {
                            $affiliate_orders_average = Order::find()->where(['affiliate_order_id' => $model->affiliate_order_id, 'status' => 'Completed'])->limit(3)->average('grand_total');
                            $affiliate_order = $this->findModelByUniqueId($model->affiliate_order_id);
                            if ($affiliate_orders_average >= $affiliate_order->sub_total) {
                                //GENRATE COUPON CODE FOR USER
                                $coupon_model = new \common\models\Coupons();
                                $coupon_model->code = $this->getRandomCoupinCode();
                                $coupon_model->coupon_key = "Share order link to 3 friends and get one order free";
                                $coupon_model->title = "Discount for affiliate order ID " . $model->affiliate_order_id;
                                $coupon_model->description = "Discount for affiliate order ID " . $model->affiliate_order_id;
                                $coupon_model->type = "Personal";
                                $coupon_model->coupon_perameter = Json::encode(array(
                                            'user_id' => $affiliate_order->user_id,
                                            'validity_days' => 30,
                                            'discount_value' => $affiliate_order->grand_total,
                                ));
                                $coupon_model->notify = "No";
                                $coupon_model->status = "Active";
                                $coupon_model->created_on = date('Y-m-d H:i:s', time());
                                $coupon_model->expired_on = date('Y-m-d H:i:s',strtotime(date("Y-m-d H:i:s", time()) . " + 365 day"));
                                $coupon_model->save(FALSE);

                                /** EMAIL TO CUSTOMER INFORMING NEW COUPON FOR HIM * */
                                $customer_email_ststus = \Yii::$app->mailer->compose('coupons/order_affiliate', ['model' => $affiliate_order, 'coupon_code' => $coupon_model->code])
                                        ->setFrom([\Yii::$app->params['adminEmail'] => \Yii::$app->name . ' Admin'])
                                        ->setTo($affiliate_order->email)
                                        ->setSubject('New Coupon for you from ' . \Yii::$app->name)
                                        ->send();
                                /** SMS TO CUSTOMER INFORMING NEW COUPON FOR HIM * */
                                $sms = new Sms();
                                $sms->send($affiliate_order->mobile, "Congratulations! You have got coupon code worth Rs. " . $affiliate_order->grand_total . " from " . \Yii::$app->name . ". Coupon code : " . $coupon_model->code . ". Hurry, Order Now!");
                            }
                        }
                    }

                    if (trim($sms_message)) {
                        $sms = new Sms();
                        $sms->send($model->mobile, $sms_message);
                    }

                    echo \yii\helpers\Json::encode(['output' => $value, 'message' => '']);
                } else {
                    echo \yii\helpers\Json::encode(['output' => 'error', 'message' => '']);
                }
                return;
            }
        } else {
            echo \yii\helpers\Json::encode(['output' => 'error', 'message' => '']);
        }
        return;
    }

   

    private function sendOdderSms($id) {
		 
        $order = $this->findModel($id);
        
        $sms = new Sms();

        /*         * * SMS FOR CUSTOMER ** */
        $messageCust = "Welcome to khayejao.com. Your order has been placed successfully. Order ID is : " . $order->order_unique_id . ". You will receive notification once the order is confirmed by the Admin.";

        /*         * ** SMS FOR ADMIN, RESTAURANT AND TELECALLER *** */
        $messagecommon = "Order ID: " . $order->order_unique_id . ", Restaurant: " . $order->restaurant->title . "\n \nDate: " . Yii::$app->formatter->asDatetime($order->booking_time) . ", Type: " . $order->delivery_type . " \n \nName: " . $order->user_full_name . " \nPhone: " . $order->mobile . " \nAddress: " . $order->address_line_1 . ($order->address_line_2 ? ", " . $order->address_line_2 : '') . ", " . $order->area0->area_name . ", " . $order->city . ", " . $order->pincode . " \n";
        if ($order->delivery_type == 'Pickup') {
            $messagecommon .= " \nItems: ";
        } else {
            $messagecommon .= "Delivery Time: " . Yii::$app->formatter->asDatetime($order->delivery_time) . " \n\nItems: ";
        }

        if (count($order->orderDishes) > 0) {
            foreach ($order->orderDishes as $orderDishes) {
                $productTotal = $orderDishes->dish_price * $orderDishes->dish_qty;

                $order_toppings = OrderTopping::findAll(['dish_id' => $orderDishes->dish_id, 'order_id' => $order->id]);
                foreach ($order_toppings as $orderToppingKey => $orderToppingValue) {
                    $productTotal = $productTotal + $orderToppingValue->price;
                }
                $messagecommon.= $orderDishes->dish_qty . " x " . $orderDishes->dish_title . (count($order_toppings) > 0 ? " (Including toppings)" : '') . " = " . number_format($productTotal, 2) . " \n";
            }
        }

        if (count($order->orderCombos) > 0) {
            foreach ($order->orderCombos as $orderCombo) {
                $productTotal = $orderCombo->price * $orderCombo->combo_qty;
                $messagecommon.= $orderCombo->combo_qty . " x " . $orderCombo->combo->title . " = " . number_format($productTotal, 2) . " \n";
            }
        }

        $messagecommon.="Discount(" . $order->discount_text . "): - " . number_format($order->discount_amount, 2) . "\n";
        $messagecommon.="Taxes(" . $order->tax_text . "): + " . number_format($order->tax, 2) . "\n";
        $messagecommon.="VAT(" . $order->vat_text . "): + " . number_format($order->vat, 2) . "\n";
        $messagecommon.="Service Charge(" . $order->service_charge_text . "): + " . number_format($order->service_charge, 2) . "\n";

        $messagecommon.="Sub Total = " . number_format($order->sub_total, 2) . "\n";
        $messagecommon.="Total = " . number_format($order->grand_total, 2) . "\n";
        $messagecommon.="Note: " . $order->comment . "\n \n";
        $messagecommon.="Please call 7046545454 for any queries.";

        $sms->send($order->mobile, $messageCust);
        $sms->send($order->restaurant->sms_number, $messagecommon);
        $sms->send("7405544551", $messagecommon);
        $sms->send("9033790409", $messagecommon);
        
    }

   
    private function getRandomOrderID() {
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $res = "";
        for ($i = 0; $i < 3; $i++) {
            $res .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return 'ORD' . $res . time();
    }

    private function checkDishPresent($id, $arr) {
        foreach ($arr as $key => $value) {
            if ($value['id'] == $id) {
                return $key;
            }
        }
        return -1;
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Order the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id) { 
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }

    protected function findModelByUniqueId($id) {
        if (($model = Order::findOne(['order_unique_id' => $id])) !== null) {
            return $model;
        }
        return;
    }

    private function getRandomCoupinCode() {
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $res = "";
        for ($i = 0; $i < 8; $i++) {
            $res .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $res;
    }

}
