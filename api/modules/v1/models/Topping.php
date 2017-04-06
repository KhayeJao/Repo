<?php

namespace api\modules\v1\models;

use Yii;

/**
 * This is the base-model class for table "tbl_topping".
 *
 * @property integer $id
 * @property integer $restaurant_id
 * @property string $title
 *
 * @property \common\models\DishTopping[] $dishToppings
 * @property \common\models\Restaurant $restaurant
 */
class Topping extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_topping';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['restaurant_id', 'title'], 'required'],
            [['restaurant_id'], 'integer'],
            [['title'], 'string', 'max' => 250]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'restaurant_id' => 'Restaurant',
            'title' => 'Title',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDishToppings() {
        return $this->hasMany(\common\models\DishTopping::className(), ['topping_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant() {
        return $this->hasOne(\common\models\Restaurant::className(), ['id' => 'restaurant_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderToppings() {
        return $this->hasMany(\common\models\OrderTopping::className(), ['topping_id' => 'id']);
    }

}
