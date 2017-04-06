<?php

namespace backend\controllers;

use common\models\Coupons;
use common\models\CouponsSearch;
use yii\web\Controller;
use yii\web\HttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use dmstr\bootstrap\Tabs;
use yii\helpers\Json;
use common\models\base\Dish;
use common\models\base\DishTopping;
use common\models\base\Combo;
use common\models\base\RestaurantCoupons;
use common\models\base\Order;
use Yii;
use common\models\Device;
use yii\helpers\ArrayHelper;

/**
 * CouponsController implements the CRUD actions for Coupons model.
 */
class CouponsController extends Controller {

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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'getcouponview', 'applycoupon', 'send-push-message'],
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
     * Lists all Coupons models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new CouponsSearch;
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
     * Displays a single Coupons model.
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
     * Creates a new Coupons model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Coupons;
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
       // echo "<pre>";
       // print_r($model);die;
        return $this->render('create', ['model' => $model]);
    }

    /**
     * Updates an existing Coupons model.
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
     * Deletes an existing Coupons model.
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

    public function actionGetcouponview($coupon_key, $coupon_id = 0, $restaurant_id) {
        switch (base64_decode($coupon_key)) {
            case "Cap On Discount":
                $data['coupon_id'] = $coupon_id;
                $data['restaurant_id'] = $restaurant_id;
                return $this->renderAjax('cap_on_discount_view', $data);
                break;
            case "Discount on Particular Dishes":
                $data['coupon_id'] = $coupon_id;
                $data['restaurant_id'] = $restaurant_id;
                return $this->renderAjax('discount_on_particular_dishes_view', $data);
                break;
            case "Buy 2 items get 1 free":
                $data['coupon_id'] = $coupon_id;
                $data['restaurant_id'] = $restaurant_id;
                return $this->renderAjax('buy_2_get_1_free_view', $data);
                break;
            case "Discount on Particular Menu":
                $data['coupon_id'] = $coupon_id;
                $data['restaurant_id'] = $restaurant_id;
                return $this->renderAjax('discount_on_particular_menu_view', $data);
                break;
            case "Discount on Minimum Order - Restaurant":
                $data['coupon_id'] = $coupon_id;
                $data['restaurant_id'] = $restaurant_id;
                return $this->renderAjax('discount_on_minimum_order_restaurant_view', $data);
                break;
            case "Discount on Minimum Order":
                $data['coupon_id'] = $coupon_id;
                $data['restaurant_id'] = $restaurant_id;
                return $this->renderAjax('discount_on_minimum_order_view', $data);
                break;
            case "Discount on your 2nd Order":
                $data['coupon_id'] = $coupon_id;
                $data['restaurant_id'] = $restaurant_id;
                return $this->renderAjax('discount_on_your_2nd_order_view', $data);
                break;
            case "Discount on your 2nd Order - Restaurant":
                $data['coupon_id'] = $coupon_id;
                $data['restaurant_id'] = $restaurant_id;
                return $this->renderAjax('discount_on_your_2nd_order_restaurant_view', $data);
                break;
            case "Discount Validity for only particular days":
                $data['coupon_id'] = $coupon_id;
                $data['restaurant_id'] = $restaurant_id;
                return $this->renderAjax('discount_validity_for_only_particular_days', $data);
                break;
            case "Discount for 1 person and 1 time use only":
                $data['coupon_id'] = $coupon_id;
                $data['restaurant_id'] = $restaurant_id;
                return $this->renderAjax('discount_for_1_person_and_1_time_use_only_view', $data);
                break;
            case "Code for person to use multiple times":
                $data['coupon_id'] = $coupon_id;
                $data['restaurant_id'] = $restaurant_id;
                return $this->renderAjax('code_for_person_to_use_multiple_times_view', $data);
                break;
            case "Buy 1 Item the other Item X% off":
                $data['coupon_id'] = $coupon_id;
                $data['restaurant_id'] = $restaurant_id;
                return $this->renderAjax('buy_1_item_the_other_item_x_percent_off_view', $data);
                break;
            case "Lunch Pack Discounts":
                $data['coupon_id'] = $coupon_id;
                $data['restaurant_id'] = $restaurant_id;
                return $this->renderAjax('lunch_pack_discounts_view', $data);
                break;
            case "Night Pack Discounts":
                $data['coupon_id'] = $coupon_id;
                $data['restaurant_id'] = $restaurant_id;
                return $this->renderAjax('night_pack_discounts_view', $data);
                break;
            case "Get 1 Item and 2nd one is Free":
                $data['coupon_id'] = $coupon_id;
                $data['restaurant_id'] = $restaurant_id;
                return $this->renderAjax('get_1_item_and_2nd_one_is_free_view', $data);
                break;
            case "Particular Restaurant Discount":
                $data['coupon_id'] = $coupon_id;
                $data['restaurant_id'] = $restaurant_id;
                return $this->renderAjax('particular_restaurant_discount_view', $data);
                break;
            case "Buy 1 Get 1 Free":
                $data['coupon_id'] = $coupon_id;
                $data['restaurant_id'] = $restaurant_id;
                return $this->renderAjax('buy_1_get_1_free_view', $data);
                break;
            case "Weekly Deals":
                $data['coupon_id'] = $coupon_id;
                $data['restaurant_id'] = $restaurant_id;
                return $this->renderAjax('week_days_deals_view', $data);
                break;
            case "Table Booking discount":
                $data['coupon_id'] = $coupon_id;
                $data['restaurant_id'] = $restaurant_id;
                return $this->renderAjax('table_booking_discount_view', $data);
                break;
            case "Table Booking discount - Restaurant":
                $data['coupon_id'] = $coupon_id;
                $data['restaurant_id'] = $restaurant_id;
                return $this->renderAjax('table_booking_discount_restaurant_view', $data);
                break;
            case "Weekly Deals - Cap On Discount":
                $data['coupon_id'] = $coupon_id;
                $data['restaurant_id'] = $restaurant_id;
                return $this->renderAjax('week_days_cap_on_discount_view', $data);
                break;
            case "Weekly Deals - Discount on Particular Dishes":
                $data['coupon_id'] = $coupon_id;
                $data['restaurant_id'] = $restaurant_id;
                return $this->renderAjax('week_days_discount_on_particular_dishes_view', $data);
                break;
            case "Weekly Deals - Discount on Particular Menu":
                $data['coupon_id'] = $coupon_id;
                $data['restaurant_id'] = $restaurant_id;
                return $this->renderAjax('week_days_discount_on_particular_menu_view', $data);
                break;
            case "Weekly Deals - Discount on Minimum Order - Restaurant":
                $data['coupon_id'] = $coupon_id;
                $data['restaurant_id'] = $restaurant_id;
                return $this->renderAjax('week_days_discount_on_minimum_order_restaurant_view', $data);
                break;
            case "Weekly Deals - Discount on Minimum Order":
                $data['coupon_id'] = $coupon_id;
                $data['restaurant_id'] = $restaurant_id;
                return $this->renderAjax('week_days_discount_on_minimum_order_view', $data);
                break;
            case "Weekly Deals - Buy 1 Item the other Item X% off":
                $data['coupon_id'] = $coupon_id;
                $data['restaurant_id'] = $restaurant_id;
                return $this->renderAjax('week_days_buy_1_item_the_other_item_x_percent_off_view', $data);
                break;
            case "Weekly Deals - Lunch Pack Discounts":
                $data['coupon_id'] = $coupon_id;
                $data['restaurant_id'] = $restaurant_id;
                return $this->renderAjax('week_days_lunch_pack_discounts_view', $data);
                break;
            case "Weekly Deals - Night Pack Discounts":
                $data['coupon_id'] = $coupon_id;
                $data['restaurant_id'] = $restaurant_id;
                return $this->renderAjax('week_days_night_pack_discounts_view', $data);
                break;
            case "Weekly Deals - Get 1 Item and 2nd one is Free":
                $data['coupon_id'] = $coupon_id;
                $data['restaurant_id'] = $restaurant_id;
                return $this->renderAjax('week_days_get_1_item_and_2nd_one_is_free_view', $data);
                break;
            case "Weekly Deals - Particular Restaurant Discount":
                $data['coupon_id'] = $coupon_id;
                $data['restaurant_id'] = $restaurant_id;
                return $this->renderAjax('week_days_particular_restaurant_discount_view', $data);
                break;
            case "Weekly Deals - Buy 1 Get 1 Free":
                $data['coupon_id'] = $coupon_id;
                $data['restaurant_id'] = $restaurant_id;
                return $this->renderAjax('week_days_buy_1_get_1_free_view', $data);
                break;
            case "Discount on 1st Order":
                $data['coupon_id'] = $coupon_id;
                $data['restaurant_id'] = $restaurant_id;
                return $this->renderAjax('discount_on_1st_order_view', $data);
                break;
                case "Buy 2 Get 1 Free":
                $data['coupon_id'] = $coupon_id;
                $data['restaurant_id'] = $restaurant_id;
                return $this->renderAjax('buy_2_get_1_free_view', $data);
                break;
                case "Weekly Deals - Buy 2 Get 1 Free":
                $data['coupon_id'] = $coupon_id;
                $data['restaurant_id'] = $restaurant_id;
                return $this->renderAjax('weekly_deals_buy2get1free_view', $data);
                break;
                
            default:
                echo "";
        }
    }

//    public function actionApplycoupon($couponcode) {
//        $coupon_model = Coupons::findOne(['code' => $couponcode]);
//        $this->resetCartPrice();
//        $response = array(
//            'status' => 0,
//            'message' => 'Invalid coupon code'
//        );
//        if ($coupon_model) {
////            print_r($coupon_model);
//            if ($coupon_model->status == 'Active') {
//                switch ($coupon_model->coupon_key) {
//                    case "Cap On Discount":
//                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
//                        $max_discount = $ccode_data['maximum_discount'];
//                        $discount_percentage = $ccode_data['discount_percentage'];
//                        $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
//                        $_SESSION['cart']['coupon_data']['discount_amount'] = floor(($_SESSION['cart']['subtotal'] * $discount_percentage / 100) > $max_discount ? $max_discount : ($_SESSION['cart']['subtotal'] * $discount_percentage / 100));
//                        $response['status'] = 1;
//                        $response['message'] = 'Hurray !! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
//                        break;
//                    case "Discount on Particular Dishes":
//                        $restaurant_id = $_SESSION['cart']['restaurant_id'];
//                        $restaurant_coupons = RestaurantCoupons::findOne(['restaurant_id' => $restaurant_id, 'coupon_id' => $coupon_model->id]);
//                        if (!$restaurant_coupons) {
//                            break;
//                        }
//                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
//                        $discount_percentage = $ccode_data['discount_percentage'];
//                        $discount_dish_id = $ccode_data['dish_id'];
//                        $dishes_in_cart = $_SESSION['cart']['dishes'];
//                        $dish_key = $this->checkDishPresent($discount_dish_id, $dishes_in_cart);
//                        if ($dish_key >= 0) {
//                            $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
//                            $_SESSION['cart']['coupon_data']['discount_amount'] = floor($_SESSION['cart']['dishes'][$dish_key]['price'] * $discount_percentage / 100);
//                            $response['status'] = 1;
//                            $response['message'] = 'Hurray !! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
//                        } else {
//                            $response['status'] = 0;
//                            $response['message'] = 'Opps! Coupon code could not apply to you :(';
//                        }
//                        break;
//                    case "Discount on Particular Menu":
//                        $restaurant_id = $_SESSION['cart']['restaurant_id'];
//                        $restaurant_coupons = RestaurantCoupons::findOne(['restaurant_id' => $restaurant_id, 'coupon_id' => $coupon_model->id]);
//                        if (!$restaurant_coupons) {
//                            break;
//                        }
//                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
//                        $discount_menu_id = $ccode_data['menu_id'];
//                        $discount_percentage = $ccode_data['discount_percentage'];
//                        $max_discount = $ccode_data['maximum_discount'];
//                        $dishes_in_cart = $_SESSION['cart']['dishes'];
//                        $discount_amount = 0;
//                        foreach ($dishes_in_cart as $dishKey => $dishValue) {
//                            $dish_model = Dish::findOne(['id' => $dishValue['id']]);
//                            if ($dish_model->menu_id == $discount_menu_id) {
//                                $discount_amount += ($dishValue['price'] * $dishValue['qty'] * $discount_percentage / 100);
//                            }
//                        }
//                        if ($discount_amount > 0) {
//                            $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
//                            $_SESSION['cart']['coupon_data']['discount_amount'] = ($discount_amount > $max_discount ? $max_discount : floor($discount_amount));
//                            $response['status'] = 1;
//                            $response['message'] = 'Hurray !! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
//                        } else {
//                            $response['status'] = 0;
//                            $response['message'] = 'The code you entered is either invalid or expired :(';
//                        }
//                        break;
//                    case "Discount on Minimum Order - Restaurant":
//                        $restaurant_id = $_SESSION['cart']['restaurant_id'];
//                        $restaurant_coupons = RestaurantCoupons::findOne(['restaurant_id' => $restaurant_id, 'coupon_id' => $coupon_model->id]);
//                        if (!$restaurant_coupons) {
//                            break;
//                        }
//                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
//                        $minimum_order = $ccode_data['minimum_order'];
//                        $discount_percentage = $ccode_data['discount_percentage'];
//                        if ($_SESSION['cart']['subtotal'] >= $minimum_order) {
//                            $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
//                            $_SESSION['cart']['coupon_data']['discount_amount'] = floor($_SESSION['cart']['subtotal'] * $discount_percentage / 100);
//                            $response['status'] = 1;
//                            $response['message'] = 'Hurray !! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
//                        } else {
//                            $response['status'] = 0;
//                            $response['message'] = 'Opps! You must have minimum order of ' . $minimum_order . ' Rs. to use this coupon :(';
//                        }
//                        break;
//                    case "Discount on Minimum Order":
//                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
//                        $minimum_order = $ccode_data['minimum_order'];
//                        $discount_percentage = $ccode_data['discount_percentage'];
//                        if ($_SESSION['cart']['subtotal'] >= $minimum_order) {
//                            $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
//                            $_SESSION['cart']['coupon_data']['discount_amount'] = floor($_SESSION['cart']['subtotal'] * $discount_percentage / 100);
//                            $response['status'] = 1;
//                            $response['message'] = 'Hurray !! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
//                        } else {
//                            $response['status'] = 0;
//                            $response['message'] = 'Opps! You must have minimum order of ' . $minimum_order . ' Rs. to use this coupon :(';
//                        }
//                        break;
//                    case "Discount on your 2nd Order":
//                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
//                        $discount_type = $ccode_data['discount_type'];
//                        $discount_value = $ccode_data['discount_value'];
//                        $user_order_count = Order::find()->where(['user_id' => $_SESSION['cart']['user_id']])->count();
//                        if ($user_order_count == 1) {
//                            $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
//                            if ($discount_type == 'Percentage') {
//                                $_SESSION['cart']['coupon_data']['discount_amount'] = floor($_SESSION['cart']['subtotal'] * $discount_value / 100);
//                            } else {
//                                $_SESSION['cart']['coupon_data']['discount_amount'] = floor($discount_value);
//                            }
//                            $response['status'] = 1;
//                            $response['message'] = 'Hurray !! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
//                        } else {
//                            $response['status'] = 0;
//                            $response['message'] = 'The code you entered is either invalid or expired :(';
//                        }
//                        break;
//                    case "Discount on your 2nd Order - Restaurant":
//                        $restaurant_id = $_SESSION['cart']['restaurant_id'];
//                        $restaurant_coupons = RestaurantCoupons::findOne(['restaurant_id' => $restaurant_id, 'coupon_id' => $coupon_model->id]);
//                        if (!$restaurant_coupons) {
//                            break;
//                        }
//                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
//                        $discount_type = $ccode_data['discount_type'];
//                        $discount_value = $ccode_data['discount_value'];
//                        $user_order_count = Order::find()->where(['user_id' => $_SESSION['cart']['user_id'], 'restaurant_id' => $restaurant_id])->count();
//                        if ($user_order_count == 1) {
//                            $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
//                            if ($discount_type == 'Percentage') {
//                                $_SESSION['cart']['coupon_data']['discount_amount'] = floor($_SESSION['cart']['subtotal'] * $discount_value / 100);
//                            } else {
//                                $_SESSION['cart']['coupon_data']['discount_amount'] = floor($discount_value);
//                            }
//                            $response['status'] = 1;
//                            $response['message'] = 'Hurray !! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
//                        } else {
//                            $response['status'] = 0;
//                            $response['message'] = 'The code you entered is either invalid or expired :(';
//                        }
//                        break;
//                    case "Discount Validity for only particular days":
//                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
//                        $from_date = $ccode_data['from_date'];
//                        $from_date_ts = strtotime($from_date);
//                        $to_date = $ccode_data['to_date'];
//                        $to_date_ts = strtotime($to_date);
//                        $discount_value = $ccode_data['discount_value'];
//                        if (time() > $from_date_ts && time() < $to_date_ts) {
//                            $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
//                            $_SESSION['cart']['coupon_data']['discount_amount'] = floor($_SESSION['cart']['subtotal'] * $discount_value / 100);
//                            $response['status'] = 1;
//                            $response['message'] = 'Hurray !! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
//                        } else {
//                            $response['status'] = 0;
//                            $response['message'] = 'Opps! Coupon has expired :(';
//                        }
//                        break;
//                    case "Discount for 1 person and 1 time use only":
//                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
//                        $user_id = $ccode_data['user_id'];
//                        $discount_value = $ccode_data['discount_value'];
//                        if ($user_id == $_SESSION['cart']['user_id']) {
//                            $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
//                            $_SESSION['cart']['coupon_data']['discount_amount'] = floor($_SESSION['cart']['subtotal'] * $discount_value / 100);
//                            $response['status'] = 1;
//                            $response['message'] = 'Hurray !! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
//                        } else {
//                            $response['status'] = 0;
//                            $response['message'] = 'The code you entered is either invalid or expired :(';
//                        }
//                        break;
//                    case "Code for person to use multiple times":
//                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
//                        $user_id = $ccode_data['user_id'];
//                        $discount_value = $ccode_data['discount_value'];
//                        $max_no_of_usage = $ccode_data['max_no_of_usage'];
//                        $no_of_usage = $ccode_data['no_of_usage'];
//
//                        if ($user_id == $_SESSION['cart']['user_id']) {
//                            if ($no_of_usage > $max_no_of_usage) {
//                                $response['status'] = 0;
//                                $response['message'] = 'Opps! Coupon has expired :(';
//                            } else {
//                                $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
//                                $_SESSION['cart']['coupon_data']['discount_amount'] = floor($_SESSION['cart']['subtotal'] * $discount_value / 100);
//                                $response['status'] = 1;
//                                $response['message'] = 'Hurray !! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
//                            }
//                        } else {
//                            $response['status'] = 0;
//                            $response['message'] = 'The code you entered is either invalid or expired :(';
//                        }
//                        break;
//                    case "Buy 1 Item the other Item X% off":
//                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
//                        $discount_value = $ccode_data['discount_value'];
//                        $price_arr = array();
//                        if ((count($_SESSION['cart']['dishes']) + count($_SESSION['cart']['combos'])) >= 2) {
//                            foreach ($_SESSION['cart']['dishes'] as $dishKey => $dishValue) {
//                                array_push($price_arr, $dishValue['price']);
//                            }
//                            foreach ($_SESSION['cart']['combos'] as $comboKey => $comboValue) {
//                                array_push($price_arr, $comboValue['price']);
//                            }
//                            $min_price = min($price_arr);
//                            $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
//                            $_SESSION['cart']['coupon_data']['discount_amount'] = floor($min_price * $discount_value / 100);
//                            $response['status'] = 1;
//                            $response['message'] = 'Hurray !! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
//                        } else {
//                            $response['status'] = 0;
//                            $response['message'] = 'Opps! You must have atleast 2 items in the cart :(';
//                        }
//                        break;
//                    case "Lunch Pack Discounts":
//                        $restaurant_id = $_SESSION['cart']['restaurant_id'];
//                        $restaurant_coupons = RestaurantCoupons::findOne(['restaurant_id' => $restaurant_id, 'coupon_id' => $coupon_model->id]);
//                        if (!$restaurant_coupons) {
//                            break;
//                        }
//                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
//                        $combo_id = $ccode_data['combo_id'];
//                        $discount_type = $ccode_data['discount_type'];
//                        $discount_value = $ccode_data['discount_value'];
//                        $combos = $_SESSION['cart']['combos'];
//                        foreach ($combos as $comboKey => $comboValue) {
//                            $combo_model = Combo::findOne(['id' => $comboValue['id']]);
//                            if ($combo_model->combo_type == 'Lunch Special' AND in_array($combo_model->id, $combo_id)) {
//                                $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
//                                if ($discount_type == 'Percentage') {
//                                    $_SESSION['cart']['coupon_data']['discount_amount'] = floor($comboValue['price'] * $comboValue['qty'] * $discount_value / 100);
//                                } else {
//                                    $_SESSION['cart']['coupon_data']['discount_amount'] = floor($discount_value);
//                                }
//                                $response['status'] = 1;
//                                $response['message'] = 'Hurray !! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
//                                break;
//                            }
//                        }
//                        break;
//                    case "Night Pack Discounts":
//                        $restaurant_id = $_SESSION['cart']['restaurant_id'];
//                        $restaurant_coupons = RestaurantCoupons::findOne(['restaurant_id' => $restaurant_id, 'coupon_id' => $coupon_model->id]);
//                        if (!$restaurant_coupons) {
//                            break;
//                        }
//                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
//                        $combo_id = $ccode_data['combo_id'];
//                        $discount_type = $ccode_data['discount_type'];
//                        $discount_value = $ccode_data['discount_value'];
//                        $combos = $_SESSION['cart']['combos'];
//                        foreach ($combos as $comboKey => $comboValue) {
//                            $combo_model = Combo::findOne(['id' => $comboValue['id']]);
//                            if ($combo_model->combo_type == 'Night special' AND in_array($combo_model->id, $combo_id)) {
//                                $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
//                                if ($discount_type == 'Percentage') {
//                                    $_SESSION['cart']['coupon_data']['discount_amount'] = floor($comboValue['price'] * $comboValue['qty'] * $discount_value / 100);
//                                } else {
//                                    $_SESSION['cart']['coupon_data']['discount_amount'] = floor($discount_value);
//                                }
//                                $response['status'] = 1;
//                                $response['message'] = 'Hurray !! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
//                                break;
//                            }
//                        }
//                        break;
//                    case "Get 1 Item and 2nd one is Free":
//                        $restaurant_id = $_SESSION['cart']['restaurant_id'];
//                        $restaurant_coupons = RestaurantCoupons::findOne(['restaurant_id' => $restaurant_id, 'coupon_id' => $coupon_model->id]);
//                        if (!$restaurant_coupons) {
//                            break;
//                        }
//                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
//                        $paid_dish_id = $ccode_data['paid_dish_id'];
//                        $free_dish_id = $ccode_data['free_dish_id'];
//                        $dishes_arr = array();
//                        foreach ($_SESSION['cart']['dishes'] as $dishKey => $dishValue) {
//                            array_push($dishes_arr, $dishValue['id']);
//                        }
//                        if (in_array($paid_dish_id, $dishes_arr) AND in_array($free_dish_id, $dishes_arr)) {
//                            $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
//                            $_SESSION['cart']['coupon_data']['discount_amount'] = floor($_SESSION['cart']['dishes'][array_search($free_dish_id, $dishes_arr)]['price']);
//                            $response['status'] = 1;
//                            $response['message'] = 'Hurray !! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
//                        } else {
//                            $response['status'] = 0;
//                            $response['message'] = 'The code you entered is either invalid or expired :(';
//                        }
//
//                        break;
//                    case "Particular Restaurant Discount":
//                        $restaurant_id = $_SESSION['cart']['restaurant_id'];
//                        $restaurant_coupons = RestaurantCoupons::findOne(['restaurant_id' => $restaurant_id, 'coupon_id' => $coupon_model->id]);
//                        if (!$restaurant_coupons) {
//                            break;
//                        }
//                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
//                        $discount_percentage = $ccode_data['discount_percentage'];
//                        $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
//                        $_SESSION['cart']['coupon_data']['discount_amount'] = floor($_SESSION['cart']['subtotal'] * $discount_percentage / 100);
//                        $response['status'] = 1;
//                        $response['message'] = 'Hurray !! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
//                        break;
//                    case "Buy 1 Get 1 Free":
//                        $response['message'] = 'The code you entered is either invalid or expired :(';
//                        $restaurant_id = $_SESSION['cart']['restaurant_id'];
//                        $restaurant_coupons = RestaurantCoupons::findOne(['restaurant_id' => $restaurant_id, 'coupon_id' => $coupon_model->id]);
//                        if (!$restaurant_coupons) {
//                            break;
//                        }
//                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
//                        $paid_dish_id = $ccode_data['paid_dish_id'];
//                        $free_dish_id = $ccode_data['free_dish_id'];
//                        $price_arr = array();
//                        $dishes_arr = array();
//                        foreach ($_SESSION['cart']['dishes'] as $dishKey => $dishValue) {
//                            array_push($dishes_arr, $dishValue['id']);
//                            array_push($price_arr, $dishValue['price']);
//                        }
//                        $paid_intersect = array_intersect($dishes_arr, $paid_dish_id);
//                        $free_intersect = array_intersect($dishes_arr, $free_dish_id);
//
//                        if ((!empty($paid_intersect)) AND ( !empty($free_intersect))) {
//                            foreach ($dishes_arr as $dishKey => $dishValue) {
//                                if (!(in_array($dishValue, $free_dish_id))) {
//                                    unset($price_arr[$dishKey]);
//                                }
//                            }
//                            $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
//                            $_SESSION['cart']['coupon_data']['discount_amount'] = floor(min($price_arr));
//                            $response['status'] = 1;
//                            $response['message'] = 'Hurray !! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
//                        }
//                        break;
//                    case "Weekly Deals":
//                        $restaurant_id = $_SESSION['cart']['restaurant_id'];
//                        $restaurant_coupons = RestaurantCoupons::findOne(['restaurant_id' => $restaurant_id, 'coupon_id' => $coupon_model->id]);
//                        if (!$restaurant_coupons) {
//                            break;
//                        }
//                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
//                        $today = \Yii::$app->formatter->asDate('now', 'eeee');
//                        $week_days = $ccode_data['week_days'];
//                        $discount_percentage = $ccode_data['discount_percentage'];
//                        if (in_array($today, $week_days)) {
//                            $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
//                            $_SESSION['cart']['coupon_data']['discount_amount'] = floor($_SESSION['cart']['subtotal'] * $discount_percentage / 100);
//                            $response['status'] = 1;
//                            $response['message'] = 'Hurray !! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
//                        } else {
//                            $response['status'] = 0;
//                            $response['message'] = 'Opps! Deal is not available for today :(';
//                        }
//                        break;
//
//
//                    default:
//                        echo "";
//                }
//            } else {
//                $response['status'] = 0;
//                $response['message'] = 'Opps! Coupon has expired :(';
//                //REMOVE COUPON SESSION DATA
//                unset($_SESSION['cart']['coupon_data']);
//            }
//        } else {
//            //REMOVE COUPON SESSION DATA
//            unset($_SESSION['cart']['coupon_data']);
//        }
//        echo Json::encode($response);
//    }

    public function actionApplycoupon($couponcode = '') {

        $hideSuccessMsg = FALSE;
        if (!$couponcode) {
            $couponcode = $_SESSION['cart']['coupon_data']['coupon_code'];
            $hideSuccessMsg = TRUE;
        }

        $coupon_model = Coupons::findOne(['code' => $couponcode]);
        $this->resetCartPrice();
        $response = array(
            'status' => 0,
            'message' => 'Invalid coupon code'
        );
        if ($coupon_model) {
//            print_r($coupon_model);
            $expired_on = strtotime($coupon_model->expired_on);
            $current = strtotime(date('Y-m-d H:i:s'));
            if ($coupon_model->status == 'Active' && $current < $expired_on) {
                switch ($coupon_model->coupon_key) {
                    case "Cap On Discount":
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $max_discount = $ccode_data['maximum_discount'];
                        $discount_type = $ccode_data['discount_type'];
                        $discount_percentage = $ccode_data['discount_percentage'];
                        $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
                        if ($discount_type == 'Percentage') {
                            $_SESSION['cart']['coupon_data']['discount_amount'] = floor(($_SESSION['cart']['subtotal'] * $discount_percentage / 100) > $max_discount ? $max_discount : ($_SESSION['cart']['subtotal'] * $discount_percentage / 100));
                        } else {
                            $_SESSION['cart']['coupon_data']['discount_amount'] = floor($discount_percentage);
                        }
                        $response['status'] = 1;
                        $response['message'] = 'Hurray!! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
                        \Yii::$app->getSession()->setFlash('coupon_code_success', 'Hurray!! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.');
                        break;
                    case "Discount on Particular Dishes":
                        $restaurant_id = $_SESSION['cart']['restaurant_id'];
                        $restaurant_coupons = RestaurantCoupons::findOne(['restaurant_id' => $restaurant_id, 'coupon_id' => $coupon_model->id]);
                        if (!$restaurant_coupons) {
                            break;
                        }
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $discount_percentage = $ccode_data['discount_percentage'];
                        $discount_type = $ccode_data['discount_type'];
                        $discount_dish_id = $ccode_data['dish_id'];
                        $dishes_in_cart = $_SESSION['cart']['dishes'];
                        $dish_key = $this->checkDishPresent($discount_dish_id, $dishes_in_cart);
                        if ($dish_key >= 0) {
                            $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
                            if ($discount_type == 'Percentage') {
                                $_SESSION['cart']['coupon_data']['discount_amount'] = floor($_SESSION['cart']['dishes'][$dish_key]['price'] * $discount_percentage / 100);
                            } else {
                                $_SESSION['cart']['coupon_data']['discount_amount'] = floor($discount_percentage);
                            }
                            $response['status'] = 1;
                            $response['message'] = 'Hurray!! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
                        } else {
                            $response['status'] = 0;
                            $response['message'] = 'The code you entered is either invalid or expired :( ';
                        }
                        break;
                    case "Discount on Particular Menu":
                        $restaurant_id = $_SESSION['cart']['restaurant_id'];
                        $restaurant_coupons = RestaurantCoupons::findOne(['restaurant_id' => $restaurant_id, 'coupon_id' => $coupon_model->id]);
                        if (!$restaurant_coupons) {
                            break;
                        }
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $discount_menu_id = $ccode_data['menu_id'];
                        $discount_type = $ccode_data['discount_type'];
                        $discount_percentage = $ccode_data['discount_percentage'];
                        $max_discount = $ccode_data['maximum_discount'];
                        $dishes_in_cart = $_SESSION['cart']['dishes'];
                        $discount_amount = 0;
                        foreach ($dishes_in_cart as $dishKey => $dishValue) {
                            $dish_model = Dish::findOne(['id' => $dishValue['id']]);
                            if ($dish_model->menu_id == $discount_menu_id) {
                                $discount_amount += ($dishValue['price'] * $dishValue['qty'] * $discount_percentage / 100);
                            }
                        }
                        if ($discount_amount > 0) {
                            $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
                            if ($discount_type == 'Percentage') {
                                $_SESSION['cart']['coupon_data']['discount_amount'] = ($discount_amount > $max_discount ? $max_discount : floor($discount_amount));
                            } else {
                                $_SESSION['cart']['coupon_data']['discount_amount'] = floor($discount_percentage);
                            }
                            $response['status'] = 1;
                            $response['message'] = 'Hurray!! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
                        } else {
                            $response['status'] = 0;
                            $response['message'] = 'The code you entered is either invalid or expired :(';
                        }
                        break;
                    case "Discount on Minimum Order - Restaurant":
                        $restaurant_id = $_SESSION['cart']['restaurant_id'];
                        $restaurant_coupons = RestaurantCoupons::findOne(['restaurant_id' => $restaurant_id, 'coupon_id' => $coupon_model->id]);
                        if (!$restaurant_coupons) {
                            break;
                        }
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $minimum_order = $ccode_data['minimum_order'];
                        $discount_type = $ccode_data['discount_type'];
                        $discount_percentage = $ccode_data['discount_percentage'];
                        if ($_SESSION['cart']['subtotal'] >= $minimum_order) {
                            $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
                            if ($discount_type == 'Percentage') {
                                $_SESSION['cart']['coupon_data']['discount_amount'] = floor($_SESSION['cart']['subtotal'] * $discount_percentage / 100);
                            } else {
                                $_SESSION['cart']['coupon_data']['discount_amount'] = floor($discount_percentage);
                            }
                            $response['status'] = 1;
                            $response['message'] = 'Hurray!! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
                        } else {
                            $response['status'] = 0;
                            $response['message'] = 'Opps! You must have minimum order of ' . $minimum_order . ' Rs. to use this coupon :(';
                        }
                        break;
                    case "Discount on Minimum Order":
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $minimum_order = $ccode_data['minimum_order'];
                        $discount_type = $ccode_data['discount_type'];
                        $discount_percentage = $ccode_data['discount_percentage'];
                        if ($_SESSION['cart']['subtotal'] >= $minimum_order) {
                            $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
                            if ($discount_type == 'Percentage') {
                                $_SESSION['cart']['coupon_data']['discount_amount'] = floor($_SESSION['cart']['subtotal'] * $discount_percentage / 100);
                            } else {
                                $_SESSION['cart']['coupon_data']['discount_amount'] = floor($discount_percentage);
                            }
                            $response['status'] = 1;
                            $response['message'] = 'Hurray!! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
                        } else {
                            $response['status'] = 0;
                            $response['message'] = 'Opps! You must have minimum order of ' . $minimum_order . ' Rs. to use this coupon :(';
                        }
                        break;
                    case "Discount on your 2nd Order":
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $discount_type = $ccode_data['discount_type'];
                        $discount_value = $ccode_data['discount_value'];
                        $user_order_count = Order::find()->where(['user_id' => $_SESSION['cart']['user_id']])->count();
                        if ($user_order_count == 1) {
                            $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
                            if ($discount_type == 'Percentage') {
                                $_SESSION['cart']['coupon_data']['discount_amount'] = floor($_SESSION['cart']['subtotal'] * $discount_value / 100);
                            } else {
                                $_SESSION['cart']['coupon_data']['discount_amount'] = floor($discount_value);
                            }
                            $response['status'] = 1;
                            $response['message'] = 'Hurray!! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
                        } else {
                            $response['status'] = 0;
                            $response['message'] = 'The code you entered is either invalid or expired :(';
                        }
                        break;
                    case "Discount on your 2nd Order - Restaurant":
                        $restaurant_id = $_SESSION['cart']['restaurant_id'];
                        $restaurant_coupons = RestaurantCoupons::findOne(['restaurant_id' => $restaurant_id, 'coupon_id' => $coupon_model->id]);
                        if (!$restaurant_coupons) {
                            break;
                        }
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $discount_type = $ccode_data['discount_type'];
                        $discount_value = $ccode_data['discount_value'];
                        $max_discount = $ccode_data['maximum_discount'];
                        $user_order_count = Order::find()->where(['user_id' => $_SESSION['cart']['user_id'], 'restaurant_id' => $restaurant_id])->count();
                        if ($user_order_count == 1) {
                            $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
                            if ($discount_type == 'Percentage') {
                                $_SESSION['cart']['coupon_data']['discount_amount'] = floor($_SESSION['cart']['subtotal'] * $discount_value / 100);
                            } else {
                                $_SESSION['cart']['coupon_data']['discount_amount'] = floor($discount_value);
                            }
                            if ($_SESSION['cart']['coupon_data']['discount_amount'] > $max_discount) {
                                $_SESSION['cart']['coupon_data']['discount_amount'] = $max_discount;
                            }
                            $response['status'] = 1;
                            $response['message'] = 'Hurray!! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
                        } else {
                            $response['status'] = 0;
                            $response['message'] = 'The code you entered is either invalid or expired :(';
                        }
                        break;
                    case "Discount Validity for only particular days":
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $from_date = $ccode_data['from_date'];
                        $from_date_ts = strtotime($from_date);
                        $to_date = $ccode_data['to_date'];
                        $to_date_ts = strtotime($to_date);
                        $discount_type = $ccode_data['discount_type'];
                        $discount_value = $ccode_data['discount_value'];
                        if (time() > $from_date_ts && time() < $to_date_ts) {
                            $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
                            if ($discount_type == 'Percentage') {
                                $_SESSION['cart']['coupon_data']['discount_amount'] = floor($_SESSION['cart']['subtotal'] * $discount_value / 100);
                            } else {
                                $_SESSION['cart']['coupon_data']['discount_amount'] = floor($discount_value);
                            }

                            $response['status'] = 1;
                            $response['message'] = 'Hurray!! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
                        } else {
                            $response['status'] = 0;
                            $response['message'] = 'Opps! Coupon has expired :(';
                        }
                        break;
                    case "Discount for 1 person and 1 time use only":
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $user_id = $ccode_data['user_id'];
                        $discount_value = $ccode_data['discount_value'];
                        $discount_type = $ccode_data['discount_type'];
                        if ($user_id == $_SESSION['cart']['user_id']) {
                            $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
                            if ($discount_type == 'Percentage') {
                                $_SESSION['cart']['coupon_data']['discount_amount'] = floor($_SESSION['cart']['subtotal'] * $discount_value / 100);
                            } else {
                                $_SESSION['cart']['coupon_data']['discount_amount'] = floor($discount_value);
                            }

                            $response['status'] = 1;
                            $response['message'] = 'Hurray!! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
                        } else {
                            $response['status'] = 0;
                            $response['message'] = 'The code you entered is either invalid or expired :(';
                        }
                        break;
                    case "Code for person to use multiple times":
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $user_id = $ccode_data['user_id'];
                        $discount_value = $ccode_data['discount_value'];
                        $max_no_of_usage = $ccode_data['max_no_of_usage'];
                        $no_of_usage = $ccode_data['no_of_usage'];
                        $discount_type = $ccode_data['discount_type'];
                        if ($user_id == $_SESSION['cart']['user_id']) {
                            if ($no_of_usage > $max_no_of_usage) {
                                $response['status'] = 0;
                                $response['message'] = 'Opps! Coupon has expired :(';
                            } else {
                                $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
                                if ($discount_type == 'Percentage') {
                                    $_SESSION['cart']['coupon_data']['discount_amount'] = floor($_SESSION['cart']['subtotal'] * $discount_value / 100);
                                } else {
                                    $_SESSION['cart']['coupon_data']['discount_amount'] = floor($discount_value);
                                }
                                $response['status'] = 1;
                                $response['message'] = 'Hurray!! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
                            }
                        } else {
                            $response['status'] = 0;
                            $response['message'] = 'The code you entered is either invalid or expired :(';
                        }
                        break;
                    case "Buy 1 Item the other Item X% off":
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $discount_value = $ccode_data['discount_value'];
                        $discount_type = $ccode_data['discount_type'];
                        $price_arr = array();
                        if ((count($_SESSION['cart']['dishes']) + count($_SESSION['cart']['combos']) ) >= 2) {
                            foreach ($_SESSION['cart']['dishes'] as $dishKey => $dishValue) {
                                array_push($price_arr, $dishValue['price']);
                            }
                            foreach ($_SESSION['cart']['combos'] as $comboKey => $comboValue) {
                                array_push($price_arr, $comboValue['price']);
                            }
                            $min_price = min($price_arr);
                            $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
                            if ($discount_type == 'Percentage') {
                                $_SESSION['cart']['coupon_data']['discount_amount'] = floor($min_price * $discount_value / 100);
                            } else {
                                $_SESSION['cart']['coupon_data']['discount_amount'] = floor($discount_value);
                            }
                            $response['status'] = 1;
                            $response['message'] = 'Hurray!! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
                        } else {
                            $response['status'] = 0;
                            $response['message'] = 'Opps! You must have atleast 2 items in the cart :(';
                        }
                        break;
                    case "Lunch Pack Discounts":
                        $restaurant_id = $_SESSION['cart']['restaurant_id'];
                        $restaurant_coupons = RestaurantCoupons::findOne(['restaurant_id' => $restaurant_id, 'coupon_id' => $coupon_model->id]);
                        if (!$restaurant_coupons) {
                            break;
                        }
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $combo_id = $ccode_data['combo_id'];
                        $discount_type = $ccode_data['discount_type'];
                        $discount_value = $ccode_data['discount_value'];
                        $combos = $_SESSION['cart']['combos'];
                        foreach ($combos as $comboKey => $comboValue) {
                            $combo_model = Combo::findOne(['id' => $comboValue['id']]);
                            if ($combo_model->combo_type == 'Lunch Special' AND in_array($combo_model->id, $combo_id)) {
                                $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
                                if ($discount_type == 'Percentage') {
                                    $_SESSION['cart']['coupon_data']['discount_amount'] = floor($comboValue['price'] * $comboValue['qty'] * $discount_value / 100);
                                } else {
                                    $_SESSION['cart']['coupon_data']['discount_amount'] = floor($discount_value);
                                }
                                $response['status'] = 1;
                                $response['message'] = 'Hurray!! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
                                break;
                            }
                        }
                        break;
                    case "Night Pack Discounts":
                        $restaurant_id = $_SESSION['cart']['restaurant_id'];
                        $restaurant_coupons = RestaurantCoupons::findOne(['restaurant_id' => $restaurant_id, 'coupon_id' => $coupon_model->id]);
                        if (!$restaurant_coupons) {
                            break;
                        }
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $combo_id = $ccode_data['combo_id'];
                        $discount_type = $ccode_data['discount_type'];
                        $discount_value = $ccode_data['discount_value'];
                        $combos = $_SESSION['cart']['combos'];
                        foreach ($combos as $comboKey => $comboValue) {
                            $combo_model = Combo::findOne(['id' => $comboValue['id']]);
                            if ($combo_model->combo_type == 'Night special' AND in_array($combo_model->id, $combo_id)) {
                                $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
                                if ($discount_type == 'Percentage') {
                                    $_SESSION['cart']['coupon_data']['discount_amount'] = floor($comboValue['price'] * $comboValue['qty'] * $discount_value / 100);
                                } else {
                                    $_SESSION['cart']['coupon_data']['discount_amount'] = floor($discount_value);
                                }
                                $response['status'] = 1;
                                $response['message'] = 'Hurray!! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
                                break;
                            }
                        }
                        break;
                    case "Get 1 Item and 2nd one is Free":
                        $restaurant_id = $_SESSION['cart']['restaurant_id'];
                        $restaurant_coupons = RestaurantCoupons::findOne(['restaurant_id' => $restaurant_id, 'coupon_id' => $coupon_model->id]);
                        if (!$restaurant_coupons) {
                            break;
                        }
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $paid_dish_id = $ccode_data['paid_dish_id'];
                        $free_dish_id = $ccode_data['free_dish_id'];
                        $dishes_arr = array();
                        foreach ($_SESSION['cart']['dishes'] as $dishKey => $dishValue) {
                            array_push($dishes_arr, $dishValue['id']);
                        }
                        if (in_array($paid_dish_id, $dishes_arr) AND in_array($free_dish_id, $dishes_arr)) {
                            $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
                            $_SESSION['cart']['coupon_data']['discount_amount'] = floor($_SESSION['cart']['dishes'][array_search($free_dish_id, $dishes_arr)]['price']);
                            $response['status'] = 1;
                            $response['message'] = 'Hurray!! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
                        } else {
                            $response['status'] = 0;
                            $response['message'] = 'The code you entered is either invalid or expired :(';
                        }

                        break;
                    case "Particular Restaurant Discount":
                        $restaurant_id = $_SESSION['cart']['restaurant_id'];
                        $restaurant_coupons = RestaurantCoupons::findOne(['restaurant_id' => $restaurant_id, 'coupon_id' => $coupon_model->id]);
                        if (!$restaurant_coupons) {
                            break;
                        }
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $discount_percentage = $ccode_data['discount_percentage'];
                        $discount_type = $ccode_data['discount_type'];
                        $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
                        if ($discount_type == 'Percentage') {
                            $_SESSION['cart']['coupon_data']['discount_amount'] = floor($_SESSION['cart']['subtotal'] * $discount_percentage / 100);
                        } else {
                            $_SESSION['cart']['coupon_data']['discount_amount'] = floor($discount_percentage);
                        }

                        $response['status'] = 1;
                        $response['message'] = 'Hurray!! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
                        break;
                    case "Buy 1 Get 1 Free":
                        $response['message'] = 'The code you entered is either invalid or expired :(';
                        $restaurant_id = $_SESSION['cart']['restaurant_id'];
                        $restaurant_coupons = RestaurantCoupons::findOne(['restaurant_id' => $restaurant_id, 'coupon_id' => $coupon_model->id]);
                        if (!$restaurant_coupons) {
                            break;
                        }
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $paid_dish_id = $ccode_data['paid_dish_id'];
                        $free_dish_id = $ccode_data['free_dish_id'];
                        $price_arr = array();
                        $dishes_arr = array();
                        $qty_arr = array();
                        foreach ($_SESSION['cart']['dishes'] as $dishKey => $dishValue) {
                            array_push($dishes_arr, $dishValue['id']);
                            array_push($price_arr, $dishValue['price']);
                            array_push($qty_arr, $dishValue['qty']);
                        }

                        if ($paid_dish_id == $free_dish_id) {
                            if (array_search($paid_dish_id, $dishes_arr) != -1) {
                                $qty = $qty_arr[array_search($paid_dish_id, $dishes_arr)];
                                if ($qty > 1) {
                                    $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
                                    $_SESSION['cart']['coupon_data']['discount_amount'] = floor($price_arr[array_search($paid_dish_id, $dishes_arr)]);
                                    $response['status'] = 1;
                                    $response['message'] = 'Hurray!! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
                                } else {
                                    $dishModel = Dish::findOne($paid_dish_id);
                                    $response['status'] = 0;
                                    $response['message'] = 'Please add one more ' . $dishModel->title . ' to get discount.';
                                }
                            }
                        } else {
                            $paid_intersect = array_intersect($dishes_arr, $paid_dish_id);
                            $free_intersect = array_intersect($dishes_arr, $free_dish_id);

                            if ((!empty($paid_intersect) ) AND (!empty($free_intersect))) {
                                foreach ($dishes_arr as $dishKey => $dishValue) {
                                    if (!(in_array($dishValue, $free_dish_id))) {
                                        unset($price_arr[$dishKey]);
                                    }
                                }
                                $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
                                $_SESSION['cart']['coupon_data']['discount_amount'] = floor(min($price_arr));
                                $response['status'] = 1;
                                $response['message'] = 'Hurray!! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
                            }
                        }


                        break;
                    case "Weekly Deals":
                        $restaurant_id = $_SESSION['cart']['restaurant_id'];
                        $restaurant_coupons = RestaurantCoupons::findOne(['restaurant_id' => $restaurant_id, 'coupon_id' => $coupon_model->id]);
                        if (!$restaurant_coupons) {
                            break;
                        }
                        $discount_type = $ccode_data['discount_type'];
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $today = \Yii::$app->formatter->asDate('now', 'eeee');
                        $week_days = $ccode_data['week_days'];
                        $discount_percentage = $ccode_data['discount_percentage'];
                        if (in_array($today, $week_days)) {
                            $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
                            if ($discount_type == 'Percentage') {
                                $_SESSION['cart']['coupon_data']['discount_amount'] = floor($_SESSION['cart'] ['subtotal'] * $discount_percentage / 100);
                            } else {
                                $_SESSION['cart']['coupon_data']['discount_amount'] = floor($discount_percentage);
                            }
                            $response['status'] = 1;
                            $response['message'] = 'Hurray!! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
                        } else {
                            $response['status'] = 0;
                            $response['message'] = 'Opps! Deal is not available for today :(';
                        }
                        break;
                    case "Share order link to 3 friends and get one order free":
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $discount_value = $ccode_data['discount_value'];
                        $user_id = $ccode_data['user_id'];
                        if ($user_id == $_SESSION['cart']['user_id']) {
                            $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
                            $_SESSION['cart']['coupon_data']['discount_amount'] = floor($_SESSION['cart'] ['subtotal'] < $discount_value ? $_SESSION['cart']['subtotal'] : $discount_value);
                            $response['status'] = 1;
                            $response['message'] = 'Hurray!! You just saved ' . $_SESSION['table_cart']['coupon_data']['discount_amount'] . ' Rs.';
                            \Yii::$app->getSession()->setFlash('coupon_code_success', 'Hurray!! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.');
                        } else {
                            $response['status'] = 0;
                            $response['message'] = 'The code you entered is either invalid or expired :(';
                        }
                        break;
                    case "Weekly Deals - Cap On Discount":
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $max_discount = $ccode_data['maximum_discount'];
                        $discount_type = $ccode_data['discount_type'];
                        $discount_percentage = $ccode_data['discount_percentage'];
                        $today = \Yii::$app->formatter->asDate('now', 'eeee');
                        $week_days = $ccode_data['week_days'];
                        if (in_array($today, $week_days)) {
                            $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
                            if ($discount_type == 'Percentage') {
                                $_SESSION['cart']['coupon_data']['discount_amount'] = floor(($_SESSION['cart']['subtotal'] * $discount_percentage / 100) > $max_discount ? $max_discount : ($_SESSION['cart']['subtotal'] * $discount_percentage / 100));
                            } else {
                                $_SESSION['cart']['coupon_data']['discount_amount'] = floor($discount_percentage);
                            }
                            $response['status'] = 1;
                            $response['message'] = 'Hurray!! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
                            \Yii::$app->getSession()->setFlash('coupon_code_success', 'Hurray!! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.');
                        } else {
                            $response['status'] = 0;
                            $response['message'] = 'Opps! Deal is not available for today :(';
                        }
                        break;
                    case "Weekly Deals - Discount on Particular Dishes":
                        $restaurant_id = $_SESSION['cart']['restaurant_id'];
                        $restaurant_coupons = RestaurantCoupons::findOne(['restaurant_id' => $restaurant_id, 'coupon_id' => $coupon_model->id]);
                        if (!$restaurant_coupons) {
                            break;
                        }
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $discount_percentage = $ccode_data['discount_percentage'];
                        $discount_type = $ccode_data['discount_type'];
                        $discount_dish_id = $ccode_data['dish_id'];
                        $dishes_in_cart = $_SESSION['cart']['dishes'];
                        $dish_key = $this->checkDishPresent($discount_dish_id, $dishes_in_cart);
                        $today = \Yii::$app->formatter->asDate('now', 'eeee');
                        $week_days = $ccode_data['week_days'];
                        if (in_array($today, $week_days)) {
                            if ($dish_key >= 0) {
                                $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
                                if ($discount_type == 'Percentage') {
                                    $_SESSION['cart']['coupon_data']['discount_amount'] = floor($_SESSION['cart']['dishes'][$dish_key]['price'] * $discount_percentage / 100);
                                } else {
                                    $_SESSION['cart']['coupon_data']['discount_amount'] = floor($discount_percentage);
                                }
                                $response['status'] = 1;
                                $response['message'] = 'Hurray!! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
                            } else {
                                $response['status'] = 0;
                                $response['message'] = 'The code you entered is either invalid or expired :( ';
                            }
                        } else {
                            $response['status'] = 0;
                            $response['message'] = 'Opps! Deal is not available for today :(';
                        }
                        break;
                    case "Weekly Deals - Discount on Particular Menu":
                        $restaurant_id = $_SESSION['cart']['restaurant_id'];
                        $restaurant_coupons = RestaurantCoupons::findOne(['restaurant_id' => $restaurant_id, 'coupon_id' => $coupon_model->id]);
                        if (!$restaurant_coupons) {
                            break;
                        }
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $discount_menu_id = $ccode_data['menu_id'];
                        $discount_type = $ccode_data['discount_type'];
                        $discount_percentage = $ccode_data['discount_percentage'];
                        $max_discount = $ccode_data['maximum_discount'];
                        $today = \Yii::$app->formatter->asDate('now', 'eeee');
                        $week_days = $ccode_data['week_days'];
                        $dishes_in_cart = $_SESSION['cart']['dishes'];
                        $discount_amount = 0;
                        foreach ($dishes_in_cart as $dishKey => $dishValue) {
                            $dish_model = Dish::findOne(['id' => $dishValue['id']]);
                            if ($dish_model->menu_id == $discount_menu_id) {
                                $discount_amount += ($dishValue['price'] * $dishValue['qty'] * $discount_percentage / 100);
                            }
                        }
                        if (in_array($today, $week_days)) {
                            if ($discount_amount > 0) {
                                $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
                                if ($discount_type == 'Percentage') {
                                    $_SESSION['cart']['coupon_data']['discount_amount'] = ($discount_amount > $max_discount ? $max_discount : floor($discount_amount));
                                } else {
                                    $_SESSION['cart']['coupon_data']['discount_amount'] = floor($discount_percentage);
                                }
                                $response['status'] = 1;
                                $response['message'] = 'Hurray!! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
                            } else {
                                $response['status'] = 0;
                                $response['message'] = 'The code you entered is either invalid or expired :(';
                            }
                        } else {
                            $response['status'] = 0;
                            $response['message'] = 'Opps! Deal is not available for today :(';
                        }
                        break;
                    case "Weekly Deals - Discount on Minimum Order - Restaurant":
                        $restaurant_id = $_SESSION['cart']['restaurant_id'];
                        $restaurant_coupons = RestaurantCoupons::findOne(['restaurant_id' => $restaurant_id, 'coupon_id' => $coupon_model->id]);
                        if (!$restaurant_coupons) {
                            break;
                        }
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $minimum_order = $ccode_data['minimum_order'];
                        $discount_type = $ccode_data['discount_type'];
                        $discount_percentage = $ccode_data['discount_percentage'];
                        $today = \Yii::$app->formatter->asDate('now', 'eeee');
                        $week_days = $ccode_data['week_days'];
                        if (in_array($today, $week_days)) {
                            if ($_SESSION['cart']['subtotal'] >= $minimum_order) {
                                $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
                                if ($discount_type == 'Percentage') {
                                    $_SESSION['cart']['coupon_data']['discount_amount'] = floor($_SESSION['cart']['subtotal'] * $discount_percentage / 100);
                                } else {
                                    $_SESSION['cart']['coupon_data']['discount_amount'] = floor($discount_percentage);
                                }
                                $response['status'] = 1;
                                $response['message'] = 'Hurray!! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
                            } else {
                                $response['status'] = 0;
                                $response['message'] = 'Opps! You must have minimum order of ' . $minimum_order . ' Rs. to use this coupon :(';
                            }
                        } else {
                            $response['status'] = 0;
                            $response['message'] = 'Opps! Deal is not available for today :(';
                        }
                        break;
                    case "Weekly Deals - Discount on Minimum Order":
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $minimum_order = $ccode_data['minimum_order'];
                        $discount_type = $ccode_data['discount_type'];
                        $discount_percentage = $ccode_data['discount_percentage'];
                        $today = \Yii::$app->formatter->asDate('now', 'eeee');
                        $week_days = $ccode_data['week_days'];
                        if (in_array($today, $week_days)) {
                            if ($_SESSION['cart']['subtotal'] >= $minimum_order) {
                                $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
                                if ($discount_type == 'Percentage') {
                                    $_SESSION['cart']['coupon_data']['discount_amount'] = floor($_SESSION['cart']['subtotal'] * $discount_percentage / 100);
                                } else {
                                    $_SESSION['cart']['coupon_data']['discount_amount'] = floor($discount_percentage);
                                }
                                $response['status'] = 1;
                                $response['message'] = 'Hurray!! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
                            } else {
                                $response['status'] = 0;
                                $response['message'] = 'Opps! You must have minimum order of ' . $minimum_order . ' Rs. to use this coupon :(';
                            }
                        } else {
                            $response['status'] = 0;
                            $response['message'] = 'Opps! Deal is not available for today :(';
                        }
                        break;
                    case "Weekly Deals - Buy 1 Item the other Item X% off":
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $discount_value = $ccode_data['discount_value'];
                        $discount_type = $ccode_data['discount_type'];
                        $today = \Yii::$app->formatter->asDate('now', 'eeee');
                        $week_days = $ccode_data['week_days'];
                        $price_arr = array();
                        if (in_array($today, $week_days)) {
                            if ((count($_SESSION['cart']['dishes']) + count($_SESSION['cart']['combos']) ) >= 2) {
                                foreach ($_SESSION['cart']['dishes'] as $dishKey => $dishValue) {
                                    array_push($price_arr, $dishValue['price']);
                                }
                                foreach ($_SESSION['cart']['combos'] as $comboKey => $comboValue) {
                                    array_push($price_arr, $comboValue['price']);
                                }
                                $min_price = min($price_arr);
                                $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
                                if ($discount_type == 'Percentage') {
                                    $_SESSION['cart']['coupon_data']['discount_amount'] = floor($min_price * $discount_value / 100);
                                } else {
                                    $_SESSION['cart']['coupon_data']['discount_amount'] = floor($discount_value);
                                }
                                $response['status'] = 1;
                                $response['message'] = 'Hurray!! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
                            } else {
                                $response['status'] = 0;
                                $response['message'] = 'Opps! You must have atleast 2 items in the cart :(';
                            }
                        } else {
                            $response['status'] = 0;
                            $response['message'] = 'Opps! Deal is not available for today :(';
                        }
                        break;
                    case "Weekly Deals - Lunch Pack Discounts":
                        $restaurant_id = $_SESSION['cart']['restaurant_id'];
                        $restaurant_coupons = RestaurantCoupons::findOne(['restaurant_id' => $restaurant_id, 'coupon_id' => $coupon_model->id]);
                        if (!$restaurant_coupons) {
                            break;
                        }
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $combo_id = $ccode_data['combo_id'];
                        $discount_type = $ccode_data['discount_type'];
                        $discount_value = $ccode_data['discount_value'];
                        $today = \Yii::$app->formatter->asDate('now', 'eeee');
                        $week_days = $ccode_data['week_days'];
                        $combos = $_SESSION['cart']['combos'];
                        if (in_array($today, $week_days)) {
                            foreach ($combos as $comboKey => $comboValue) {
                                $combo_model = Combo::findOne(['id' => $comboValue['id']]);
                                if ($combo_model->combo_type == 'Lunch Special' AND in_array($combo_model->id, $combo_id)) {
                                    $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
                                    if ($discount_type == 'Percentage') {
                                        $_SESSION['cart']['coupon_data']['discount_amount'] = floor($comboValue['price'] * $comboValue['qty'] * $discount_value / 100);
                                    } else {
                                        $_SESSION['cart']['coupon_data']['discount_amount'] = floor($discount_value);
                                    }
                                    $response['status'] = 1;
                                    $response['message'] = 'Hurray!! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
                                    break;
                                }
                            }
                        } else {
                            $response['status'] = 0;
                            $response['message'] = 'Opps! Deal is not available for today :(';
                        }
                        break;
                    case "Weekly Deals - Night Pack Discounts":
                        $restaurant_id = $_SESSION['cart']['restaurant_id'];
                        $restaurant_coupons = RestaurantCoupons::findOne(['restaurant_id' => $restaurant_id, 'coupon_id' => $coupon_model->id]);
                        if (!$restaurant_coupons) {
                            break;
                        }
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $combo_id = $ccode_data['combo_id'];
                        $discount_type = $ccode_data['discount_type'];
                        $discount_value = $ccode_data['discount_value'];
                        $today = \Yii::$app->formatter->asDate('now', 'eeee');
                        $week_days = $ccode_data['week_days'];
                        $combos = $_SESSION['cart']['combos'];
                        if (in_array($today, $week_days)) {
                            foreach ($combos as $comboKey => $comboValue) {
                                $combo_model = Combo::findOne(['id' => $comboValue['id']]);
                                if ($combo_model->combo_type == 'Night special' AND in_array($combo_model->id, $combo_id)) {
                                    $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
                                    if ($discount_type == 'Percentage') {
                                        $_SESSION['cart']['coupon_data']['discount_amount'] = floor($comboValue['price'] * $comboValue['qty'] * $discount_value / 100);
                                    } else {
                                        $_SESSION['cart']['coupon_data']['discount_amount'] = floor($discount_value);
                                    }
                                    $response['status'] = 1;
                                    $response['message'] = 'Hurray!! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
                                    break;
                                }
                            }
                        } else {
                            $response['status'] = 0;
                            $response['message'] = 'Opps! Deal is not available for today :(';
                        }
                        break;
                    case "Weekly Deals - Get 1 Item and 2nd one is Free";
                        $restaurant_id = $_SESSION['cart']['restaurant_id'];
                        $restaurant_coupons = RestaurantCoupons::findOne(['restaurant_id' => $restaurant_id, 'coupon_id' => $coupon_model->id]);
                        if (!$restaurant_coupons) {
                            break;
                        }
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $paid_dish_id = $ccode_data['paid_dish_id'];
                        $free_dish_id = $ccode_data['free_dish_id'];
                        $today = \Yii::$app->formatter->asDate('now', 'eeee');
                        $week_days = $ccode_data['week_days'];
                        $dishes_arr = array();
                        foreach ($_SESSION['cart']['dishes'] as $dishKey => $dishValue) {
                            array_push($dishes_arr, $dishValue['id']);
                        }
                        if (in_array($today, $week_days)) {
                            if (in_array($paid_dish_id, $dishes_arr) AND in_array($free_dish_id, $dishes_arr)) {
                                $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
                                $_SESSION['cart']['coupon_data']['discount_amount'] = floor($_SESSION['cart']['dishes'][array_search($free_dish_id, $dishes_arr)]['price']);
                                $response['status'] = 1;
                                $response['message'] = 'Hurray!! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
                            } else {
                                $response['status'] = 0;
                                $response['message'] = 'The code you entered is either invalid or expired :(';
                            }
                        } else {
                            $response['status'] = 0;
                            $response['message'] = 'Opps! Deal is not available for today :(';
                        }
                        break;
                    case "Weekly Deals - Particular Restaurant Discount":
                        $restaurant_id = $_SESSION['cart']['restaurant_id'];
                        $restaurant_coupons = RestaurantCoupons::findOne(['restaurant_id' => $restaurant_id, 'coupon_id' => $coupon_model->id]);
                        if (!$restaurant_coupons) {
                            break;
                        }
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $discount_percentage = $ccode_data['discount_percentage'];
                        $discount_type = $ccode_data['discount_type'];
                        $today = \Yii::$app->formatter->asDate('now', 'eeee');
                        $week_days = $ccode_data['week_days'];
                        if (in_array($today, $week_days)) {
                            $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
                            if ($discount_type == 'Percentage') {
                                $_SESSION['cart']['coupon_data']['discount_amount'] = floor($_SESSION['cart']['subtotal'] * $discount_percentage / 100);
                            } else {
                                $_SESSION['cart']['coupon_data']['discount_amount'] = floor($discount_percentage);
                            }

                            $response['status'] = 1;
                            $response['message'] = 'Hurray!! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
                        } else {
                            $response['status'] = 0;
                            $response['message'] = 'Opps! Deal is not available for today :(';
                        }
                        break;
                    case "Weekly Deals - Buy 1 Get 1 Free";
                        $response['message'] = 'The code you entered is either invalid or expired :(';
                        $restaurant_id = $_SESSION['cart']['restaurant_id'];
                        $restaurant_coupons = RestaurantCoupons::findOne(['restaurant_id' => $restaurant_id, 'coupon_id' => $coupon_model->id]);
                        if (!$restaurant_coupons) {
                            break;
                        }
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $paid_dish_id = $ccode_data['paid_dish_id'];
                        $free_dish_id = $ccode_data['free_dish_id'];
                        $price_arr = array();
                        $dishes_arr = array();
                        foreach ($_SESSION['cart']['dishes'] as $dishKey => $dishValue) {
                            array_push($dishes_arr, $dishValue['id']);
                            array_push($price_arr, $dishValue['price']);
                        }
                        $paid_intersect = array_intersect($dishes_arr, $paid_dish_id);
                        $free_intersect = array_intersect($dishes_arr, $free_dish_id);
                        if (in_array($today, $week_days)) {
                            if ((!empty($paid_intersect) ) AND (!empty($free_intersect))) {
                                foreach ($dishes_arr as $dishKey => $dishValue) {
                                    if (!(in_array($dishValue, $free_dish_id))) {
                                        unset($price_arr[$dishKey]);
                                    }
                                }
                                $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
                                $_SESSION['cart']['coupon_data']['discount_amount'] = floor(min($price_arr));
                                $response['status'] = 1;
                                $response['message'] = 'Hurray!! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
                            }
                        } else {
                            $response['status'] = 0;
                            $response['message'] = 'Opps! Deal is not available for today :(';
                        }
                        break;
                    case "Discount on 1st Order":
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $discount_type = $ccode_data['discount_type'];
                        $discount_value = $ccode_data['discount_value'];
                        $user_order_count = Order::find()->where(['user_id' => $_SESSION['cart']['user_id']])->count();
                        if ($user_order_count == 0 AND $_SESSION['cart']['user_id']) {
                            $_SESSION['cart']['coupon_data']['coupon_code'] = $couponcode;
                            if ($discount_type == 'Percentage') {
                                $_SESSION['cart']['coupon_data']['discount_amount'] = floor($_SESSION['cart']['subtotal'] * $discount_value / 100);
                            } else {
                                $_SESSION['cart']['coupon_data']['discount_amount'] = floor($discount_value);
                            }
                            $response['status'] = 1;
                            $response['message'] = 'Hurray!! You just saved ' . $_SESSION['cart']['coupon_data']['discount_amount'] . ' Rs.';
                        } else {
                            $response['status'] = 0;
                            $response['message'] = 'The code you entered is either invalid or expired :(';
                        }
                        break;
                    default:
                        echo "";
                }
            } else {
                $response['status'] = 0;
                $response['message'] = 'Opps! Coupon has expired :(';
                //REMOVE COUPON SESSION DATA
                unset($_SESSION['cart']['coupon_data']);
            }
        } else {
//REMOVE COUPON SESSION DATA
            unset($_SESSION['cart']['coupon_data']);
        }
        if ($response['status']) {
            $_SESSION['cart']['coupon_success_message'] = $response['message'];
            if ($hideSuccessMsg) {
                unset($_SESSION['cart']['coupon_success_message']);
                return '';
            }
        } else {
            $_SESSION['cart']['coupon_error_message'] = $response['message'];
        }
        echo Json::encode(
                $response);
    }

    public function actionSendPushMessage(){

	    $id=$_POST['id'];
	    $cmsg=$_POST['cmsg'];
            if(!$cmsg){
               $cmsg='KhayeJao Discount Coupon!';
            }
            //print_r($_POST);die;
        $model      = $this->findModel($id);
        $devices    = Device::find()->where(['device_platform' => 'android'])->all();
        $registerId = ArrayHelper::getColumn($devices, 'device_id');
       // echo $model->title;die;

        $devices1 = \common\models\Device::find()->where(['device_platform' => 'ios'])->all();
        $iosregisterId = ArrayHelper::getColumn($devices1, 'device_id');

        if ($registerId) {
            define('API_ACCESS_KEY', 'AIzaSyAufRLgh5WXbbRzeUNLHaZX0IzuvBtQ29Q'); //OLD : AIzaSyDm5Nce1hTEpWXh6vW4Pfsps-bTU2449Dw
            $registrationIds = $registerId;
            $msg = array( 
                'message' => $model->title,
                'cmsg' => $cmsg,
                'title' => $cmsg,
                'subtitle' => $model->description,
                'tickerText' => $model->description,
                'vibrate' => 1,
                'sound' => 1,
                'largeIcon' => 'large_icon',
                'smallIcon' => 'small_icon'
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

        if ($iosregisterId) {
            /*             * *** */

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


            /*             * *** */
        }


        return $this->redirect(Url::previous());
    }

    public function checkForCouponDesctive($coupon_code) {
        $coupon_model = Coupons::findOne(['code' => $coupon_code]);
        if ($coupon_model) {
            switch ($coupon_model->coupon_key) {
                case "Discount for 1 person and 1 time use only":
                    $ccode_data = Json::decode($coupon_model->coupon_perameter);
                    if ($_SESSION['cart']['user_id'] == $ccode_data['user_id']) {
                        $coupon_model->status = 'Inactive';
                        $coupon_model->save(FALSE);
                    }
                    break;
                case "Code for person to use multiple times":
                    $ccode_data = Json::decode($coupon_model->coupon_perameter);
                    if ($_SESSION['cart']['user_id'] == $ccode_data['user_id']) {
                        $ccode_data['no_of_usage'] = $ccode_data['no_of_usage'] + 1;
                        $coupon_model->coupon_perameter = Json::encode($ccode_data);
                        if ($ccode_data['no_of_usage'] >= $ccode_data['max_no_of_usage']) {
                            $coupon_model->status = 'Inactive';
                        }
                        $coupon_model->save(FALSE);
                    }
                    break;
                default:
                    echo "";
            }
        }
    }

    private function resetCartPrice() {
        $original_cart = $_SESSION['cart'];
        if (isset($_SESSION['cart']['dishes'])) {
            foreach ($_SESSION['cart']['dishes'] as $dishesKey => $dishesValue) {
                $dish = Dish::findOne(['id' => $dishesValue['id']]);
                $_SESSION['cart']['dishes'][$dishesKey]['price'] = $dish->price;
                if (isset($dishesValue['toppings'])) {
                    foreach ($dishesValue['toppings'] as $dishToppingKey => $dishToppingValue) {
                        $dish_topping = DishTopping::findOne(['id' => $dishToppingValue['dish_topping_id']]);
                        $_SESSION['cart']['dishes'][$dishesKey]['toppings'][$dishToppingKey]['price'] = $dish_topping->price;
                    }
                }
            }
        }
        if (isset($_SESSION['cart']['combos'])) {
            foreach ($_SESSION['cart']['combos'] as $comboKey => $comboValue) {
                $combo = Combo::findOne(['id' => $comboValue['id']]);
                $_SESSION['cart']['combos'][$comboKey]['price'] = $combo->price;
            }
        }

        //REMOVE COUPON SESSION DATA
        unset($_SESSION['cart']['coupon_data']);
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
     * Finds the Coupons model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Coupons the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Coupons::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }

}
