<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "tbl_order_dish".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $dish_id
 * @property string $dish_title
 * @property double $dish_price
 * @property integer $dish_qty
 * @property string $comment
 *
 * @property \common\models\Order $order
 * @property \common\models\Dish $dish
 */
class OrderDish extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_order_dish';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'dish_id', 'dish_title', 'dish_price', 'dish_qty', 'comment'], 'required'],
            [['order_id', 'dish_id', 'dish_qty'], 'integer'],
            [['dish_price'], 'number'],
            [['dish_title'], 'string', 'max' => 250],
            [['comment'], 'string', 'max' => 500]
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
            'dish_title' => 'Dish Title',
            'dish_price' => 'Dish Price',
            'dish_qty' => 'Dish Qty',
            'comment' => 'Comment',
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
    public function getDish()
    {
        return $this->hasOne(\common\models\Dish::className(), ['id' => 'dish_id']);
    }
}
