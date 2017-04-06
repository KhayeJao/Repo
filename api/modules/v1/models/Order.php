<?php

namespace api\modules\v1\models;

use Yii;

/**
 * This is the base-model class for table "tbl_order".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $restaurant_id
 * @property string $order_unique_id
 * @property string $affiliate_order_id
 * @property string $user_full_name
 * @property string $mobile
 * @property string $email
 * @property string $address_line_1
 * @property string $address_line_2
 * @property integer $area
 * @property string $city
 * @property string $pincode
 * @property string $delivery_time
 * @property string $delivery_type
 * @property string $coupon_code
 * @property double $discount_amount
 * @property string $discount_text
 * @property integer $order_items
 * @property double $tax
 * @property string $tax_text
 * @property double $vat
 * @property string $vat_text
 * @property double $service_charge
 * @property string $service_charge_text
 * @property double $sub_total 
 * @property double $grand_total 
 * @property string $comment
 * @property string $booking_time
 * @property string $order_status_change_datetime
 * @property string $accept_reject_datetime 
 * @property string $complete_datetime 
 * @property string $order_status_change_reason
 * @property string $placed_via
 * @property string $order_ip
 * @property string $status
 *
 * @property \common\models\User $user
 * @property \common\models\Restaurant $restaurant
 * @property \common\models\OrderCombo[] $orderCombos
 * @property \common\models\OrderDish[] $orderDishes
 */
class Order extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_order';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['restaurant_id', 'delivery_type', 'order_unique_id', 'user_full_name', 'mobile', 'email', 'address_line_1', 'address_line_2', 'area', 'city', 'pincode', 'delivery_time', 'order_items', 'tax', 'tax_text', 'vat', 'vat_text', 'service_charge', 'service_charge_text', 'booking_time','placed_via', 'status'], 'required'],
            [['user_id', 'restaurant_id', 'order_items', 'area'], 'integer'],
            [['delivery_time', 'booking_time', 'order_status_change_datetime', 'accept_reject_datetime', 'complete_datetime'], 'safe'],
            [['discount_amount', 'tax', 'vat', 'service_charge', 'sub_total', 'grand_total'], 'number'],
            [['status','placed_via'], 'string'],
            [['order_unique_id', 'affiliate_order_id', 'order_ip'], 'string', 'max' => 30],
            [['user_full_name'], 'string', 'max' => 400],
            [['mobile'], 'string', 'max' => 15],
            [['email', 'discount_text', 'tax_text', 'vat_text', 'service_charge_text'], 'string', 'max' => 255],
            [['address_line_1', 'address_line_2', 'coupon_code'], 'string', 'max' => 100],
            [['city', 'pincode'], 'string', 'max' => 50],
            [['comment'], 'string', 'max' => 700],
            [['order_status_change_reason'], 'string', 'max' => 300],
            [['order_unique_id'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'restaurant_id' => 'Restaurant',
            'order_unique_id' => 'Order ID',
            'affiliate_order_id' => 'Affiliate Order ID',
            'user_full_name' => 'Customer Full Name',
            'mobile' => 'Mobile',
            'email' => 'Email',
            'address_line_1' => 'Address Line 1',
            'address_line_2' => 'Address Line 2',
            'area' => 'Area',
            'city' => 'City',
            'pincode' => 'Pincode',
            'delivery_time' => 'Delivery Time',
            'delivery_type' => 'Delivery Type',
            'coupon_code' => 'Coupon Code',
            'discount_amount' => 'Discount Amount',
            'discount_text' => 'Discount Text',
            'order_items' => 'Order Items',
            'tax' => 'Tax',
            'tax_text' => 'Tax Text',
            'vat' => 'Vat',
            'vat_text' => 'Vat Text',
            'service_charge' => 'Service Charge',
            'service_charge_text' => 'Service Charge Text',
            'comment' => 'Comment',
            'booking_time' => 'Booking Time',
            'order_status_change_datetime' => 'Order Status Change Datetime',
            'accept_reject_datetime' => 'Accept Reject Datetime',
            'complete_datetime' => 'Complete Datetime',
            'order_status_change_reason' => 'Order Status Change Reason',
            
            'order_ip' => 'Order Ip',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(\common\models\User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant() {
        return $this->hasOne(\common\models\Restaurant::className(), ['id' => 'restaurant_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderCombos() {
        return $this->hasMany(\common\models\OrderCombo::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderDishes() {
        return $this->hasMany(\common\models\OrderDish::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery 
     */
    public function getOrderToppings() {
        return $this->hasMany(\common\models\OrderTopping::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArea0() {
        return $this->hasOne(\common\models\Area::className(), ['id' => 'area']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderPayments() {
        return $this->hasMany(\common\models\OrderPayments::className(), ['order_id' => 'id']);
    }

}
