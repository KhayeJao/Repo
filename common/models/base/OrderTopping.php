<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "tbl_order_topping".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $dish_id
 * @property integer $topping_id
 * @property double $price
 *
 * @property \common\models\Topping $topping
 * @property \common\models\Order $order
 * @property \common\models\Dish $dish
 */
class OrderTopping extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_order_topping';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'dish_id', 'topping_id', 'price'], 'required'],
            [['order_id', 'dish_id', 'topping_id'], 'integer'],
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
            'dish_id' => 'Dish ID',
            'topping_id' => 'Topping ID',
            'price' => 'Price',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTopping()
    {
        return $this->hasOne(\common\models\Topping::className(), ['id' => 'topping_id']);
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
    public function getDish()
    {
        return $this->hasOne(\common\models\Dish::className(), ['id' => 'dish_id']);
    }
}
