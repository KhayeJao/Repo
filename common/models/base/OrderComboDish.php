<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "tbl_order_combo_dish".
 *
 * @property integer $id
 * @property integer $order_combo_id
 * @property integer $dish_id
 * @property integer $dish_qry
 *
 * @property \common\models\Dish $dish
 * @property \common\models\OrderCombo $orderCombo
 */
class OrderComboDish extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_order_combo_dish';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_combo_id', 'dish_id', 'dish_qry'], 'required'],
            [['order_combo_id', 'dish_id', 'dish_qry'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_combo_id' => 'Order Combo',
            'dish_id' => 'Dish',
            'dish_qry' => 'Qry',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDish()
    {
        return $this->hasOne(\common\models\Dish::className(), ['id' => 'dish_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderCombo()
    {
        return $this->hasOne(\common\models\OrderCombo::className(), ['id' => 'order_combo_id']);
    }
}
