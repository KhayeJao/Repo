<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "tbl_delivery_boy_order".
 *
 * @property integer $id
 * @property string $area_name
 *
 * @property \common\models\Address[] $addresses 
 * @property \common\models\Order[] $orders 
 * @property \common\models\RestaurantArea[] $restaurantAreas
 */
class DeliveryBoyOrder extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_delivery_boy_order';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['user_id','status'], 'required'],
            [['order_status','status'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_id' => 'User Name',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddresses() {
        return $this->hasMany(\common\models\Address::className(), ['area' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery 
     */
    public function getOrders() {
        return $this->hasMany(\common\models\Order::className(), ['area' => 'id']);
    }

public function getArea() {
        return $this->hasMany(\common\models\Order::className(), ['order_unique_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantAreas() {
        return $this->hasMany(\common\models\RestaurantArea::className(), ['area_id' => 'id']);
    }

}
