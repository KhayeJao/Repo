<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "tbl_restaurant_coupons".
 *
 * @property integer $id
 * @property integer $restaurant_id
 * @property integer $coupon_id
 *
 * @property \common\models\Restaurant $restaurant
 * @property \common\models\Coupons $coupon
 */
class RestaurantCoupons extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_restaurant_coupons';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['restaurant_id', 'coupon_id'], 'required'],
            [['restaurant_id', 'coupon_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'restaurant_id' => 'Restaurant',
            'coupon_id' => 'Coupon',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant()
    {
        return $this->hasOne(\common\models\Restaurant::className(), ['id' => 'restaurant_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoupon()
    {
        return $this->hasOne(\common\models\Coupons::className(), ['id' => 'coupon_id']);
    }
}
