<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "tbl_restaurant_services".
 *
 * @property integer $id
 * @property integer $restaurant_id
 * @property integer $service_id
 *
 * @property \common\models\Restaurant $restaurant
 * @property \common\models\Service $service
 */
class RestaurantServices extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_restaurant_services';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['restaurant_id', 'service_id'], 'required'],
            [['restaurant_id', 'service_id'], 'integer']
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
            'service_id' => 'Service',
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
    public function getService()
    {
        return $this->hasOne(\common\models\Service::className(), ['id' => 'service_id']);
    }
}
