<?php

namespace backend\controllers;

use common\models\Order;
use common\models\OrderSearch;
use common\models\Settings;
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
use common\models\base\AddressL;
use common\models\base\Restaurant;
use kartik\mpdf\Pdf;
use common\components\Sms;
use common\models\base\LogisticUser;
use common\models\OrderTopping;
use common\models\DeliveryBoyOrder;
use common\models\base\DeliveryBoy;
use yii\helpers\ArrayHelper;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends Controller {

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
                       'actions' => ['index', 'view', 'create', 'update', 'delete', 'placeorder', 'adddish', 'refreshcart', 'removedish', 'emptycart', 'checkout', 'checkoutpricess', 'addcomment', 'selectaddress', 'changestatus', 'outputinfo','neworder','newordershow','orderviewstaus', 'orderchangestatus','manuallyassign'],
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
        $resolved = \Yii::$app->request->resolve();
        $resolved[1]['_pjax'] = null;
        $url = Url::to(array_merge(['/' . $resolved[0]], $resolved[1]));
        \Yii::$app->session['__crudReturnUrl'] = Url::previous();
        Url::remember($url);
        Tabs::rememberActiveState();
        $model = $this->findModel($id);
        $connection = \Yii::$app->db;
        $command = $connection->createCommand('UPDATE tbl_order SET view=1 WHERE id='.$id.'');
        $command->execute(); 
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

    public function actionPlaceorder($user_id = 0, $restaurant_id = 0,$user_type='') {
        if (!$restaurant_id) {
            \Yii::$app->getSession()->setFlash('error', 'You have to select restaurant before plcing order');
            return $this->redirect(Url::previous());
        }
        unset($_SESSION['cart']);
        $_SESSION['cart']['restaurant_id'] = $restaurant_id;
        $_SESSION['cart']['user_id'] = $user_id;
        $restaurant_model = \common\models\base\Restaurant::findOne(['id' => $restaurant_id]);
        \Yii::$app->session['user_type']=$user_type;
        if($user_type=='L'){ 
			
			   $user_model = \common\models\base\LogisticUser::findOne(['id' => $user_id]);
			   
		 }else{ 
				  
              $user_model = \common\models\base\User::findOne(['id' => $user_id]);
	     }
        return $this->render('place_order', [
                    'restaurant_model' => $restaurant_model,
                    'user_model' => $user_model,
        ]);
    }
    
    
    
    public function actionManuallyassign($user_id = 0, $order_id = 0) {
        if (!$user_id) {
            \Yii::$app->getSession()->setFlash('error', 'You have to select delevery boy before assign order');
            return $this->redirect(Url::previous());
        }
         
           $this->sendpushmessage($order_id,$user_id);
         
       return $this->redirect(Url::previous());
    }

    public function actionCheckout($user_id = 0, $restaurant_id = 0){
        $model = new Order;
        if (!$restaurant_id) {
            \Yii::$app->getSession()->setFlash('error', 'You have to select restaurant before plcing order');
            return $this->redirect(Url::previous());
        }
        $restaurant_model = \common\models\base\Restaurant::findOne(['id' => $restaurant_id]);
        
        
        $user_type    =  \Yii::$app->session['user_type'];
        if($user_type=="L"){ 
			
		  $user_model = \common\models\base\LogisticUser::findOne(['id' => $user_id]); 
		}else{
		    $user_model = \common\models\base\User::findOne(['id' => $user_id]);
		}
		
        $addressModel = new \common\models\base\Address(); 
        $model->city = $restaurant_model->city;
      
        return $this->render('checkout', [
                    'restaurant_model' => $restaurant_model,
                    'user_model' => $user_model,
                    'model' => $model,
                    'addressModel' => $addressModel
        ]);
    }

    public function actionCheckoutpricess() {
		$user_type= \Yii::$app->session['user_type'];  
		$post_date = yii::$app->request->post(); 
		$session_date = $_SESSION['cart'];
      
	    $admin_delivery_charges = Settings::findOne(['key' => 'delivery_charges', 'status' => 'Active']);
	    
		if($admin_delivery_charges){
			$delivery_charges  =($restaurant_area_model->delivery_charge<$admin_delivery_charges->value)?$admin_delivery_charges->value:$restaurant_area_model->delivery_charge;
		    $session_date['delivery_charge'] =$delivery_charges;
		}
       
        $connection = \Yii::$app->db;
        //CHECK IF COUPON HAS USED AND APPLICABLE TO EXPIRE THAT COUPON
        if (isset($session_date['coupon_data'])) {
            CouponsController::checkForCouponDesctive($session_date['coupon_data']['coupon_code']);
        }


        //PREPARE ORDER DATA
        $order_model = new Order(); 
        $restaurant_model = Restaurant::findOne(['id' => $session_date['restaurant_id']]);
        $order_model->load(yii::$app->request->post()); 
        
        if($post_date['Order']['order_to']=='restaurant'){
			
			$order_model->order_unique_id = $this->getRandomOrderID()."_L"; 
			
	    }else{
			
		    $order_model->order_unique_id = $this->getRandomOrderID();
		}
		
        if ($post_date['Order']['user_id']) { 
         
             $address_model = Address::findOne(['id' => yii::$app->request->post('address_id')]);
		 
            $order_model->address_line_1 = $address_model->address_line_1;
            $order_model->address_line_2 = $address_model->address_line_2;
            $order_model->area = $address_model->area;
            $order_model->city = $address_model->city;
            $order_model->pincode = $address_model->pincode;
        }else{
			
			if($user_type=="L"){  
				
				 $address_model = AddressL::findOne(['id' => yii::$app->request->post('address_id')]);  
				 $order_model->address_line_1 = $address_model->address_line_1;
				 $order_model->address_line_2 = $address_model->address_line_2;
				 $order_model->area = $address_model->area;
				 $order_model->city = $address_model->city;
				 $order_model->pincode = $address_model->pincode;
				 
			 } 
			 
           
			
			/* insert on logistic member table */
			 $luser =   new \common\models\LogisticUser(); 
			 $L_user  = $luser->findByEmail($post_date['Order']['email']); 
			 if(!$L_user['email']){
				 $user_full_name = explode(" ",$post_date['Order']['user_full_name']);
				 $first_name =$user_full_name['0'];
				 $last_name  =($user_full_name['1']!=''? $user_full_name['1']:' '); 
				 $restaurant_ids = $post_date['Order']['restaurant_id'].","; 
				 if($post_date['Order']['order_to']=='khayejao') $restaurant_ids= "-1".",";
				 $date = date('Y-m-d H:i:s');
				
				 $connection->createCommand()->insert('tbl_logistic_user', ['restaurant_id' => $restaurant_ids,'first_name'=>$first_name,'last_name'=>$last_name,'mobile_no' => $post_date['Order']['mobile'],'email' => $post_date['Order']['email'],'user_to' => $post_date['Order']['order_to'],'type' =>  'guest','created_at' => strtotime($date),'updated_at' =>  strtotime($date),])->execute();
				 $id = Yii::$app->db->getLastInsertID();
		     }else{
				 $flage   =0;
				 $res_ids = explode(",",$L_user['restaurant_id']); 
				 
				 foreach($res_ids as $vals){   
					 
					 if($vals==$post_date['Order']['restaurant_id']){
						 $flage =1;
					 } 
				 }
				 
				 if(!$flage){ 
					 array_push($res_ids, $post_date['Order']['restaurant_id']); 
					 $res_ids= array_filter($res_ids); 
					 $res_ids_str = implode(',', $res_ids);   
					 $connection->createCommand()
					 ->update('tbl_logistic_user', ['restaurant_id' => $res_ids_str], 'email="'.$post_date['Order']['email'].'"')
					 ->execute();  
					 
				 }else{  
					 if($post_date['Order']['order_to']=='khayejao') $post_date['Order']['restaurant_id']= '-1'; 
					 array_push($res_ids, $post_date['Order']['restaurant_id']); 
					 $res_ids= array_unique(array_filter($res_ids)); 
					 $res_ids_str = implode(',', $res_ids);   
					 $connection->createCommand()
					 ->update('tbl_logistic_user', ['restaurant_id' => $res_ids_str], 'email="'.$post_date['Order']['email'].'"')
					 ->execute();
				 } 
				 
			   
			 }
			/* end  */
		}

        if (yii::$app->request->post('delivery_time_radio') == "Now") {
            $order_model->delivery_time = date('Y-m-d H:i:s', time());
        } else if (yii::$app->request->post('delivery_time_radio') == 'Pre-Order') {
            $order_model->delivery_time = date('Y-m-d H:i:s', strtotime(yii::$app->request->post('delivery_date_input') . ' ' . yii::$app->request->post('delivery_time_input') . ':00'));
        }
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
        $order_model->sub_total    = $session_date['subtotal'];
        $order_model->grand_total  = $session_date['grand_total'];

        $order_model->booking_time = date('Y-m-d H:i:s', time());
        $order_model->order_ip     = yii::$app->request->userIP;
        $order_model->status       = 'Placed';
         $order_model->order_to    = $post_date['Order']['order_to'];
        $order_model->placed_via   = 'Telecaller/Admin';
        
        $order_model->insert();
       
        if (!empty($order_model->errors)) {
            $keys = array_keys($order_model->errors);
            \Yii::$app->getSession()->setFlash('error', $order_model->errors[$keys[0]][0]);
            return $this->redirect(Url::to(['order/checkout', 'restaurant_id' => $_SESSION['cart']['restaurant_id'], 'user_id' => $_SESSION['cart']['user_id']]));
        }

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
     //   $this->sendOdderSms($order_model->id);

        /*         * * SENDING EMAILs ** */ 
				
				 $this->sendOrderEmails($order_model->id);
				
			 
        unset($_SESSION['cart']);
        \Yii::$app->getSession()->setFlash('success', "Order has been placed. Bone Appetit!");
        return $this->redirect(array('user/index'));
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

    public function actionSelectaddress() {
        $id = $_POST['id'];
        $restaurant_area_model = RestaurantArea::findOne(['restaurant_id' => $_SESSION['cart']['restaurant_id'], 'area_id' => $id]);
		
        if ($_POST['delivery_type'] == 'Delivery') {
            $_SESSION['cart']['delivery_charge'] = $restaurant_area_model->delivery_charge;
            $response = array(
                'status' => 1,
                'message' => 'Delivery charge added successfully'
            );
        } else {
            $_SESSION['cart']['delivery_charge'] = 0;
            $response = array(
                'status' => 1,
                'message' => 'Delivery charge not applicable'
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
                        /* logistic push notification  Telecaller/Admin*/
                        if($model->placed_via=="Telecaller/Admin"){
							$suc = $this->sendpushmessage($model->id);
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
                      //  $sms->send($model->mobile, $sms_message);
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
    
     /* send push notification */
    private function  sendpushmessage($order_id=0,$db_id=0){
		if(!$db_id){
		 $unit  = 1.609344;
		 $distance=5;
		 $model =  Order::findOne(['id'=>$order_id]);
		 $restaurant_model = \common\models\base\Restaurant::findOne(['id' => $model->restaurant_id]);
		 $latitude  =  $restaurant_model->latitude;
		 $longitude =  $restaurant_model->longitude; 
		 $connection = \Yii::$app->db;
		 $query = "SELECT device_id , "
                    . "ROUND(" . $unit . " * 3956 * acos( cos( radians('$latitude') ) * "
                    . "cos( radians(latitude) ) * "
                    . "cos( radians(longitude) - radians('$longitude') ) + "
                    . "sin( radians('$latitude') ) * "
                    . "sin( radians(latitude) ) ) ,8) as distance "
                    . "from tbl_delivery_boy " 
                    . "where is_active = 1 and "  
                    . "ROUND((" . $unit . " * 3956 * acos( cos( radians('$latitude') ) * "
                    . "cos( radians(latitude) ) * "
                    . "cos( radians(longitude) - radians('$longitude') ) + "
                    . "sin( radians('$latitude') ) * "
                    . "sin( radians(latitude) ) ) ) ,8) <= $distance "
                    . "order by distance "
                    . "LIMIT 1";
			$devices = $connection->createCommand($query)->queryAll(); 
			$registerId = ArrayHelper::getColumn($devices, 'device_id');
			
	 }else{
		 
		   $devices = \common\models\base\DeliveryBoy::findOne(['id' => $db_id]);
           $registerId = ArrayHelper::getColumn($devices, 'device_id');
         
	 }
       // echo $model->title;die;
/*
        $devices1 = \common\models\Device::find()->where(['device_platform' => 'ios'])->all();
        $iosregisterId = ArrayHelper::getColumn($devices1, 'device_id'); 
        */
        if ($registerId) {
            define('API_ACCESS_KEY', 'AIzaSyCsdPAdHz9AUC7p4jzpjKl3A1g1543tHb8'); //OLD : AIzaSyDm5Nce1hTEpWXh6vW4Pfsps-bTU2449Dw
            $registrationIds = $registerId;
            $msg = array( 
                'message' => $order_id, 
            );
            	 
            $fields = array(
                'registration_ids' => $registrationIds,
                'data' => $msg
            );

            $headers = array
                (
                'Authorization: key=' . API_ACCESS_KEY,
                'Content-Type: application/json'
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://android.googleapis.com/gcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);

            curl_close($ch);
        }
/* for ios 
        if ($iosregisterId) {
           

            $ctx = stream_context_create();
            stream_context_set_option($ctx, 'ssl', 'local_cert', 'ck.pem');
            stream_context_set_option($ctx, 'ssl', 'passphrase', "1234");

            $fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);

            //if (!$fp)
            //exit("Failed to connect amarnew: $err $errstr" . PHP_EOL);
            //echo 'Connected to APNS' . PHP_EOL;
            // Create the payload body
            $month = date("F, Y", strtotime($month));
            $body['aps'] = array(
                'badge' => +1,
                'alert' => 'KhayeJao Discount Coupon! '.$model->title,
                'sound' => 'default'
            );

            $payload = json_encode($body);

            foreach ($iosregisterId as $iosregisterIdKey => $iosregisterIdValue) {
                // Build the binary notification
                $msg = chr(0) . pack('n', 32) . pack('H*', $iosregisterIdValue) . pack('n', strlen($payload)) . $payload;

                // Send it to the server
                $result = fwrite($fp, $msg, strlen($msg));
            }



            // Close the connection to the server
            fclose($fp);


           
        } */


        return Json::encode(array('result'=>'success'));
    }
    /* end */

    public function actionOutputinfo($id, $act) {
        $model = $this->findModel($id);

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




  public function actionOrderchangestatus($id='', $act='') {
	  if(!$id){
		  $id=$_POST['id'];
		  $msg=$_POST['msg'];  
		  $act=$_POST['act'];
	  }
        $model = $this->findModel($id); 
        
        if ($act == 'accept') {
			
		   $model->accept_reject_datetime = date('Y-m-d H:i:s', time()); 
           $sms_message = " Your Order(Order ID " . $model->order_unique_id . ") has been confirm at " . \Yii::$app->name;
                            /** EMAIL TO CUSTOMER  * */
                            $customer_email_ststus = \Yii::$app->mailer->compose('order/confirmOrder', ['model' => $model])
                                    ->setFrom([\Yii::$app->params['adminEmail'] => \Yii::$app->name . ' Admin'])
                                    ->setTo($model->email)
                                    ->setSubject('Regarding your order at ' . \Yii::$app->name)
                                    ->send();
                                    
                                     /* logistic push notification  Telecaller/Admin*/
                        if($model->placed_via=="Telecaller/Admin"){
							$suc = $this->sendpushmessage($model->id);
							 
						}
                        $model->order_status_change_reason = ""; 
                        $model->status='Approved';
        }
        
        if ($act == 'reject') {
			
           $model->accept_reject_datetime = date('Y-m-d H:i:s', time()); 
           
                      if($_POST['cmsg']){
						  $_POST['msg'] =$_POST['cmsg'];
					  }
                       $model->order_status_change_reason = $_POST['msg'];  
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
                     $model->status='Rejected';
        }
        if (trim($sms_message)){
                        $sms = new Sms();
                        $sms->send($model->mobile, $sms_message);
                    }
     $model->save(FALSE);  
       
         $this->redirect(Url::previous());
    }

    private function sendOdderSms($id) {
        $order = $this->findModel($id);
        $sms = new Sms();

        /*         * * SMS FOR CUSTOMER ** */
        $messageCust = "Welcome to khayejao.com. Your order has been placed successfully. Order ID is : " . $order->order_unique_id . ". You will receive notification once the order is confirmed by the Admin.";

        /*         * ** SMS FOR ADMIN, RESTAURANT AND TELECALLER *** */
        $messagecommon = "New order placed \n \n Order ID: " . $order->order_unique_id . ", Restaurant: " . $order->restaurant->title . "\n \nDate: " . Yii::$app->formatter->asDatetime($order->booking_time) . ", Type: " . $order->delivery_type . " \n \nName: " . $order->user_full_name . " \nPhone: " . $order->mobile . " \nAddress: " . $order->address_line_1 . ($order->address_line_2 ? ", " . $order->address_line_2 : '') . ", " . $order->area0->area_name . ", " . $order->city . ", " . $order->pincode . " \n";
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

    private function sendOrderEmails($id) {
       $order = $this->findModel($id);
       $session_date = $_SESSION['cart']; 
       $restaurant_model = Restaurant::findOne(['id' => $session_date['restaurant_id']]); 
       $order_to=$order->order_to;
        /** EMAIL TO ADMIN * */
        $admin_email_ststus = \Yii::$app->mailer->compose('order/newOrder', ['model' => $order])
                ->setFrom([$order->email => $order->user_full_name])
                ->setTo(\Yii::$app->params['adminEmail'])
                ->setSubject('New Order at ' . \Yii::$app->name . " - " . $order->order_unique_id)
                ->send();
        /** EMAIL TO RESTAURANT OWNER * */
        $restaurant_email_ststus = \Yii::$app->mailer->compose('order/newOrder', ['model' => $order])
                ->setFrom([$order->email => $order->user_full_name])
                ->setTo($order->restaurant->user->email)
                ->setSubject('New Order form ' . \Yii::$app->name . " - " . $order->order_unique_id)
                ->send();
        /** EMAIL TO CUSTOMER OWNER * */ 
        if($order_to=='khayejao' || $order_to=='restaurant' ){
			
			if($order->restaurant->user->is_sendemail=='Yes'){
				
			      if($order_to=='restaurant' && $restaurant_model->is_sendemail=='1'){  
						$customer_email_ststus = \Yii::$app->mailer->compose('order/newOrder', ['model' => $order])
						->setFrom([\Yii::$app->params['adminEmail'] => \Yii::$app->name . ' Admin'])
						->setTo($order->email)
						->setSubject('Your order details at ' . \Yii::$app->name)
						->send();
					}elseif($order_to=='khayejao'){ 
						$customer_email_ststus = \Yii::$app->mailer->compose('order/newOrder', ['model' => $order])
						->setFrom([\Yii::$app->params['adminEmail'] => \Yii::$app->name . ' Admin'])
						->setTo($order->email)
						->setSubject('Your order details at ' . \Yii::$app->name)
						->send();
					}
					
			  }			 
                
			}
                
        return;
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
     public function actionNeworder(){
		  $date = Date('Y-m-d');//telecaller
		 if(yii::$app->user->identity->type=='admin' || yii::$app->user->identity->type=='restaurant' || yii::$app->user->identity->type=='telecaller'){
			 
					      if(yii::$app->user->identity->type=='restaurant'){ 
							  $res_id      =  yii::$app->user->identity->id;
							  $restaurant_model = \common\models\base\Restaurant::findOne(['user_id' => $res_id]);  
					          $order_model = \common\models\base\Order::findAll(['restaurant_id' =>$restaurant_model->id,'status' => 'Placed','close' => '0','placed_via'=>'Telecaller/Admin']); 
					          $order_model_today = \common\models\base\Order::findAll(['status' => 'Approved','close' => '0','placed_via'=>'Telecaller/Admin','Date(accept_reject_datetime)'=>$date]);
					          
						  }else{
							  $order_model = \common\models\base\Order::findAll(['status' => 'Placed','close' => '0','placed_via'=>'Telecaller/Admin']); 
							  $order_model_today = \common\models\base\Order::findAll(['status' => 'Approved','close' => '0','placed_via'=>'Telecaller/Admin','Date(accept_reject_datetime)'=>$date]);
							   
							   $order_model_today_Rejected = \common\models\base\Order::findAll(['status' => 'Rejected','close' => '0','placed_via'=>'Telecaller/Admin','Date(accept_reject_datetime)'=>$date]);
						     //  today place order 
							   $order_model_today_pace = \common\models\base\Order::findAll(['status' => 'Placed','close' => '0', 'placed_via'=>'Telecaller/Admin','Date(booking_time)'=>$date]);
						  } 
						  
						 if(yii::$app->user->identity->type=='telecaller'){
							 
								$cnt   =   count($order_model_today) + count($order_model_today_pace) + count($order_model_today_Rejected);	
								
							}elseif(yii::$app->user->identity->type=='restaurant'){
							    $cnt    =  count($order_model) ;	
							}else{
								$cnt =0;
							}
				 	  
		 }
		return $cnt; 
	 }
	 
	  public function actionNewordershow(){
		  
		    
		    $date = Date('Y-m-d');//telecaller
		   if(yii::$app->user->identity->type=='admin' || yii::$app->user->identity->type=='restaurant' || yii::$app->user->identity->type=='telecaller'){
			 
					      if(yii::$app->user->identity->type=='restaurant'){
							  
							  $res_id      =  yii::$app->user->identity->id;
							  $restaurant_model = \common\models\base\Restaurant::findOne(['user_id' => $res_id]);  
					          $order_model = \common\models\base\Order::findAll(['restaurant_id' =>$restaurant_model->id,'status' => 'Placed','close' => '0','placed_via'=>'Telecaller/Admin']); 
					          $order_model_today = \common\models\base\Order::findAll(['restaurant_id' =>$restaurant_model->id,'status' => 'Approved','placed_via'=>'Telecaller/Admin','close' => '0','Date(accept_reject_datetime)'=>$date]);
						  }else{
							  $order_model = \common\models\base\Order::findAll(['status' => 'Placed','close' => '0','placed_via'=>'Telecaller/Admin']); 
							  $order_model_today_Approved = \common\models\base\Order::findAll(['status' => 'Approved','close' => '0','placed_via'=>'Telecaller/Admin','Date(accept_reject_datetime)'=>$date]);
							  
							  $order_model_today_Rejected = \common\models\base\Order::findAll(['status' => 'Rejected','close' => '0','placed_via'=>'Telecaller/Admin','Date(accept_reject_datetime)'=>$date]);
							    //  today place order 
							     $connection = \Yii::$app->db;
                                
                                $order_model_today_pace = $connection->createCommand("SELECT * FROM tbl_order where status='Placed' && close='0' && placed_via='Telecaller/Admin' &&  Date(booking_time)='".$date."' && booking_time > NOW() - INTERVAL 5 MINUTE ")->queryAll(); 
             
							  //$order_model_today_pace = \common\models\base\Order::findAll(['status' => 'Placed','close' => '0', 'placed_via'=>'Telecaller/Admin','Date(booking_time)'=>$date ]);
						  }
						  
		 }
		   $html='';
		   $i=1;
		 if(yii::$app->user->identity->type=='restaurant'){
			  foreach($order_model as $order_val){ 
				  if($i<4){
					  $newDateTime = date('F j, Y, g:i a', strtotime($order_val->booking_time));
					   $color=($order_val->view==0 ? "808080" :"A4A4A4");
					 $html.='<li><a style="color:#'.$color.'" href="'.Yii::$app->request->baseUrl.'/order/view?id='.$order_val->id.'"><i class="fa fa-envelope"></i> New order placed <span>'.$newDateTime.'</span> </a><a style="color:#'.$color.'" href="#"> <span class="sp" id="'.$order_val->id.'">X</span></a></li>';
					 
					 
				 }
				 $i++;
			  } 
		} 
		
		if(yii::$app->user->identity->type=='telecaller'){
			   if($order_model_today_Approved){ 
				                if($html) $html.="<hr>"; 
				                
								foreach($order_model_today_Approved as $order_model_todays){  
									if($i<4){
										 $newDateTime = date('F j, Y, g:i a', strtotime($order_model_todays->accept_reject_datetime));
										 $color=($order_model_todays->view==0 ? "808080" :"A4A4A4");
										 $restaurant = \common\models\base\Restaurant::findOne(['id' => $order_model_todays->restaurant_id]);
										 $color=($order_model_todays->view==0 ? "808080" :"A4A4A4");
										 if($order_model_todays->status=="Approved"){
										 
										 $html.='<li><a style="color:#'.$color.'" href="'.Yii::$app->request->baseUrl.'/order/view?id='.$order_model_todays->id.'"><i class="fa fa-envelope"></i> Order from '.ucfirst($order_model_todays->user_full_name).'  is accepted by '.ucfirst($restaurant->title).' <span>'.$newDateTime.'</span> </a><a style="color:#'.$color.'" href="#"><span class="sp" id="'.$order_model_todays->id.'">X</span> </a></li>';
										 
										  /* for send notification to telecoller  when order assign to delivery boy  autometicaly by nearby */
								            $deliveryBoyOrder = \common\models\base\DeliveryBoyOrder::findAll(['order_id' => $order_model_todays->id]);
								            if($deliveryBoyOrder){
												
												 $user_info = \common\models\base\DeliveryBoy::findOne(['id' => $deliveryBoyOrder->user_id]);
												
												 if($deliveryBoyOrder->status =="Acknowledge"){ 
													 
													
													 $minutes =  (time()-strtotime($deliveryBoyOrder->created_at))/60*1000; 
													 
													 if($minutes<2){
														 
														$html.='<li><a style="color:#'.$color.'" href="'.Yii::$app->request->baseUrl.'/order/view?id='.$order_model_todays->id.'"><i class="fa fa-envelope"></i> Delivery boy&#39; '.ucfirst($user_info->first_name." ". $user_info->last_name).'  has been assigned the delivery of '.ucfirst($restaurant->title).' <span>'.$newDateTime.'</span> </a><a style="color:#'.$color.'" href="#"><span class="sp" id="'.$order_model_todays->id.'">X</span> </a></li>'; 
														
													}else{
														
													  $html.='<li><a style="color:#'.$color.'" href="'.Yii::$app->request->baseUrl.'/order/view?id='.$order_model_todays->id.'"><i class="fa fa-envelope"></i>  '.ucfirst($user_info->first_name." ". $user_info->last_name).'  has not yet acknowledged the order from '.ucfirst($restaurant->title).' <span>'.$newDateTime.'</span> </a><a style="color:#'.$color.'" href="#"><span class="sp" id="'.$order_model_todays->id.'">X</span> </a></li>'; 
													  
													}
													 
													 
												 }elseif($deliveryBoyOrder->status =="Acknowledged"){
													 
													  $html.='<li><a style="color:#'.$color.'" href="'.Yii::$app->request->baseUrl.'/order/view?id='.$order_model_todays->id.'"><i class="fa fa-envelope"></i>  '.ucfirst($user_info->first_name." ". $user_info->last_name).'  is ready to pick up the order from  '.ucfirst($restaurant->title).' <span>'.$newDateTime.'</span> </a><a style="color:#'.$color.'" href="#"><span class="sp" id="'.$order_model_todays->id.'">X</span> </a></li>';
													  
												 }elseif($deliveryBoyOrder->status =="Pickup"){
													 
													  $html.='<li><a style="color:#'.$color.'" href="'.Yii::$app->request->baseUrl.'/order/view?id='.$order_model_todays->id.'"><i class="fa fa-envelope"></i>  '.ucfirst($user_info->first_name." ". $user_info->last_name).'  has picked up the order from  '.ucfirst($restaurant->title).' <span>'.$newDateTime.'</span> </a><a style="color:#'.$color.'" href="#"><span class="sp" id="'.$order_model_todays->id.'">X</span> </a></li>';
													  
												 }elseif($deliveryBoyOrder->status =="Delivered"){
													 
													  $html.='<li><a style="color:#'.$color.'" href="'.Yii::$app->request->baseUrl.'/order/view?id='.$order_model_todays->id.'"><i class="fa fa-envelope"></i> The order has been delivered from  '.ucfirst($restaurant->title).'  to  '.ucfirst($order_model_todays->user_full_name).' <span>'.$newDateTime.'</span> </a><a style="color:#'.$color.'" href="#"><span class="sp" id="'.$order_model_todays->id.'">X</span> </a></li>';
												 }
												 
												 
												
											}else{
												
												$html.='<li><a style="color:#'.$color.'" href="'.Yii::$app->request->baseUrl.'/order/view?id='.$order_model_todays->id.'"><i class="fa fa-envelope"></i> Order of  '.ucfirst($restaurant->title).'  has not yet been assigned for Delivery 
												<span>'.$newDateTime.'</span> </a><a style="color:#'.$color.'" href="#"><span class="sp" id="'.$order_model_todays->id.'">X</span> </a></li>'; 
												
											}
								     
								          /* end */
										 }
										 
									}
									 $i++;
								}
							
						 }
						 // if reject by resurant owner  
						 
						 if($order_model_today_Rejected){
						 	       $j=$i;
						 	       foreach($order_model_today_Rejected as $order_model_todays_R){
									   if($j<5){
											 $restaurant = \common\models\base\Restaurant::findOne(['id' => $order_model_todays_R->restaurant_id]);
											 $newDateTime = date('F j, Y, g:i a', strtotime($order_model_todays_R->accept_reject_datetime)); 
											 $html.='<li><a style="color:#'.$color.'" href="'.Yii::$app->request->baseUrl.'/order/view?id='.$order_model_todays_R->id.'"><i class="fa fa-envelope"></i> Order from '.ucfirst($order_model_todays_R->user_full_name).' is cancelled by '.ucfirst($restaurant->title).' <span>'.$newDateTime.'</span> </a><a style="color:#'.$color.'" href="#"><span class="sp" id="'.$order_model_todays_R->id.'">X</span> </a></li>';
									    }
									    $j++;
									 }
									 
							}
										 
						  // end
						 // 5 minutes not accepted by resturant owner
						  if($order_model_today_pace){ 
							  
				                if($html) $html.="<hr>"; 
				                
								foreach($order_model_today_pace as $order_model_today_paces){ 
									
									if($i<5){
										 
										 $restaurant = \common\models\base\Restaurant::findOne(['id' => $order_model_today_paces->restaurant_id]); 
										 $connection = \Yii::$app->db;
                                         $data = $connection->createCommand("SELECT  * FROM tbl_order where id='".$order_model_today_paces->id."' AND booking_time >= DATE_SUB(now(), INTERVAL 5 MINUTE) ")->queryAll();
                                         /*
										 $booking_time =   strtotime($order_model_today_paces->booking_time); 
										 $date = date('Y-m-d H:i:s');
										 $currentdate_time =  strtotime($date); 
										 $minut =  date('i',$currentdate_time-$booking_time); 
										 * */ 
										 $newDateTime = date('F j, Y, g:i a', strtotime($order_model_today_paces->booking_time));  
										  
										 if(!$data){
											
										 $color=($order_model_today_paces->view==0 ? "808080" :"A4A4A4");
										 $html.='<li><a style="color:#'.$color.'" href="'.Yii::$app->request->baseUrl.'/order/view?id='.$order_model_today_paces->id.'"><i class="fa fa-envelope"></i> '.ucfirst($restaurant->title).'  has not yet seen the order from '.ucfirst($order_model_today_paces->user_full_name).' <span>'.$newDateTime.'</span> </a><a style="color:#'.$color.'" href="#"><span class="sp" id="'.$order_model_today_paces->id.'">X</span> </a></li>';
										 
									    }
								     }
								     $i++;
								}
							 
						 }
						 
			}
						 
		return $html;  
	 
	 }
	 
	  public function actionOrderviewstaus($id){  
	    $connection = \Yii::$app->db;
        $command = $connection->createCommand('UPDATE tbl_order SET close=1 WHERE id='.$id.'');
        $command->execute(); 
	  }

}
