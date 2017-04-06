<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "tbl_order_payments".
 *
 * @property integer $id
 * @property integer $order_id
 * @property string $payment_info
 * @property string $payment_datetime
 *
 * @property \common\models\Order $order
 */
class OrderPayments extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_order_payments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'payment_info', 'payment_datetime'], 'required'],
            [['order_id'], 'integer'],
            [['payment_info'], 'string'],
            [['payment_datetime'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'payment_info' => 'Payment Info',
            'payment_datetime' => 'Payment Datetime',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(\common\models\Order::className(), ['id' => 'order_id']);
    }
}
