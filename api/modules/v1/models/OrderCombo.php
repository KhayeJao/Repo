<?php

namespace api\modules\v1\models;

use Yii;

/**
 * This is the base-model class for table "tbl_order_combo".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $combo_id
 * @property integer $combo_qty
 * @property double $price
 *
 * @property \common\models\Order $order
 * @property \common\models\Combo $combo
 * @property \common\models\OrderComboDish[] $orderComboDishes
 */
class OrderCombo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_order_combo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'combo_id', 'combo_qty', 'price'], 'required'],
            [['order_id', 'combo_id', 'combo_qty'], 'integer'],
            [['price'], 'number']
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
            'combo_id' => 'Combo ID',
            'combo_qty' => 'Combo Qty',
            'price' => 'Price',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(\common\models\Order::className(), ['id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCombo()
    {
        return $this->hasOne(\common\models\Combo::className(), ['id' => 'combo_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderComboDishes()
    {
        return $this->hasMany(\common\models\OrderComboDish::className(), ['order_combo_id' => 'id']);
    }
}
