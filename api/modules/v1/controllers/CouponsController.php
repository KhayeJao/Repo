<?php

namespace api\modules\v1\controllers;

use api\modules\v1\models\Coupons;
use api\modules\v1\models\CouponsSearch;
use yii\rest\Controller;
use yii\web\HttpException;
use yii\filters\AccessControl;
use yii\helpers\Json;
use api\modules\v1\models\Dish;
use api\modules\v1\models\DishTopping;
use api\modules\v1\models\Restaurant;
use api\modules\v1\models\Combo;
use api\modules\v1\models\RestaurantCoupons;
use api\modules\v1\models\Order;
use yii\web\Response;
use Yii;

/**
 * CouponsController implements the CRUD actions for Coupons model.
 */
class CouponsController extends Controller {

    public $modelClass = 'api\modules\v1\models\Coupons';
    private $response = array(
        'status' => 0,
        'message' => 0,
    );

    public function init() {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE");
        header("Access-Control-Allow-Headers: x-requested-with");
        parent::init();
        Yii::$app->user->enableSession = FALSE;
        if (Yii::$app->request->post('app_token') != Yii::$app->params['application_token']) {
            $this->response['status'] = 0;
            $this->response['message'] = 'Invalid token';
            echo Json::encode($this->response);
            exit;
        }
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;
        $behaviors['access'] = array(
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['weeklydeals', 'applycoupon', 'applytablecoupon', 'deals'],
                ]
            ]
        );
        return $behaviors;
    }

    /**
     * Lists all Coupons models.
     * @return mixed
     */
    public function actionWeeklydeals() {
        $weekly_deals = Coupons::find()->where(['coupon_key' => 'Weekly Deals', 'status' => 'Active'])->all();
        return $this->render('weeklydeals', ['weekly_deals' => $weekly_deals]);
    }

    public function actionApplycoupon() {
        $cart_data = Json::decode(Yii::$app->request->post('cart'));
        $couponcode = Yii::$app->request->post('couponcode');
        $coupon_model = Coupons::findOne(['code' => $couponcode]);
        if ($coupon_model) {
//            print_r($coupon_model);
            $expired_on = strtotime($coupon_model->expired_on);
            $current = strtotime(date('Y-m-d H:i:s'));
            if ($coupon_model->status == 'Active' && $current < $expired_on) {
                switch ($coupon_model->coupon_key) {
                    case "Cap On Discount":
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $max_discount = $ccode_data['maximum_discount'];
                        $discount_percentage = $ccode_data['discount_percentage'];
                        $discount_type = $ccode_data['discount_type'];
                        $cart_data['coupon_data']['coupon_code'] = $couponcode;
                        if ($discount_type == 'Percentage') {
                            $cart_data['coupon_data']['discount_amount'] = floor(($cart_data['subtotal'] * $discount_percentage / 100) > $max_discount ? $max_discount : ($cart_data['subtotal'] * $discount_percentage / 100));
                        } else {
                            $cart_data['coupon_data']['discount_amount'] = floor($discount_percentage>$max_discount ? $max_discount:floor($discount_percentage));
                        }
                        $this->response['status'] = 1;
                        $this->response['message'] = 'Hurray !! You just saved ' . $cart_data['coupon_data']['discount_amount'] . ' Rs.';
                        break;
                    case "Discount on Particular Dishes":
                        $restaurant_id = $cart_data['restaurant_id']; 
                        $restaurant_coupons = RestaurantCoupons::findOne(['restaurant_id' => $restaurant_id, 'coupon_id' => $coupon_model->id]);
                        if (!$restaurant_coupons) {
                            break;
                        }
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $discount_percentage = $ccode_data['discount_percentage'];
                        $max_discount        = $ccode_data['maximum_discount'];
                        $discount_dish_id = $ccode_data['dish_id'];
                        $discount_type = $ccode_data['discount_type'];
                        $dishes_in_cart = $cart_data['dishes'];
                        $dish_key = $this->checkDishPresent($discount_dish_id, $dishes_in_cart);
                        if ($dish_key >= 0) {
                            $cart_data['coupon_data']['coupon_code'] = $couponcode;
                            if ($discount_type == 'Percentage') {
                                $cart_data['coupon_data']['discount_amount'] = floor(($cart_data['dishes'][$dish_key]['price'] * $discount_percentage / 100) > $max_discount ? $max_discount : ($cart_data['dishes'][$dish_key]['price'] * $discount_percentage / 100));
                            } else {
								$cart_data['coupon_data']['discount_amount'] = floor($discount_percentage>$max_discount ? $max_discount : floor($discount_percentage)); 
                            }
                            $this->response['status'] = 1;
                            $this->response['message'] = 'Hurray !! You just saved ' . $cart_data['coupon_data']['discount_amount'] . ' Rs.';
                        } else {
                            $this->response['status'] = 0;
                            $this->response['message'] = 'Opps! Coupon code could not apply to you :(';
                        }
                        break;
                    case "Discount on Particular Menu":
                        $restaurant_id = $cart_data['restaurant_id'];
                        $restaurant_coupons = RestaurantCoupons::findOne(['restaurant_id' => $restaurant_id, 'coupon_id' => $coupon_model->id]);
                        if (!$restaurant_coupons) {
                            break;
                        }
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $discount_menu_id = $ccode_data['menu_id'];
                        $discount_type    = $ccode_data['discount_type'];
                        $discount_percentage = $ccode_data['discount_percentage'];
                        $max_discount = $ccode_data['maximum_discount'];
                        $dishes_in_cart = $cart_data['dishes'];
                        $discount_amount = 0;
                        foreach ($dishes_in_cart as $dishKey => $dishValue) {
                            $dish_model = Dish::findOne(['id' => $dishValue['id']]);
                            if ($dish_model->menu_id == $discount_menu_id) {
                                $discount_amount += ($dishValue['price'] * $dishValue['qty'] * $discount_percentage / 100);
                            }
                        }
                        if ($discount_amount > 0) {
                            $cart_data['coupon_data']['coupon_code'] = $couponcode;
                            if ($discount_type == 'Percentage') {
                                $cart_data['coupon_data']['discount_amount'] = ($discount_amount > $max_discount ? $max_discount : floor($discount_amount));
                            }else {
                                $cart_data['coupon_data']['discount_amount'] = ($discount_percentage > $max_discount ? $max_discount : floor($discount_percentage));
                            }
                            $this->response['status'] = 1;
                            $this->response['message'] = 'Hurray !! You just saved ' . $cart_data['coupon_data']['discount_amount'] . ' Rs.';
                        } else {
                            $this->response['status'] = 0;
                            $this->response['message'] = 'The code you entered is either invalid or expired :(';
                        }
                        break;
                    case "Discount on Minimum Order - Restaurant":
                        $restaurant_id = $cart_data['restaurant_id'];
                        $restaurant_coupons = RestaurantCoupons::findOne(['restaurant_id' => $restaurant_id, 'coupon_id' => $coupon_model->id]);
                        if (!$restaurant_coupons) {
                            break;
                        }
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $minimum_order = $ccode_data['minimum_order'];
                        $max_discount  = $ccode_data['maximum_discount'];
                        $discount_type = $ccode_data['discount_type'];
                        $discount_percentage = $ccode_data['discount_percentage'];
                        if ($cart_data['subtotal'] >= $minimum_order) {
                            $cart_data['coupon_data']['coupon_code'] = $couponcode;
                            if ($discount_type == 'Percentage') {
                                $cart_data['coupon_data']['discount_amount'] = floor(($cart_data['subtotal'] * $discount_percentage / 100) > $max_discount ? $max_discount : ($cart_data['subtotal'] * $discount_percentage / 100));
                            } else {
                                $cart_data['coupon_data']['discount_amount'] = floor($discount_percentage>$max_discount ? $max_discount: $discount_percentage);
                            }
                            $this->response['status'] = 1;
                            $this->response['message'] = 'Hurray !! You just saved ' . $cart_data['coupon_data']['discount_amount'] . ' Rs.';
                        } else {
                            $this->response['status'] = 0;
                            $this->response['message'] = 'Opps! You must have minimum order of ' . $minimum_order . ' Rs. to use this coupon :(';
                        }
                        break;
                    case "Discount on Minimum Order":
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $minimum_order = $ccode_data['minimum_order'];
                        $discount_type = $ccode_data['discount_type'];
                        $max_discount  = $ccode_data['maximum_discount'];
                        $discount_percentage = $ccode_data['discount_percentage'];
                        if ($cart_data['subtotal'] >= $minimum_order) {
                            $cart_data['coupon_data']['coupon_code'] = $couponcode;
                            if ($discount_type == 'Percentage') {
                                $cart_data['coupon_data']['discount_amount'] = floor($cart_data['subtotal'] * $discount_percentage / 100);
                            } else {
                                $cart_data['coupon_data']['discount_amount'] = floor($discount_percentage);
                            }
                            if ($cart_data['coupon_data']['discount_amount'] > $max_discount) { 
			           $cart_data['coupon_data']['discount_amount'] = $max_discount;
										
				}
                            $this->response['status'] = 1;
                            $this->response['message'] = 'Hurray !! You just saved ' . $cart_data['coupon_data']['discount_amount'] . ' Rs.';
                        } else {
                            $this->response['status'] = 0;
                            $this->response['message'] = 'Opps! You must have minimum order of ' . $minimum_order . ' Rs. to use this coupon :(';
                        }
                        break;
                   case "Discount on your 2nd Order":
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $discount_type = $ccode_data['discount_type'];
                        $max_discount  = $ccode_data['maximum_discount'];
                        $discount_value = $ccode_data['discount_value'];
                        $user_order_count = Order::find()->where(['user_id' => $cart_data['user_id']])->count();
                        if ($user_order_count == 1) {
                            $cart_data['coupon_data']['coupon_code'] = $couponcode;
                            if ($discount_type == 'Percentage') {
                                $cart_data['coupon_data']['discount_amount'] = floor(($cart_data['subtotal'] * $discount_value / 100) > $max_discount ? $max_discount : ($cart_data['subtotal'] * $discount_value / 100) );
                            } else {
                                $cart_data['coupon_data']['discount_amount'] = floor($discount_value > $max_discount ? $max_discount : $discount_value);
                            }
                            $this->response['status'] = 1;
                            $this->response['message'] = 'Hurray !! You just saved ' . $cart_data['coupon_data']['discount_amount'] . ' Rs.';
                        } else {
                            $this->response['status'] = 0;
                            $this->response['message'] = 'The code you entered is either invalid or expired :(';
                        }
                        break;
                    case "Discount on your 2nd Order - Restaurant":
                        $restaurant_id = $cart_data['restaurant_id'];
                        $restaurant_coupons = RestaurantCoupons::findOne(['restaurant_id' => $restaurant_id, 'coupon_id' => $coupon_model->id]);
                        if (!$restaurant_coupons) {
                            break;
                        }
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $discount_type = $ccode_data['discount_type'];
                        $max_discount  = $ccode_data['maximum_discount'];
                        $discount_value = $ccode_data['discount_value'];
                        $max_discount = $ccode_data['maximum_discount'];
                        $user_order_count = Order::find()->where(['user_id' => $cart_data['user_id'], 'restaurant_id' => $restaurant_id])->count();
                        if ($user_order_count == 1) {
                            $cart_data['coupon_data']['coupon_code'] = $couponcode;
                            if ($discount_type == 'Percentage') {
                                $cart_data['coupon_data']['discount_amount'] = floor($cart_data['subtotal'] * $discount_value / 100);
                            } else {
                                $cart_data['coupon_data']['discount_amount'] = floor($discount_value);
                            }
                            if ($cart_data['coupon_data']['discount_amount'] > $max_discount) {
                                $cart_data['coupon_data']['discount_amount'] = $max_discount;
                            }
                            $this->response['status'] = 1;
                            $this->response['message'] = 'Hurray !! You just saved ' . $cart_data['coupon_data']['discount_amount'] . ' Rs.';
                        } else {
                            $this->response['status'] = 0;
                            $this->response['message'] = 'The code you entered is either invalid or expired :(';
                        }
                        break;
                    case "Discount Validity for only particular days":
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $from_date = $ccode_data['from_date'];
                        $from_date_ts = strtotime($from_date);
                        $to_date = $ccode_data['to_date'];
                        $to_date_ts = strtotime($to_date);
                        $max_discount  = $ccode_data['maximum_discount'];
                        $discount_type = $ccode_data['discount_type'];
                        $discount_value = $ccode_data['discount_value'];
                        if (time() > $from_date_ts && time() < $to_date_ts) {
                            $cart_data['coupon_data']['coupon_code'] = $couponcode;
                            if ($discount_type == 'Percentage') {
                                $cart_data['coupon_data']['discount_amount'] = floor($cart_data['subtotal'] * $discount_value / 100);
                            } else {
                                $cart_data['coupon_data']['discount_amount'] = floor($discount_value);
                            }
                            
                            if ($cart_data['coupon_data']['discount_amount'] > $max_discount) {
                                $cart_data['coupon_data']['discount_amount'] = $max_discount;
                            }

                            $this->response['status'] = 1;
                            $this->response['message'] = 'Hurray !! You just saved ' . $cart_data['coupon_data']['discount_amount'] . ' Rs.';
                        } else {
                            $this->response['status'] = 0;
                            $this->response['message'] = 'Opps! Coupon has expired :(';
                        }
                        break;
                    case "Discount for 1 person and 1 time use only":
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $user_id = $ccode_data['user_id'];
                        $discount_value = $ccode_data['discount_value'];
                        $discount_type = $ccode_data['discount_type'];
                        $max_discount  = $ccode_data['maximum_discount'];
                        if ($user_id == $cart_data['user_id']) {
                            $cart_data['coupon_data']['coupon_code'] = $couponcode;
                            if ($discount_type == 'Percentage') {
                                $cart_data['coupon_data']['discount_amount'] = floor($cart_data['subtotal'] * $discount_value / 100);
                            } else {
                                $cart_data['coupon_data']['discount_amount'] = floor($discount_value);
                            }
                            if ($cart_data['coupon_data']['discount_amount'] > $max_discount) {
                                $cart_data['coupon_data']['discount_amount'] = $max_discount;
                            }

                            $this->response['status'] = 1;
                            $this->response['message'] = 'Hurray !! You just saved ' . $cart_data['coupon_data']['discount_amount'] . ' Rs.';
                        } else {
                            $this->response['status'] = 0;
                            $this->response['message'] = 'The code you entered is either invalid or expired :(';
                        }
                        break;
                    case "Code for person to use multiple times":
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $user_id = $ccode_data['user_id'];
                        $discount_value = $ccode_data['discount_value'];
                        $max_no_of_usage = $ccode_data['max_no_of_usage'];
                        $no_of_usage = $ccode_data['no_of_usage'];
                        $max_discount  = $ccode_data['maximum_discount'];
                        $discount_type = $ccode_data['discount_type'];
                        if ($user_id == $cart_data['user_id']) {
                            if ($no_of_usage > $max_no_of_usage) {
                                $this->response['status'] = 0;
                                $this->response['message'] = 'Opps! Coupon has expired :(';
                            } else {
                                $cart_data['coupon_data']['coupon_code'] = $couponcode;
                                if ($discount_type == 'Percentage') {
                                    $cart_data['coupon_data']['discount_amount'] = floor($cart_data['subtotal'] * $discount_value / 100);
                                } else {
                                    $cart_data['coupon_data']['discount_amount'] = floor($discount_value);
                                }
                                
                                if ($cart_data['coupon_data']['discount_amount'] > $max_discount) {
                                    $cart_data['coupon_data']['discount_amount'] = $max_discount;
                                 }
                                $this->response['status'] = 1;
                                $this->response['message'] = 'Hurray !! You just saved ' . $cart_data['coupon_data']['discount_amount'] . ' Rs.';
                            }
                        } else {
                            $this->response['status'] = 0;
                            $this->response['message'] = 'The code you entered is either invalid or expired :(';
                        }
                        break;
                    case "Buy 1 Item the other Item X% off":
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $discount_value = $ccode_data['discount_value'];
                         $max_discount  = $ccode_data['maximum_discount'];
                        $discount_type = $ccode_data['discount_type'];
                        $price_arr = array();
                        if ((count($cart_data['dishes']) + count($cart_data['combos'])) >= 2) {
                            foreach ($cart_data['dishes'] as $dishKey => $dishValue) {
                                array_push($price_arr, $dishValue['price']);
                            }
                            foreach ($cart_data['combos'] as $comboKey => $comboValue) {
                                array_push($price_arr, $comboValue['price']);
                            }
                            $min_price = min($price_arr);
                            $cart_data['coupon_data']['coupon_code'] = $couponcode;
                            if ($discount_type == 'Percentage') {
                                $cart_data['coupon_data']['discount_amount'] = floor($min_price * $discount_value / 100);
                            } else {
                                $cart_data['coupon_data']['discount_amount'] = floor($discount_value);
                            }
                            if ($cart_data['coupon_data']['discount_amount'] > $max_discount) {
                                $cart_data['coupon_data']['discount_amount'] = $max_discount;
                            }
                            $this->response['status'] = 1;
                            $this->response['message'] = 'Hurray !! You just saved ' . $cart_data['coupon_data']['discount_amount'] . ' Rs.';
                        } else {
                            $this->response['status'] = 0;
                            $this->response['message'] = 'Opps! You must have atleast 2 items in the cart :(';
                        }
                        break;
                    case "Lunch Pack Discounts":
                        $restaurant_id = $cart_data['restaurant_id'];
                        $restaurant_coupons = RestaurantCoupons::findOne(['restaurant_id' => $restaurant_id, 'coupon_id' => $coupon_model->id]);
                        if (!$restaurant_coupons) {
                            break;
                        }
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $combo_id = $ccode_data['combo_id'];
                        $discount_type = $ccode_data['discount_type'];
                        $discount_value = $ccode_data['discount_value'];
                        $max_discount  = $ccode_data['maximum_discount'];
                        $combos = $cart_data['combos'];
                        foreach ($combos as $comboKey => $comboValue) {
                            $combo_model = Combo::findOne(['id' => $comboValue['id']]);
                            if ($combo_model->combo_type == 'Lunch Special' AND in_array($combo_model->id, $combo_id)) {
                                $cart_data['coupon_data']['coupon_code'] = $couponcode;
                                if ($discount_type == 'Percentage') {
                                    $cart_data['coupon_data']['discount_amount'] = floor($comboValue['price'] * $comboValue['qty'] * $discount_value / 100);
                                } else {
                                    $cart_data['coupon_data']['discount_amount'] = floor($discount_value);
                                }
                                if ($cart_data['coupon_data']['discount_amount'] > $max_discount) {
                                   $cart_data['coupon_data']['discount_amount'] = $max_discount;
                                 }
                                $this->response['status'] = 1;
                                $this->response['message'] = 'Hurray !! You just saved ' . $cart_data['coupon_data']['discount_amount'] . ' Rs.';
                                break;
                            }
                        }
                        break;
                    case "Night Pack Discounts":
                        $restaurant_id = $cart_data['restaurant_id'];
                        $restaurant_coupons = RestaurantCoupons::findOne(['restaurant_id' => $restaurant_id, 'coupon_id' => $coupon_model->id]);
                        if (!$restaurant_coupons) {
                            break;
                        }
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $combo_id = $ccode_data['combo_id'];
                        $discount_type = $ccode_data['discount_type'];
                        $discount_value = $ccode_data['discount_value'];
                        $max_discount  = $ccode_data['maximum_discount'];
                        $combos = $cart_data['combos'];
                        foreach ($combos as $comboKey => $comboValue) {
                            $combo_model = Combo::findOne(['id' => $comboValue['id']]);
                            if ($combo_model->combo_type == 'Night special' AND in_array($combo_model->id, $combo_id)) {
                                $cart_data['coupon_data']['coupon_code'] = $couponcode;
                                if ($discount_type == 'Percentage') {
                                    $cart_data['coupon_data']['discount_amount'] = floor($comboValue['price'] * $comboValue['qty'] * $discount_value / 100);
                                } else {
                                    $cart_data['coupon_data']['discount_amount'] = floor($discount_value);
                                }
                                if ($cart_data['coupon_data']['discount_amount'] > $max_discount) {
                                   $cart_data['coupon_data']['discount_amount'] = $max_discount;
                                }
                                $this->response['status'] = 1;
                                $this->response['message'] = 'Hurray !! You just saved ' . $cart_data['coupon_data']['discount_amount'] . ' Rs.';
                                break;
                            }
                        }
                        break;
                    case "Get 1 Item and 2nd one is Free":
                        $restaurant_id = $cart_data['restaurant_id'];
                        $restaurant_coupons = RestaurantCoupons::findOne(['restaurant_id' => $restaurant_id, 'coupon_id' => $coupon_model->id]);
                        if (!$restaurant_coupons) {
                            break;
                        }
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $paid_dish_id = $ccode_data['paid_dish_id']; 
                        $free_dish_id = $ccode_data['free_dish_id'];
                        $dishes_arr = array();
                        foreach ($cart_data['dishes'] as $dishKey => $dishValue) {
                            array_push($dishes_arr, $dishValue['id']);
                        }
                        if (in_array($paid_dish_id, $dishes_arr) AND in_array($free_dish_id, $dishes_arr)) {
                            $cart_data['coupon_data']['coupon_code'] = $couponcode;
                            $cart_data['coupon_data']['discount_amount'] = floor($cart_data['dishes'][array_search($free_dish_id, $dishes_arr)]['price']);
                            $this->response['status'] = 1;
                            $this->response['message'] = 'Hurray !! You just saved ' . $cart_data['coupon_data']['discount_amount'] . ' Rs.';
                        } else {
                            $this->response['status'] = 0;
                            $this->response['message'] = 'The code you entered is either invalid or expired :(';
                        }

                        break;
                    case "Particular Restaurant Discount":
                        $restaurant_id = $cart_data['restaurant_id'];
                        $restaurant_coupons = RestaurantCoupons::findOne(['restaurant_id' => $restaurant_id, 'coupon_id' => $coupon_model->id]);
                        if (!$restaurant_coupons) {
                            break;
                        }
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $discount_percentage = $ccode_data['discount_percentage'];
                        $max_discount  = $ccode_data['maximum_discount'];
                        $discount_type = $ccode_data['discount_type'];
                        $cart_data['coupon_data']['coupon_code'] = $couponcode;
                        if ($discount_type == 'Percentage') {
                            $cart_data['coupon_data']['discount_amount'] = floor($cart_data['subtotal'] * $discount_percentage / 100);
                        } else {
                            $cart_data['coupon_data']['discount_amount'] = floor($discount_percentage);
                        }
                        if ($cart_data['coupon_data']['discount_amount'] > $max_discount) {
                            $cart_data['coupon_data']['discount_amount'] = $max_discount;
                        }

                        $this->response['status'] = 1;
                        $this->response['message'] = 'Hurray !! You just saved ' . $cart_data['coupon_data']['discount_amount'] . ' Rs.';
                        break;
                    case "Buy 1 Get 1 Free":
                        $this->response['message'] = 'The code you entered is either invalid or expired :(';
                        $restaurant_id = $cart_data['restaurant_id'];
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
                        foreach ($cart_data['dishes'] as $dishKey => $dishValue) {
                            array_push($dishes_arr, $dishValue['id']);
                            array_push($price_arr, $dishValue['price']);
                            array_push($qty_arr, $dishValue['qty']);
                        }

                        if ($paid_dish_id == $free_dish_id) {
                            if (array_search($paid_dish_id, $dishes_arr) != -1) {
                                $qty = $qty_arr[array_search($paid_dish_id, $dishes_arr)];
                                if ($qty > 1) {
                                    $cart_data['coupon_data']['coupon_code'] = $couponcode;
                                    $cart_data['coupon_data']['discount_amount'] = floor($price_arr[array_search($paid_dish_id, $dishes_arr)]);
                                    $this->response['status'] = 1;
                                    $this->response['message'] = 'Hurray!! You just saved ' . $cart_data['coupon_data']['discount_amount'] . ' Rs.';
                                } else {
                                    $dishModel = Dish::findOne($paid_dish_id);
                                    unset($cart_data['coupon_data']);
                                    $this->response['status'] = 0;
                                    $this->response['message'] = 'Please add one more ' . $dishModel->title . ' and try again to get discount.';
                                }
                            }
                        } else {
                            $paid_intersect = array_intersect($dishes_arr, $paid_dish_id);
                            $free_intersect = array_intersect($dishes_arr, $free_dish_id);

                            if ((!empty($paid_intersect)) AND (!empty($free_intersect))) {
                                foreach ($dishes_arr as $dishKey => $dishValue) {
                                    if (!(in_array($dishValue, $free_dish_id))) {
                                        unset($price_arr[$dishKey]);
                                    }
                                }
                                $cart_data['coupon_data']['coupon_code'] = $couponcode;
                                $cart_data['coupon_data']['discount_amount'] = floor(min($price_arr));
                                $this->response['status'] = 1;
                                $this->response['message'] = 'Hurray !! You just saved ' . $cart_data['coupon_data']['discount_amount'] . ' Rs.';
                            }
                        }


                        break;
                    case "Weekly Deals":
                        $restaurant_id = $cart_data['restaurant_id'];
                        $restaurant_coupons = RestaurantCoupons::findOne(['restaurant_id' => $restaurant_id, 'coupon_id' => $coupon_model->id]);
                        if (!$restaurant_coupons) {
                            break;
                        }
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $today = \Yii::$app->formatter->asDate('now', 'eeee');
                        $week_days = $ccode_data['week_days'];
                        $discount_percentage = $ccode_data['discount_percentage'];
                        $max_discount  = $ccode_data['maximum_discount'];
                        $discount_type = $ccode_data['discount_type'];
                        if (in_array($today, $week_days)) {
                            $cart_data['coupon_data']['coupon_code'] = $couponcode;
                            if ($discount_type == 'Percentage') {
                                $cart_data['coupon_data']['discount_amount'] = floor($cart_data['subtotal'] * $discount_percentage / 100);
                            } else {
                                $cart_data['coupon_data']['discount_amount'] = floor($discount_percentage);
                            }
                            if ($cart_data['coupon_data']['discount_amount'] > $max_discount) {
								$cart_data['coupon_data']['discount_amount'] = $max_discount;
                             }
                            $this->response['status'] = 1;
                            $this->response['message'] = 'Hurray !! You just saved ' . $cart_data['coupon_data']['discount_amount'] . ' Rs.';
                        } else {
                            $this->response['status'] = 0;
                            $this->response['message'] = 'Opps! Deal is not available for today :(';
                        }
                        break;
                    case "Share order link to 3 friends and get one order free":
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $discount_value = $ccode_data['discount_value'];
                        $user_id = $ccode_data['user_id'];
                        if ($user_id == $cart_data['user_id']) {
                            $cart_data['coupon_data']['coupon_code'] = $couponcode;
                            $cart_data['coupon_data']['discount_amount'] = floor($cart_data['subtotal'] < $discount_value ? $cart_data['subtotal'] : $discount_value);
                            $this->response['status'] = 1;
                            $this->response['message'] = 'Hurray !! You just saved ' . $cart_data['coupon_data']['discount_amount'] . ' Rs.';
                            \Yii::$app->getSession()->setFlash('coupon_code_success', 'Hurray !! You just saved ' . $cart_data['coupon_data']['discount_amount'] . ' Rs.');
                        } else {
                            $this->response['status'] = 0;
                            $this->response['message'] = 'The code you entered is either invalid or expired :(';
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
                            $cart_data['coupon_data']['coupon_code'] = $couponcode;
                            if ($discount_type == 'Percentage') {
                                $cart_data['coupon_data']['discount_amount'] = floor(($cart_data['subtotal'] * $discount_percentage / 100) > $max_discount ? $max_discount : ($cart_data['subtotal'] * $discount_percentage / 100));
                            } else {
                                $cart_data['coupon_data']['discount_amount'] = floor($discount_percentage);
                            }
                            $this->response['status'] = 1;
                            $this->response['message'] = 'Hurray!! You just saved ' . $cart_data['coupon_data']['discount_amount'] . ' Rs.';
                            \Yii::$app->getSession()->setFlash('coupon_code_success', 'Hurray!! You just saved ' . $cart_data['coupon_data']['discount_amount'] . ' Rs.');
                        } else {
                            $this->response['status'] = 0;
                            $this->response['message'] = 'Opps! Deal is not available for today :(';
                        }
                        break;
                    case "Weekly Deals - Discount on Particular Dishes":
                        $restaurant_id = $cart_data['restaurant_id'];
                        $restaurant_coupons = RestaurantCoupons::findOne(['restaurant_id' => $restaurant_id, 'coupon_id' => $coupon_model->id]);
                        if (!$restaurant_coupons) {
                            break;
                        }
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $discount_percentage = $ccode_data['discount_percentage'];
                        
                        $discount_type = $ccode_data['discount_type'];
                        $max_discount  = $ccode_data['maximum_discount'];
                        $discount_dish_id = $ccode_data['dish_id'];
                        $dishes_in_cart = $cart_data['dishes'];
                        $dish_key = $this->checkDishPresent($discount_dish_id, $dishes_in_cart);
                        $today = \Yii::$app->formatter->asDate('now', 'eeee');
                        $week_days = $ccode_data['week_days'];
                        if (in_array($today, $week_days)) {
                            if ($dish_key >= 0) {
                                $cart_data['coupon_data']['coupon_code'] = $couponcode;
                                if ($discount_type == 'Percentage') {
                                    $cart_data['coupon_data']['discount_amount'] = floor($cart_data['dishes'][$dish_key]['price'] * $discount_percentage / 100);
                                } else {
                                    $cart_data['coupon_data']['discount_amount'] = floor($discount_percentage);
                                }
                                if ($cart_data['coupon_data']['discount_amount'] > $max_discount) {
                                  $cart_data['coupon_data']['discount_amount'] = $max_discount;
                                }
                                $this->response['status'] = 1;
                                $this->response['message'] = 'Hurray!! You just saved ' . $cart_data['coupon_data']['discount_amount'] . ' Rs.';
                            } else {
                                $this->response['status'] = 0;
                                $this->response['message'] = 'The code you entered is either invalid or expired :( ';
                            }
                        } else {
                            $this->response['status'] = 0;
                            $this->response['message'] = 'Opps! Deal is not available for today :(';
                        }
                        break;
                    case "Weekly Deals - Discount on Particular Menu":
                        $restaurant_id = $cart_data['restaurant_id'];
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
                        $dishes_in_cart = $cart_data['dishes'];
                        $discount_amount = 0;
                        foreach ($dishes_in_cart as $dishKey => $dishValue) {
                            $dish_model = Dish::findOne(['id' => $dishValue['id']]);
                            if ($dish_model->menu_id == $discount_menu_id) {
                                $discount_amount += ($dishValue['price'] * $dishValue['qty'] * $discount_percentage / 100);
                            }
                        }
                        if (in_array($today, $week_days)) {
                            if ($discount_amount > 0) {
                                $cart_data['coupon_data']['coupon_code'] = $couponcode;
                                if ($discount_type == 'Percentage') {
                                    $cart_data['coupon_data']['discount_amount'] = ($discount_amount > $max_discount ? $max_discount : floor($discount_amount));
                                } else {
                                    $cart_data['coupon_data']['discount_amount'] = floor($discount_percentage);
                                }
                                if ($cart_data['coupon_data']['discount_amount'] > $max_discount) {
                                     $cart_data['coupon_data']['discount_amount'] = $max_discount;
                                 }
                                $this->response['status'] = 1;
                                $this->response['message'] = 'Hurray!! You just saved ' . $cart_data['coupon_data']['discount_amount'] . ' Rs.';
                            } else {
                                $this->response['status'] = 0;
                                $this->response['message'] = 'The code you entered is either invalid or expired :(';
                            }
                        } else {
                            $this->response['status'] = 0;
                            $this->response['message'] = 'Opps! Deal is not available for today :(';
                        }
                        break;
                    case "Weekly Deals - Discount on Minimum Order - Restaurant":
                        $restaurant_id = $cart_data['restaurant_id'];
                        $restaurant_coupons = RestaurantCoupons::findOne(['restaurant_id' => $restaurant_id, 'coupon_id' => $coupon_model->id]);
                        if (!$restaurant_coupons) {
                            break;
                        }
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $minimum_order = $ccode_data['minimum_order'];
                        $discount_type = $ccode_data['discount_type'];
                        $max_discount  = $ccode_data['maximum_discount'];
                        $discount_percentage = $ccode_data['discount_percentage'];
                        $today = \Yii::$app->formatter->asDate('now', 'eeee');
                        $week_days = $ccode_data['week_days'];
                        if (in_array($today, $week_days)) {
                            if ($cart_data['subtotal'] >= $minimum_order) {
                                $cart_data['coupon_data']['coupon_code'] = $couponcode;
                                if ($discount_type == 'Percentage') {
                                    $cart_data['coupon_data']['discount_amount'] = floor($cart_data['subtotal'] * $discount_percentage / 100);
                                } else {
                                    $cart_data['coupon_data']['discount_amount'] = floor($discount_percentage);
                                }
                                if ($cart_data['coupon_data']['discount_amount'] > $max_discount) {
                                   $cart_data['coupon_data']['discount_amount'] = $max_discount;
                                }
                                $this->response['status'] = 1;
                                $this->response['message'] = 'Hurray!! You just saved ' . $cart_data['coupon_data']['discount_amount'] . ' Rs.';
                            } else {
                                $this->response['status'] = 0;
                                $this->response['message'] = 'Opps! You must have minimum order of ' . $minimum_order . ' Rs. to use this coupon :(';
                            }
                        } else {
                            $this->response['status'] = 0;
                            $this->response['message'] = 'Opps! Deal is not available for today :(';
                        }
                        break;
                    case "Weekly Deals - Discount on Minimum Order":
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $minimum_order = $ccode_data['minimum_order'];
                        $discount_type = $ccode_data['discount_type'];
                        $max_discount  = $ccode_data['maximum_discount'];
                        $discount_percentage = $ccode_data['discount_percentage'];
                        $today = \Yii::$app->formatter->asDate('now', 'eeee');
                        $week_days = $ccode_data['week_days'];
                        if (in_array($today, $week_days)) {
                            if ($cart_data['subtotal'] >= $minimum_order) {
                                $cart_data['coupon_data']['coupon_code'] = $couponcode;
                                if ($discount_type == 'Percentage') {
                                    $cart_data['coupon_data']['discount_amount'] = floor($cart_data['subtotal'] * $discount_percentage / 100);
                                } else {
                                    $cart_data['coupon_data']['discount_amount'] = floor($discount_percentage);
                                }
                                if ($cart_data['coupon_data']['discount_amount'] > $max_discount) {
                                     $cart_data['coupon_data']['discount_amount'] = $max_discount;
                                   }
                                $this->response['status'] = 1;
                                $this->response['message'] = 'Hurray!! You just saved ' . $cart_data['coupon_data']['discount_amount'] . ' Rs.';
                            } else {
                                $this->response['status'] = 0;
                                $this->response['message'] = 'Opps! You must have minimum order of ' . $minimum_order . ' Rs. to use this coupon :(';
                            }
                        } else {
                            $this->response['status'] = 0;
                            $this->response['message'] = 'Opps! Deal is not available for today :(';
                        }
                        break;
                    case "Weekly Deals - Buy 1 Item the other Item X% off":
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $discount_value = $ccode_data['discount_value'];
                        $max_discount  = $ccode_data['maximum_discount'];
                        $discount_type = $ccode_data['discount_type'];
                        $today = \Yii::$app->formatter->asDate('now', 'eeee');
                        $week_days = $ccode_data['week_days'];
                        $price_arr = array();
                        if (in_array($today, $week_days)) {
                            if ((count($cart_data['dishes']) + count($cart_data['combos']) ) >= 2) {
                                foreach ($cart_data['dishes'] as $dishKey => $dishValue) {
                                    array_push($price_arr, $dishValue['price']);
                                }
                                foreach ($cart_data['combos'] as $comboKey => $comboValue) {
                                    array_push($price_arr, $comboValue['price']);
                                }
                                $min_price = min($price_arr);
                                $cart_data['coupon_data']['coupon_code'] = $couponcode;
                                if ($discount_type == 'Percentage') {
                                    $cart_data['coupon_data']['discount_amount'] = floor($min_price * $discount_value / 100);
                                } else {
                                    $cart_data['coupon_data']['discount_amount'] = floor($discount_value);
                                }
                                if ($cart_data['coupon_data']['discount_amount'] > $max_discount) {
                                    $cart_data['coupon_data']['discount_amount'] = $max_discount;
                                }
                                $this->response['status'] = 1;
                                $this->response['message'] = 'Hurray!! You just saved ' . $cart_data['coupon_data']['discount_amount'] . ' Rs.';
                            } else {
                                $this->response['status'] = 0;
                                $this->response['message'] = 'Opps! You must have atleast 2 items in the cart :(';
                            }
                        } else {
                            $this->response['status'] = 0;
                            $this->response['message'] = 'Opps! Deal is not available for today :(';
                        }
                        break;
                    case "Weekly Deals - Lunch Pack Discounts":
                        $restaurant_id = $cart_data['restaurant_id'];
                        $restaurant_coupons = RestaurantCoupons::findOne(['restaurant_id' => $restaurant_id, 'coupon_id' => $coupon_model->id]);
                        if (!$restaurant_coupons) {
                            break;
                        }
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $combo_id = $ccode_data['combo_id'];
                        $discount_type = $ccode_data['discount_type'];
                        $discount_value = $ccode_data['discount_value'];
                         $max_discount  = $ccode_data['maximum_discount'];
                        $today = \Yii::$app->formatter->asDate('now', 'eeee');
                        $week_days = $ccode_data['week_days'];
                        $combos = $cart_data['combos'];
                        if (in_array($today, $week_days)) {
                            foreach ($combos as $comboKey => $comboValue) {
                                $combo_model = Combo::findOne(['id' => $comboValue['id']]);
                                if ($combo_model->combo_type == 'Lunch Special' AND in_array($combo_model->id, $combo_id)) {
                                    $cart_data['coupon_data']['coupon_code'] = $couponcode;
                                    if ($discount_type == 'Percentage') {
                                        $cart_data['coupon_data']['discount_amount'] = floor($comboValue['price'] * $comboValue['qty'] * $discount_value / 100);
                                    } else {
                                        $cart_data['coupon_data']['discount_amount'] = floor($discount_value);
                                    }
                                    if ($cart_data['coupon_data']['discount_amount'] > $max_discount) {
                                          $cart_data['coupon_data']['discount_amount'] = $max_discount;
                                     }
                                    $this->response['status'] = 1;
                                    $this->response['message'] = 'Hurray!! You just saved ' . $cart_data['coupon_data']['discount_amount'] . ' Rs.';
                                    break;
                                }
                            }
                        } else {
                            $this->response['status'] = 0;
                            $this->response['message'] = 'Opps! Deal is not available for today :(';
                        }
                        break;
                    case "Weekly Deals - Night Pack Discounts":
                        $restaurant_id = $cart_data['restaurant_id'];
                        $restaurant_coupons = RestaurantCoupons::findOne(['restaurant_id' => $restaurant_id, 'coupon_id' => $coupon_model->id]);
                        if (!$restaurant_coupons) {
                            break;
                        }
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $combo_id = $ccode_data['combo_id'];
                        $discount_type = $ccode_data['discount_type'];
                        $discount_value = $ccode_data['discount_value'];
                        $max_discount  = $ccode_data['maximum_discount'];
                        $today = \Yii::$app->formatter->asDate('now', 'eeee');
                        $week_days = $ccode_data['week_days'];
                        $combos = $cart_data['combos'];
                        if (in_array($today, $week_days)) {
                            foreach ($combos as $comboKey => $comboValue) {
                                $combo_model = Combo::findOne(['id' => $comboValue['id']]);
                                if ($combo_model->combo_type == 'Night special' AND in_array($combo_model->id, $combo_id)) {
                                    $cart_data['coupon_data']['coupon_code'] = $couponcode;
                                    if ($discount_type == 'Percentage') {
                                        $cart_data['coupon_data']['discount_amount'] = floor($comboValue['price'] * $comboValue['qty'] * $discount_value / 100);
                                    } else {
                                        $cart_data['coupon_data']['discount_amount'] = floor($discount_value);
                                    }
                                    if ($cart_data['coupon_data']['discount_amount'] > $max_discount) {
										$cart_data['coupon_data']['discount_amount'] = $max_discount;
									}
                                    $this->response['status'] = 1;
                                    $this->response['message'] = 'Hurray!! You just saved ' . $cart_data['coupon_data']['discount_amount'] . ' Rs.';
                                    break;
                                }
                            }
                        } else {
                            $this->response['status'] = 0;
                            $this->response['message'] = 'Opps! Deal is not available for today :(';
                        }
                        break;
                    case "Weekly Deals - Get 1 Item and 2nd one is Free";
                        $restaurant_id = $cart_data['restaurant_id'];
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
                        foreach ($cart_data['dishes'] as $dishKey => $dishValue) {
                            array_push($dishes_arr, $dishValue['id']);
                        }
                        if (in_array($today, $week_days)) {
                            if (in_array($paid_dish_id, $dishes_arr) AND in_array($free_dish_id, $dishes_arr)) {
                                $cart_data['coupon_data']['coupon_code'] = $couponcode;
                                $cart_data['coupon_data']['discount_amount'] = floor($cart_data['dishes'][array_search($free_dish_id, $dishes_arr)]['price']);
                                $this->response['status'] = 1;
                                $this->response['message'] = 'Hurray!! You just saved ' . $cart_data['coupon_data']['discount_amount'] . ' Rs.';
                            } else {
                                $this->response['status'] = 0;
                                $this->response['message'] = 'The code you entered is either invalid or expired :(';
                            }
                        } else {
                            $this->response['status'] = 0;
                            $this->response['message'] = 'Opps! Deal is not available for today :(';
                        }
                        break;
                    case "Weekly Deals - Particular Restaurant Discount":
                        $restaurant_id = $cart_data['restaurant_id'];
                        $restaurant_coupons = RestaurantCoupons::findOne(['restaurant_id' => $restaurant_id, 'coupon_id' => $coupon_model->id]);
                        if (!$restaurant_coupons) {
                            break;
                        }
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $discount_percentage = $ccode_data['discount_percentage'];
                        $discount_type = $ccode_data['discount_type'];
                        $max_discount  = $ccode_data['maximum_discount'];
                        $today = \Yii::$app->formatter->asDate('now', 'eeee');
                        $week_days = $ccode_data['week_days'];
                        if (in_array($today, $week_days)) {
                            $cart_data['coupon_data']['coupon_code'] = $couponcode;
                            if ($discount_type == 'Percentage') {
                                $cart_data['coupon_data']['discount_amount'] = floor($cart_data['subtotal'] * $discount_percentage / 100);
                            } else {
                                $cart_data['coupon_data']['discount_amount'] = floor($discount_percentage);
                            }
                            if ($cart_data['coupon_data']['discount_amount'] > $max_discount) {
								$cart_data['coupon_data']['discount_amount'] = $max_discount;
							}
                            $this->response['status'] = 1;
                            $this->response['message'] = 'Hurray!! You just saved ' . $cart_data['coupon_data']['discount_amount'] . ' Rs.';
                        } else {
                            $this->response['status'] = 0;
                            $this->response['message'] = 'Opps! Deal is not available for today :(';
                        }
                        break;
                    case "Weekly Deals - Buy 1 Get 1 Free";
                        $this->response['message'] = 'The code you entered is either invalid or expired :(';
                        $restaurant_id = $cart_data['restaurant_id'];
                        $restaurant_coupons = RestaurantCoupons::findOne(['restaurant_id' => $restaurant_id, 'coupon_id' => $coupon_model->id]);
                        if (!$restaurant_coupons) {
                            break;
                        }
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $paid_dish_id = $ccode_data['paid_dish_id'];
                        $free_dish_id = $ccode_data['free_dish_id'];
                        $price_arr = array();
                        $dishes_arr = array();
                        foreach ($cart_data['dishes'] as $dishKey => $dishValue) {
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
                                $cart_data['coupon_data']['coupon_code'] = $couponcode;
                                $cart_data['coupon_data']['discount_amount'] = floor(min($price_arr));
                                $this->response['status'] = 1;
                                $this->response['message'] = 'Hurray!! You just saved ' . $cart_data['coupon_data']['discount_amount'] . ' Rs.';
                            }
                        } else {
                            $this->response['status'] = 0;
                            $this->response['message'] = 'Opps! Deal is not available for today :(';
                        }
                        break;
                    case "Discount on 1st Order":
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $discount_type = $ccode_data['discount_type'];
                        $discount_value = $ccode_data['discount_value'];
                        $max_discount  = $ccode_data['maximum_discount'];
                        $user_order_count = Order::find()->where(['user_id' => $cart_data['user_id']])->count();
                        if ($user_order_count == 0 AND $cart_data['user_id']) {
                            $cart_data['coupon_data']['coupon_code'] = $couponcode;
                            if ($discount_type == 'Percentage') {
                                $cart_data['coupon_data']['discount_amount'] = floor($cart_data['subtotal'] * $discount_value / 100);
                            } else {
                                $cart_data['coupon_data']['discount_amount'] = floor($discount_value);
                            }
                            if ($cart_data['coupon_data']['discount_amount'] > $max_discount) {
								$cart_data['coupon_data']['discount_amount'] = $max_discount;
							}
                            $this->response['status'] = 1;
                            $this->response['message'] = 'Hurray!! You just saved ' . $cart_data['coupon_data']['discount_amount'] . ' Rs.';
                        } else {
                            $this->response['status'] = 0;
                            $this->response['message'] = 'The code you entered is either invalid or expired :(';
                        }
                        
                        break;
                    default:
                        echo "";
                }
            } else {
                $this->response['status'] = 0;
                $this->response['message'] = 'Opps! Coupon has expired :(';
                //REMOVE COUPON SESSION DATA
                unset($cart_data['coupon_data']);
            }
        } else {

            $this->response['status'] = 0;
            $this->response['message'] = "Invalid coupon code";
            //REMOVE COUPON SESSION DATA
            unset($cart_data['coupon_data']);
        }
        if ($this->response['status'] == 0) {

            $cart_data['discounted_subtotal'] = $cart_data['subtotal'];
            $cart_data['grand_total'] = $cart_data['subtotal'] + $cart_data['tax'] + $cart_data['vat'] + $cart_data['service_charge'];
            unset($cart_data['coupon_data']);
        }

        $this->response['cart'] = $cart_data;
        return $this->response;
    }

    public function actionApplytablecoupon() {
        $cart_data = Json::decode(Yii::$app->request->post('cart'));
        $couponcode = Yii::$app->request->post('couponcode');
        $coupon_model = Coupons::findOne(['code' => $couponcode]);

        if ($coupon_model) {
//            print_r($coupon_model);
            if ($coupon_model->status == 'Active') {
                switch ($coupon_model->coupon_key) {
                    case "Table Booking discount":
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $max_discount = $ccode_data['maximum_discount'];
                        $discount_percentage = $ccode_data['discount_percentage'];
                        $cart_data['coupon_data']['coupon_code'] = $couponcode;
                        $cart_data['coupon_data']['discount_amount'] = floor(($cart_data['subtotal'] * $discount_percentage / 100) > $max_discount ? $max_discount : ($cart_data['subtotal'] * $discount_percentage / 100));
                        $this->response['status'] = 1;
                        $this->response['message'] = 'Hurray !! You just saved ' . $cart_data['coupon_data']['discount_amount'] . ' Rs.';
                        break;
                    case "Table Booking discount - Restaurant":
                        $restaurant_id = $cart_data['restaurant_id'];
                        $restaurant_coupons = RestaurantCoupons::findOne(['restaurant_id' => $restaurant_id, 'coupon_id' => $coupon_model->id]);
                        if (!$restaurant_coupons) {
                            break;
                        }
                        $ccode_data = Json::decode($coupon_model->coupon_perameter);
                        $max_discount = $ccode_data['maximum_discount'];
                        $discount_percentage = $ccode_data['discount_percentage'];
                        $cart_data['coupon_data']['coupon_code'] = $couponcode;
                        $cart_data['coupon_data']['discount_amount'] = floor(($cart_data['subtotal'] * $discount_percentage / 100) > $max_discount ? $max_discount : ($cart_data['subtotal'] * $discount_percentage / 100));
                        $this->response['status'] = 1;
                        $this->response['message'] = 'Hurray !! You just saved ' . $cart_data['coupon_data']['discount_amount'] . ' Rs.';
                        break;



                    default:
                        echo "";
                }
            } else {
                $this->response['status'] = 0;
                $this->response['message'] = 'Opps! Coupon has expired :(';
                //REMOVE COUPON SESSION DATA
                unset($cart_data['coupon_data']);
            }
        } else {
            $this->response['status'] = 0;
            $this->response['message'] = 'Invalid coupon code :(';
            //REMOVE COUPON SESSION DATA
            unset($cart_data['coupon_data']);
        }
        if ($this->response['status'] == 0) {
            $cart_data['discounted_subtotal'] = $cart_data['subtotal'];
            $cart_data['grand_total'] = $cart_data['subtotal'];
            unset($cart_data['coupon_data']);
        } else {
            $cart_data['discounted_subtotal'] = $cart_data['subtotal'] - $cart_data['coupon_data']['discount_amount'];
            $cart_data['grand_total'] = $cart_data['discounted_subtotal'];
        }
        $this->response['table_cart'] = $cart_data;
        return $this->response;
    }

    public function checkForCouponDesctive($cart_data) {
        $coupon_code = $cart_data['coupon_data']['coupon_code'];
        $coupon_model = Coupons::findOne(['code' => $coupon_code]);
        if ($coupon_model) {
            switch ($coupon_model->coupon_key) {
                case "Discount for 1 person and 1 time use only":
                    $ccode_data = Json::decode($coupon_model->coupon_perameter);
                    if ($cart_data['user_id'] == $ccode_data['user_id']) {
                        $coupon_model->status = 'Inactive';
                        $coupon_model->save(FALSE);
                    }
                    break;
                case "Code for person to use multiple times":
                    $ccode_data = Json::decode($coupon_model->coupon_perameter);
                    if ($cart_data['user_id'] == $ccode_data['user_id']) {
                        $ccode_data['no_of_usage'] = $ccode_data['no_of_usage'] + 1;
                        $coupon_model->coupon_perameter = Json::encode($ccode_data);
                        if ($ccode_data['no_of_usage'] >= $ccode_data['max_no_of_usage']) {
                            $coupon_model->status = 'Inactive';
                        }
                        $coupon_model->save(FALSE);
                    }
                    break;
                case "Share order link to 3 friends and get one order free":
                    $coupon_model->status = 'Inactive';
                    $coupon_model->save(FALSE);
                    break;
                default:
                    echo "";
            }
        }
    }

    public function actionDeals() {

        $coupons = Coupons::find()->where(['status' => 'Active', 'notify' => 'Yes'])->orderBy('created_on DESC')->all();
        $coupon_arr = array();
        foreach ($coupons as $key => $coupon) {
            if ($coupon->type == "Restaurant") {
                $rest = Restaurant::findOne(['id' => $coupon->restaurantCoupons[0]->restaurant_id]);
                $logo = $rest->logo;
                $restaurant_id = $rest->id;
            } else {
                $logo = "";
                $restaurant_id = '';
            }
            array_push($coupon_arr, array(
                'code' => $coupon->code,
                'title' => $coupon->title,
                'description' => $coupon->description,
                'type' => $coupon->type,
                'logo' => $logo,
                'restaurant_id' => $restaurant_id
            ));
        }
        if ($coupons) {
            $this->response['status'] = 1;
            $this->response['message'] = "List of coupons";
            $this->response['data'] = $coupon_arr;
        } else {
            $this->response['status'] = 0;
            $this->response['message'] = "No coupons found";
        }
        return $this->response;
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
