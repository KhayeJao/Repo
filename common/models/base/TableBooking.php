<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "tbl_table_booking".
 *
 * @property integer $id
 * @property string $order_unique_id 
 * @property integer $user_id
 * @property string $checkin_datetime
 * @property string $booking_date
 * @property string $comment
 * @property double $discount_amount 
 * @property string $discount_text 
 * @property double $sub_total 
 * @property double $grand_total 
 * @property string $payment_info 
 * @property string $status 
 *
 * @property \common\models\User $user
 * @property \common\models\TableBookingTables[] $tableBookingTables
 */
class TableBooking extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_table_booking';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['order_unique_id','user_id', 'checkin_datetime', 'booking_date', 'comment', 'discount_amount', 'discount_text', 'sub_total', 'grand_total','payment_info', 'status'], 'required'],
            [['user_id'], 'integer'],
            [['checkin_datetime', 'booking_date'], 'safe'],
            [['discount_amount', 'sub_total', 'grand_total'], 'number'],
            [['comment'], 'string', 'max' => 700],
            [['discount_text'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_id' => 'User',
            'checkin_datetime' => 'Checkin Datetime',
            'booking_date' => 'Booking Date',
            'comment' => 'Comment',
            'discount_amount' => 'Discount Amount',
            'discount_text' => 'Discount Text',
            'sub_total' => 'Sub Total',
            'grand_total' => 'Grand Total',
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
    public function getTableBookingTables() {
        return $this->hasMany(\common\models\TableBookingTables::className(), ['table_booking_id' => 'id']);
    }

}
