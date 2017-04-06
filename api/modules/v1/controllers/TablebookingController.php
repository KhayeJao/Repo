<?php

namespace api\modules\v1\controllers;

use yii\rest\Controller;
use yii\web\HttpException;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Response;
use Yii;
use api\modules\v1\models\TableBooking;
use kartik\mpdf\Pdf;
use api\modules\v1\models\TableBookingTables;
use api\modules\v1\models\Restaurant;
use yii\helpers\ArrayHelper;
use common\components\Sms;
use api\modules\v1\models\Table;

/**
 * RestaurantController implements the CRUD actions for Restaurant model.
 */
class TablebookingController extends Controller {

    public $modelClass = 'api\modules\v1\models\TableBooking';
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

    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;
        $behaviors['access'] = array(
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['tblbooking', 'tblbookingview', 'tblbookingpdf', 'booktable', 'paymentsuccess', 'paymentcancle', 'getrestaurenttable'],
                ]
            ]
        );
        return $behaviors;
    }

    public function actionBooktable() {
        $cart_data = Json::decode(Yii::$app->request->post('table_cart'));
        if (!$cart_data) {
            $this->response['status'] = 0;
            $this->response['message'] = "Invalid request";
            return $this->response;
        }
        $restaurant_model = $this->findRestaurantModel($cart_data['restaurant_id']);
        if (!$restaurant_model) {
            $this->response['status'] = 0;
            $this->response['message'] = "Invalid restaurant";
            return $this->response;
        }
        $bookingdatetime = $cart_data['booking_datetime'];
        $date = new \DateTime($bookingdatetime);
        $date->add(new \DateInterval('PT' . $restaurant_model->table_slot_time . 'M'));
        $query = TableBookingTables::find()->select('table_id')->where('tbl_table_booking.checkin_datetime BETWEEN "' . $bookingdatetime . '" AND "' . $date->format('Y-m-d H:i:s') . '" AND status <> "Canceled"')->joinWith('tableBooking')->all();
        $set_arr = explode(',', $cart_data['set']);
        $engaged_table = ArrayHelper::getColumn($query, 'table_id');
        $result = array_intersect($engaged_table, $set_arr);
        if (count($result) > 0) {
            $new_set = array_diff($set_arr, $engaged_table);
            $cart_data['set'] = implode(',', $new_set);
            $this->response['status'] = 0;
            $this->response['message'] = 'It seems like all tables that you have selected are not available. Please try again.';
            $this->response['table_cart'] = $cart_data;
            return $this->response;
        }

        /*
         * INSERT BOOKING TABLE WITH BLOCKED STATUS
         */

        $table_booking = new TableBooking();
        $table_booking->order_unique_id = $this->getRandomOrderID();
        $table_booking->user_id = $cart_data['user_id'];
        $table_booking->checkin_datetime = $cart_data['booking_datetime'];
        $table_booking->booking_date = date('Y-m-d H:i:s', time());
        $table_booking->comment = "";
        $table_booking->discount_amount = "0";
        $table_booking->discount_text = "";
        if (isset($cart_data['coupon_data']['discount_amount'])) {
            $table_booking->discount_amount = $cart_data['coupon_data']['discount_amount'];
            $table_booking->discount_text = "Coupon Code used".$cart_data['coupon_data']['coupon_code'];
        }
        $table_booking->sub_total = $cart_data['subtotal'];
        $table_booking->grand_total = $cart_data['grand_total'];
        $table_booking->payment_info = "";
        $table_booking->status = "Blocked";
        $table_booking->save(FALSE);
        $table_booking_id = $table_booking->id;


        foreach ($set_arr as $set_arr_key => $set_arr_value) {
            $table_booking_tables = new TableBookingTables();
            $table_model = \common\models\Table::findOne(['id' => $set_arr_value]);
            $table_booking_tables->table_booking_id = $table_booking_id;
            $table_booking_tables->table_id = $table_model->id;
            $table_booking_tables->table_price = $table_model->price;
            $table_booking_tables->save(FALSE);
        }
        unset($cart_data);
        $cart_data = array();
        $cart_data['order_id'] = $table_booking_id;
        $cart_data['order_unique_id'] = $table_booking->order_unique_id;
        $this->response['status'] = 1;
        $this->response['message'] = 'Table(s) has been blocked for you until you complete your payment';
        $this->response['booking_info'] = $cart_data;

        /*
         * TODO : GO TO PAYMENT SITE
         */

        /*
         * TODO : REMOVE BELOW FUNCTION CALL AFTER TESTING
         */
//        $this->actionPaymentsuccess();
        return $this->response;
    }

    public function actionPaymentsuccess() {
        $cart_data = Json::decode(Yii::$app->request->post('table_cart'));
        $table_booking = TableBooking::findOne(['id' => $cart_data['order_id']]);
        if ($table_booking) {
            /*
             * TODO : CHECK FOR POST DATA FORM PAYMENT SITE
             * TODO : PUT PAYMENT DATA HERE INDATABASE
             */
            $table_booking->payment_info = "";
            $table_booking->status = 'Confirmed';
            $table_booking->update(FALSE);
            $sms = new Sms();
            /*             * * SMS FOR CUSTOMER ** */
            $messageCust = "Your tables at " . $table_booking->tableBookingTables[0]->table->restaurant->title . " restaurant has been booked successfully. Order ID is : " . $table_booking->order_unique_id . " You should reach there before 10 minuts of " . \Yii::$app->formatter->asTime($table_booking->checkin_datetime, 'HH:mm') . " on " . \Yii::$app->formatter->asDate($table_booking->checkin_datetime);
            $sms->send($table_booking->user->mobile_no, $messageCust);

            /*             * * SMS FOR RESTAURANT ADMIN ** */
            $tables_arr = array();
            foreach ($table_booking->tableBookingTables as $booked_tables_key => $booked_tables_value) {
                array_push($tables_arr, "Table # : " . $booked_tables_value->table->table_id . " (" . $booked_tables_value->table->no_of_seats . " seats)");
            }

            $messageRestaurant = "Tables at your restaurant( " . $table_booking->tableBookingTables[0]->table->restaurant->title . " ) has been booked. Tables Booked : " . implode(', ', $tables_arr) . ". Order ID is : " . $table_booking->order_unique_id . ". Booking at " . \Yii::$app->formatter->asTime($table_booking->checkin_datetime, 'HH:mm') . " on " . \Yii::$app->formatter->asDate($table_booking->checkin_datetime);
            foreach ($table_booking->tableBookingTables[0]->table->restaurant->restaurantPhones as $phone_key => $phone_value) {
                $sms->send($phone_value->phone_no, $messageRestaurant);
            }

            /** EMAIL TO RESTAURANT OWNER * */
            $restaurant_email_ststus = \Yii::$app->mailer->compose('table_order/newOrder', ['table_booking' => $table_booking])
                    ->setFrom([$table_booking->user->email => $table_booking->user->first_name . " " . $table_booking->user->last_name])
                    ->setTo($table_booking->tableBookingTables[0]->table->restaurant->user->email)
                    ->setSubject('New Table Booking from ' . \Yii::$app->name . " - " . $table_booking->order_unique_id)
                    ->send();
            /** EMAIL TO CUSTOMER * */
            $customer_email_ststus = \Yii::$app->mailer->compose('table_order/newOrder', ['table_booking' => $table_booking])
                    ->setFrom([\Yii::$app->params['adminEmail'] => \Yii::$app->name . ' Admin'])
                    ->setTo($table_booking->user->email)
                    ->setSubject('Your Table booking details at ' . \Yii::$app->name)
                    ->send();
            unset($cart_data);
            $this->response['status'] = 1;
            $this->response['message'] = 'Your table has been booked. Your order id is #' . $table_booking->order_unique_id;
            $this->response['booking_info'] = array(
                'order_id' => $table_booking->id,
                'order_unique_id' => $table_booking->order_unique_id,
            );
            \Yii::$app->getSession()->setFlash('success', 'Your table has been booked. Your order id is #' . $table_booking->order_unique_id);
        } else {
            $this->response['status'] = 0;
            $this->response['message'] = 'Invalid booking information';
        }
        return $this->response;
    }

    public function actionPaymentcancle() {
        $cart_data = Json::decode(Yii::$app->request->post('table_cart'));
        $table_booking = TableBooking::findOne(['id' => $cart_data['order_id']]);
        if ($table_booking) {
            $table_booking->payment_info = "";
            $table_booking->status = 'Canceled';
            $table_booking->update(FALSE);
            $this->response['status'] = 1;
            $this->response['message'] = 'Your table booking with #' . $table_booking->order_unique_id . ' has been canceled.';
            $this->response['booking_info'] = array(
                'order_id' => $table_booking->id,
                'order_unique_id' => $table_booking->order_unique_id,
            );
        } else {
            $this->response['status'] = 0;
            $this->response['message'] = 'Invalid booking information';
        }
        return $this->response;
    }

    public function actionTblbooking() {
        $id = Yii::$app->request->post('id');
        $bookings = TableBooking::findAll(['user_id' => $id]);
        $_arr = array();
        foreach ($bookings as $key => $booking) {
            $model = TableBooking::findOne($booking->id);
            $restaurant_title = $model->tableBookingTables[0]->table->restaurant->title;
            array_push($_arr, array(
                'id' => $booking->id,
                'order_unique_id' => $booking->order_unique_id,
                'restaurant_title' => $restaurant_title,
                'price' => $booking->grand_total,
                'checkin_datetime' => Yii::$app->formatter->asDatetime($booking->checkin_datetime, "php:M d, Y H:i:s A"),
                'booking_date' => Yii::$app->formatter->asDatetime($booking->booking_date, "php:M d, Y H:i:s A"),
                'status' => $booking->status,
            ));
        }
        if ($bookings) {
            $this->response['status'] = 1;
            $this->response['message'] = "List Of Table Booking";
            $this->response['data'] = $_arr;
        } else {
            $this->response['status'] = 0;
            $this->response['message'] = "No Table Booking found";
        }
        return $this->response;
    }

    public function actionTblbookingview() {
        $id = Yii::$app->request->post('id');
        $booking = Tablebooking::findOne($id);
        $restaurant_title = $booking->tableBookingTables[0]->table->restaurant->title;
        $address = $booking->tableBookingTables[0]->table->restaurant->address . ", " . $booking->tableBookingTables[0]->table->restaurant->area . ", " . $booking->tableBookingTables[0]->table->restaurant->city;
        $open = $booking->tableBookingTables[0]->table->restaurant->open_datetime_1 . " - " . $booking->tableBookingTables[0]->table->restaurant->close_datetime_1 . " AND " . $booking->tableBookingTables[0]->table->restaurant->open_datetime_2 . " - " . $booking->tableBookingTables[0]->table->restaurant->close_datetime_2;
        $name = $booking->user->first_name . " " . $booking->user->last_name;
        $order_unique_id = $booking->order_unique_id;
        $datetime = Yii::$app->formatter->asDatetime($booking->checkin_datetime);
        $booking_tbl = array();
        foreach ($booking->tableBookingTables as $booked_tables_key => $booked_tables_value) {
            $key = $booked_tables_key + 1;
            $table_id = $booked_tables_value->table->table_id;
            $no_of_seats = $booked_tables_value->table->no_of_seats;
            $table_price = $booked_tables_value->table_price;
            array_push($booking_tbl, array(
                'key' => $key,
                'table_id' => $table_id,
                'no_of_seats' => $no_of_seats,
                'table_price' => $table_price,
            ));
        }
        if (trim($booking->payment_info)) {
            $payment_info = $booking->payment_info;
        } else {
            $payment_info = "";
        }
        $order_datetime = Yii::$app->formatter->asDatetime($booking->booking_date);
        $subtotal = $booking->sub_total;
        if ($booking->discount_amount > 0) {
            $discount = (trim($booking->discount_text) ? "(" . $booking->discount_text . ")" : '');
            $discount_amount = $booking->discount_amount;
        } else {
            $discount = "";
            $discount_amount = "";
        }
        $grand_total = $booking->grand_total;
        $booking_json = array(
            'id' => $booking->id,
            'restaurant_title' => $restaurant_title,
            'address' => $address,
            'restaurant_time' => $open,
            'customer_name' => $name,
            'order_unique_id' => $order_unique_id,
            'check_in_datetime' => $datetime,
            'booking_table_list' => $booking_tbl,
            'payment_info' => $payment_info,
            'order_datetime' => $order_datetime,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'discount_amount' => $discount_amount,
            'grand_total' => $grand_total
        );
        if ($booking) {
            $this->response['status'] = 1;
            $this->response['message'] = "Table Booking Details";
            $this->response['data'] = $booking_json;
        } else {
            $this->response['status'] = 0;
            $this->response['message'] = "Table Booking not found";
        }
        return $this->response;
    }

    public function actionGetrestaurenttable() {
        $restaurant = Yii::$app->request->post('restaurant');
        $bookingdatetime = Yii::$app->request->post('bookingdatetime');
        $restaurant_model = $this->findRestaurantModel($restaurant);
        $is_restaurant_open = $restaurant_model->isOpenForTableBooking(strtotime(date('H:i:s', strtotime($bookingdatetime))));

        $date = new \DateTime($bookingdatetime);
        $date->add(new \DateInterval('PT' . $restaurant_model->table_slot_time . 'M'));

        $query = \common\models\base\TableBookingTables::find()->select('table_id')->where('tbl_table_booking.checkin_datetime BETWEEN "' . $bookingdatetime . '" AND "' . $date->format('Y-m-d H:i:s') . '" AND status <> "Canceled"')->joinWith('tableBooking')->all();
        $engaged_table = ArrayHelper::getColumn($query, 'table_id');

//        $_SESSION['table_cart']['tables'] = array();
        $data['is_restaurant_open'] = $is_restaurant_open;
        $data['bookingdatetime'] = $bookingdatetime;
        $data['restaurant_model'] = $this->findRestaurantModel($restaurant);
        $data['engaged_table'] = $engaged_table;
        $data['two_tables'] = Table::find()->orderBy('status')->where(['restaurant_id' => $restaurant, 'no_of_seats' => 2])->all();
        $data['four_tables'] = Table::find()->orderBy('status')->where(['restaurant_id' => $restaurant, 'no_of_seats' => 4])->all();
        $data['six_tables'] = Table::find()->orderBy('status')->where(['restaurant_id' => $restaurant, 'no_of_seats' => 6])->all();
        $data['eight_tables'] = Table::find()->orderBy('status')->where(['restaurant_id' => $restaurant, 'no_of_seats' => 8])->all();
        $this->response['status'] = 1;
        $this->response['message'] = 'List of restaurent table';
        $this->response['data'] = $data;
        return $this->response;
    }

    public function actionTblbookingpdf() {
        $model = $this->findBookingModel(Yii::$app->request->post('id'));

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

        $pdf->output($content, "Invoice-" . $model->order_unique_id . ".pdf", Pdf::DEST_DOWNLOAD);
        return;
    }

    private function getRandomOrderID() {
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $res = "";
        for ($i = 0; $i < 3; $i++) {
            $res .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return 'TBLORD' . $res . time();
    }

    protected function findBookingModel($id) {
        if (($model = TableBooking::findOne(['order_unique_id' => $id])) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }

    protected function findRestaurantModel($id) {
        $model = Restaurant::findOne(['id' => $id, 'status' => 'Active']);
        return $model;
    }

}
