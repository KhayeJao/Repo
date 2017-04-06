<?php

namespace api\modules\v1\controllers;

use api\modules\v1\models\Order;
use yii\rest\Controller;
use yii\web\HttpException;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Response;
use Yii;
use kartik\mpdf\Pdf;
use api\modules\v1\models\OrderPayments;
use api\modules\v1\models\OrderTopping;
use api\modules\v1\models\User;
use api\modules\v1\models\OrderDish;
use api\modules\v1\models\OrderCombo;
use api\modules\v1\models\OrderComboDish;
use common\components\Sms;
use api\modules\v1\models\Restaurant;
use api\modules\v1\models\Address;
use api\modules\v1\models\Dish;
use api\modules\v1\models\Combo;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends Controller {

    public $modelClass = 'api\modules\v1\models\Order';
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
                    'actions' => ['orders', 'orderview', 'outputinfo', 'place'],
                //  'roles' => ['@']
                ]
            ]
        );
        return $behaviors;
    }

    public function actionPlace() {
        $cart_data = Json::decode(Yii::$app->request->post('cart'));
        $post_data = Json::decode(Yii::$app->request->post('order'));

        $this->response['status'] = 0;
        $this->response['message'] = 'Could not place order';
        $user = User::findOne(['id' => $cart_data['user_id'], 'type' => 'customer']);
        if (!$user) {
            $this->response['status'] = 0;
            $this->response['message'] = 'Invalid user';
            return $this->response;
        }

        


        //PREPARE ORDER DATA
        $order_model = new Order();
        $restaurant_model = Restaurant::findOne(['id' => $cart_data['restaurant_id']]);
        $order_model->load($post_data);

        $order_model->order_unique_id = $this->getRandomOrderID();
        $order_model->restaurant_id = $restaurant_model->id;
        $order_model->affiliate_order_id = "";


        if ($post_data['Order']['user_id'] && $order_model->delivery_type != 'Pickup') {
            $address_model = Address::findOne(['id' => $post_data['address_id']]);
            $order_model->address_line_1 = $address_model->address_line_1;
            $order_model->address_line_2 = $address_model->address_line_2;
            $order_model->area = $address_model->area;
            $order_model->city = $address_model->city;
            $order_model->pincode = $address_model->pincode;
        }
//        else {
//            $this->response['status'] = 0;
//            $this->response['message'] = "Invalid address selected";
//            return $this->response;
//        }



        if ($post_data['delivery_time_radio'] == "Now") {
            $order_model->delivery_time = date('Y-m-d H:i:s', time());
//            echo 'Now';
        } else if ($post_data['delivery_time_radio'] == 'Pre-Order') {
            $order_model->delivery_time = date('Y-m-d H:i:s', strtotime($post_data['delivery_date_input'] . ' ' . $post_data['delivery_time_input'] . ':00'));
//            echo 'Preorder';
        }
        if (isset($cart_data['coupon_data'])) {
            $order_model->coupon_code = $cart_data['coupon_data']['coupon_code'];
            $order_model->discount_amount = $cart_data['coupon_data']['discount_amount'];
        } else {
            $order_model->coupon_code = '';
            $order_model->discount_amount = '';
        }

        $order_model->order_items = count($cart_data['dishes']);
        if (isset($cart_data['combos'])) {
            $order_model->order_items = $order_model->order_items + count($cart_data['combos']);
        }
        $order_model->tax = number_format($cart_data['tax'], 2);
        $order_model->tax_text = $restaurant_model->tax . ' % tax';

        $order_model->vat = $cart_data['vat'];
        $order_model->vat_text = $restaurant_model->vat . ' % vat';

        $order_model->service_charge = $cart_data['service_charge'];
        if ($restaurant_model->scharge_type == 'Percentage') {
            $order_model->service_charge_text = $restaurant_model->service_charge . ' % service charge + ' . $cart_data['delivery_charge'] . ' Rs delivery charge';
        } else {
            $order_model->service_charge_text = $restaurant_model->service_charge . ' Rs service charge + ' . $cart_data['delivery_charge'] . ' Rs delivery charge';
        }
        $order_model->sub_total = $cart_data['subtotal'];
        $order_model->grand_total = $cart_data['grand_total'];

        $order_model->booking_time = date('Y-m-d H:i:s', time());
        $order_model->order_ip = yii::$app->request->userIP;
        $order_model->status = 'Placed';
        if ($cart_data['placed_via'] == 'android') {
            $order_model->placed_via = 'Android';
        } else if ($cart_data['placed_via'] == 'ios') {
            $order_model->placed_via = 'iOS';
        } else {
            $order_model->placed_via = 'Website';
        }


        $order_model->insert(FALSE);
        if (!empty($order_model->errors)) {
            $keys = array_keys($order_model->errors);
            $this->response['status'] = 0;
            $this->response['message'] = $order_model->errors[$keys[0]][0];
            return $this->response;
        }

        //PREPARE DISH DATA AND INSERT TO DB
        foreach ($cart_data['dishes'] as $dishKey => $dishValue) {
            $dishes_order_model = new OrderDish();
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
                    $order_topping_model = new OrderTopping();
                    $order_topping_model->order_id = $order_model->id;
                    $order_topping_model->dish_id = $dishValue['id'];
                    $order_topping_model->topping_id = $toppingValue['topping_id'];
                    $order_topping_model->price = $toppingValue['price'];
                    $order_topping_model->insert(FALSE);
                }
            }
        }

        if (isset($cart_data['combos'])) {
            //PREPARE COMBO DATA AND INSERT TO DB
            foreach ($cart_data['combos'] as $comboKey => $comboValue) {
                $combo_order_model = new OrderCombo();
                $combo_model = Combo::findOne(['id' => $comboValue['id']]);
                $combo_order_model->order_id = $order_model->id;
                $combo_order_model->combo_id = $comboValue['id'];
                $combo_order_model->combo_qty = $comboValue['qty'];
                $combo_order_model->price = $combo_model->price;
                $combo_order_model->insert(FALSE);
                foreach ($combo_model->comboDishes as $comboDishesKey => $comboDishesValue) {
                    $order_combo_dish_model = new OrderComboDish();
                    $order_combo_dish_model->order_combo_id = $combo_order_model->id;
                    $order_combo_dish_model->dish_id = $comboDishesValue->dish_id;
                    $order_combo_dish_model->dish_qry = $comboDishesValue->dish_qty;
                    $order_combo_dish_model->insert(FALSE);
                }
            }
        }
        
        //CHECK IF COUPON HAS USED AND APPLICABLE TO EXPIRE THAT COUPON
        if (isset($cart_data['coupon_data'])) {
            CouponsController::checkForCouponDesctive($cart_data);
        }


        /*         * * SENDING SMSs ** */
        $this->sendOdderSms($order_model->id);

        /*         * * SENDING EMAILs ** */
        $this->sendOrderEmails($order_model->id);

        $this->response['status'] = 1;
        $this->response['message'] = "Order placed successfully";
        $this->response['order_info'] = array(
            'id' => $order_model->id,
            'order_unique_id' => $order_model->order_unique_id,
            'delivery_time' => $order_model->delivery_time,
            'delivery_type' => $order_model->delivery_type,
            'booking_time' => $order_model->booking_time,
        );
        return $this->response;
    }

    public function actionOrders() {
        $id = Yii::$app->request->post('id');
        $orders = Order::findAll(['user_id' => $id]);
        $order_arr = array();
        foreach ($orders as $key => $order) {
            $model = Order::findOne($order->id);
            $restaurant_title = $model->restaurant->title;
            array_push($order_arr, array(
                'id' => $order->id,
                'user_id' => $order->user_id,
                'restaurant_id' => $order->restaurant_id,
                'restaurant_title' => $restaurant_title,
                'user_full_name' => $order->user_full_name,
                'mobile' => $order->mobile,
                'email' => $order->email,
                'delivery_time' => Yii::$app->formatter->asDatetime($order->delivery_time, "php:M d, Y H:i:s A"),
                'booking_time' => Yii::$app->formatter->asDatetime($order->booking_time, "php:M d, Y H:i:s A"),
                'grand_total' => $order->grand_total,
                'status' => $order->status,
            ));
        }
        if ($orders) {
            $this->response['status'] = 1;
            $this->response['message'] = "List of Orders";
            $this->response['data'] = $order_arr;
        } else {
            $this->response['status'] = 0;
            $this->response['message'] = "No Orders found";
        }
        return $this->response;
    }

    public function actionOrderview() {
        $id = Yii::$app->request->post('id');
        $order = Order::findOne($id);
        $order_payment_model = OrderPayments::findOne(['order_id' => $id]);
        $restaurant_title = $order->restaurant->title;
        $payment_mode = $order->payment_mode == 'COD' ? 'Cash On Delivery' : 'Through ' . $order->payment_mode;
        $order_datetime = Yii::$app->formatter->asDatetime($order->booking_time);
        if ($order->payment_mode != "COD") {
            $order_payment_model = OrderPayments::findOne(['order_id' => $model->id]);
            $payment_info = $order_payment_model->payment_info;
            $payment_datetime = Yii::$app->formatter->asDatetime($order_payment_model->payment_datetime);
        } else {
            $payment_info = "";
            $payment_datetime = "";
        }
        if ($order->coupon_code) {
            $Discount = $order->discount_amount . ($order->discount_text ? "( " . $order->discount_text . ")" : "");
        } else {
            $Discount = 'N/A';
        }
        $dishes = array();
        foreach ($order->orderDishes as $dishKey => $dishValue) {
            $order_toppings = OrderTopping::findAll(['dish_id' => $dishValue->dish_id, 'order_id' => $order->id]);
            $topping = array();
            foreach ($order_toppings as $orderToppingKey => $orderToppingValue) {
                if ($orderToppingValue->price) {
                    $topping_price = $orderToppingValue->price;
                } else {
                    $topping_price = '<i>Free</i>';
                }
                array_push($topping, array(
                    'topping_title' => $dishValue->dish_title . " - " . $orderToppingValue->topping->title,
                    'topping_price' => $topping_price
                ));
            }
            array_push($dishes, array(
                'dish_title' => $dishValue->dish_title,
                'dish_comment' => ($dishValue->comment ? $dishValue->comment : '-'),
                'dish_qty' => $dishValue->dish_qty,
                'dish_price' => $dishValue->dish_price,
                'price_total' => $dishValue->dish_price * $dishValue->dish_qty,
                'toppings' => $topping,
            ));
        }
        $combo = array();
        foreach ($order->orderCombos as $comboKey => $comboValue) {
            $combo_dishes_arr = array();
            foreach ($comboValue->orderComboDishes as $comboDishesKey => $comboDishesValue) {
                array_push($combo_dishes_arr, ($comboDishesValue->dish_qry > 1 ? $comboDishesValue->dish_qry : '') . " " . $comboDishesValue->dish->title);
            }
            echo implode(', ', $combo_dishes_arr);
            array_push($combo, array(
                'combo_title' => $comboValue->combo->title,
                'combo_qty' => $comboValue->combo_qty,
                'combo_price' => $comboValue->price,
                'combo_total' => $comboValue->price * $comboValue->combo_qty
            ));
        }
        $order = array(
            'id' => $order->id,
            'user_id' => $order->user_id,
            'restaurant_id' => $order->restaurant_id,
            'user_full_name' => $order->user_full_name,
            'delivery_address' => $order->address_line_1 . ($order->address_line_2 ? ", " . $order->address_line_2 : '') . ", " . $order->area0->area_name . ", " . $order->city . ", " . $order->pincode,
            'mobile' => $order->mobile,
            'email' => $order->email,
            'restaurant_title' => $restaurant_title,
            'restaurant_address' => $order->restaurant->address . ", " . $order->restaurant->area . ", " . $order->restaurant->city,
            'restaurant_time' => $order->restaurant->open_datetime_1 . " - " . $order->restaurant->close_datetime_1 . " AND " . $order->restaurant->open_datetime_2 . " - " . $order->restaurant->close_datetime_2,
            'order_unique_id' => $order->order_unique_id,
            'delivery_time' => Yii::$app->formatter->asDatetime($order->delivery_time),
            'delivery_items' => $order->delivery_type . " - " . $order->order_items,
            'payment_mode' => $payment_mode,
            'order_datetime' => $order_datetime,
            'payment_info' => $payment_info,
            'payment_datetime' => $payment_datetime,
            'sub_total' => $order->sub_total,
            'discount_text' => ($order->discount_text ? "( " . $order->discount_text . ")" : ""),
            'Discount' => $Discount,
            'coupon_code' => $order->coupon_code,
            'discount_amount' => $order->discount_amount,
            'discount_text' => $order->discount_text,
            'tax_text' => ($order->tax_text ? "( " . $order->tax_text . ")" : ""),
            'tax' => number_format($order->tax, 2) . " (" . $order->tax_text . ")",
            'vat_text' => ($order->vat_text ? "( " . $order->vat_text . ")" : ""),
            'vat' => $order->vat . " (" . $order->vat_text . ")",
            'service_charge_text' => ($order->service_charge_text ? "( " . $order->service_charge_text . ")" : ""),
            'service_charge' => $order->service_charge,
            'grand_total' => $order->grand_total,
            'dishes' => $dishes,
            'combos' => $combo
        );

        if ($order) {
            $this->response['status'] = 1;
            $this->response['message'] = "Order Details";
            $this->response['data'] = $order;
        } else {
            $this->response['status'] = 0;
            $this->response['message'] = "Order not found";
        }
        return $this->response;
    }

    public function actionOutputinfo() {
        $model = $this->findorderModel(Yii::$app->request->post('id'));

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


        $pdf->output($content, "Invoice-" . $model->order_unique_id . ".pdf", Pdf::DEST_DOWNLOAD);
        return;
    }

    protected function findModel($id) {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Order the loaded model
     * @throws HttpException if the model cannot be found
     */
    protected function findorderModel($id) {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }

    private function getRandomOrderID() {
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $res = "";
        for ($i = 0; $i < 3; $i++) {
            $res .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return 'ORD' . $res . time().'_M';
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

    private function sendOrderEmails($id) {
        $order = $this->findModel($id);
        /** EMAIL TO ADMIN * */
        $admin_email_ststus = \Yii::$app->mailer->compose('order/newOrder', ['model' => $order])
                ->setFrom([$order->email => $order->user_full_name])
                ->setTo(\Yii::$app->params['adminEmail'])
                ->setSubject('New Booking at ' . \Yii::$app->name . " - " . $order->order_unique_id)
                ->send();
        /** EMAIL TO RESTAURANT OWNER * */
        $restaurant_email_ststus = \Yii::$app->mailer->compose('order/newOrder', ['model' => $order])
                ->setFrom([$order->email => $order->user_full_name])
                ->setTo($order->restaurant->user->email)
                ->setSubject('New Booking from ' . \Yii::$app->name . " - " . $order->order_unique_id)
                ->send();
        /** EMAIL TO CUSTOMER OWNER * */
        $customer_email_ststus = \Yii::$app->mailer->compose('order/newOrder', ['model' => $order])
                ->setFrom([\Yii::$app->params['adminEmail'] => \Yii::$app->name . ' Admin'])
                ->setTo($order->email)
                ->setSubject('Your order details at ' . \Yii::$app->name)
                ->send();
        return;
    }

}
