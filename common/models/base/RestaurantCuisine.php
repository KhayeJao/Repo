<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "tbl_restaurant_cuisine".
 *
 * @property integer $id
 * @property integer $restaurant_id
 * @property integer $cuisine_id
 *
 * @property \common\models\Restaurant $restaurant
 * @property \common\models\Cuisine $cuisine
 */
class RestaurantCuisine extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_restaurant_cuisine';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['restaurant_id', 'cuisine_id'], 'required'],
            [['restaurant_id', 'cuisine_id'], 'integer']
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
            'cuisine_id' => 'Cuisine',
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
    public function getCuisine()
    {
        return $this->hasOne(\common\models\Cuisine::className(), ['id' => 'cuisine_id']);
    }
}
