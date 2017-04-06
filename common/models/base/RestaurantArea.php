<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "tbl_restaurant_area".
 *
 * @property integer $id
 * @property integer $restaurant_id
 * @property integer $area_id
 * @property double $delivery_charge
 *
 * @property \common\models\Area $area
 * @property \common\models\Restaurant $restaurant
 */
class RestaurantArea extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_restaurant_area';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['restaurant_id', 'area_id', 'delivery_charge'], 'required'],
            [['restaurant_id', 'area_id'], 'integer'],
            //[['area_id'], 'unique', 'message' => '{attribute}:{value} already exists!'],
            [['delivery_charge'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'restaurant_id' => 'Restaurant',
            'area_id' => 'Area',
            'delivery_charge' => 'Delivery Charge',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArea() {
        return $this->hasOne(\common\models\Area::className(), ['id' => 'area_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant() {
        return $this->hasOne(\common\models\Restaurant::className(), ['id' => 'restaurant_id']);
    }

}
