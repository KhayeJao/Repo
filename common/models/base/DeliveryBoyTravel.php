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
class DeliveryBoyTravel extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_delivery_boy_travel';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['user_id'], 'required'],
            [['status'], 'string', 'max' => 255],
            [['travel_distance'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_id' => 'User Name',
            'create_at' => 'Create',
            'travel_distance'=> 'Travel Distance',
        ];
    } 
     

}
