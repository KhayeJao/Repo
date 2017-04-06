<?php

namespace api\modules\v1\models;

use Yii;

/**
 * This is the base-model class for table "tbl_dish".
 *
 * @property integer $id
 * @property integer $menu_id
 * @property integer $restaurant_id
 * @property string $title
 * @property string $description
 * @property string $ingredients
 * @property integer $price
 * @property string $is_deleted
 * @property string $status
 *
 * @property \common\models\ComboDish[] $comboDishes
 * @property \common\models\Menu $menu
 * @property \common\models\Restaurant $restaurant
 * @property \common\models\FavDish[] $favDishes
 * @property \common\models\OrderComboDish[] $orderComboDishes
 * @property \common\models\OrderDish[] $orderDishes
 * @property \common\models\OrderTopping[] $orderToppings
 * @property \common\models\ToppingGroup[] $toppingGroups
 */
class Dish extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_dish';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['menu_id', 'restaurant_id', 'title', 'description', 'ingredients', 'price', 'is_deleted', 'status'], 'required'],
            [['menu_id', 'restaurant_id', 'price'], 'integer'],
            [['is_deleted', 'status'], 'string'],
            [['title'], 'string', 'max' => 250],
            [['description', 'ingredients'], 'string', 'max' => 700]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'menu_id' => 'Menu',
            'restaurant_id' => 'Restaurant',
            'title' => 'Title',
            'description' => 'Description',
            'ingredients' => 'Ingredients',
            'price' => 'Price',
            'is_deleted' => 'Is Deleted',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComboDishes() {
        return $this->hasMany(\common\models\ComboDish::className(), ['dish_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenu() {
        return $this->hasOne(\common\models\Menu::className(), ['id' => 'menu_id']);
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
    public function getFavDishes() {
        return $this->hasMany(\common\models\FavDish::className(), ['dish_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderComboDishes() {
        return $this->hasMany(\common\models\OrderComboDish::className(), ['dish_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderDishes() {
        return $this->hasMany(\common\models\OrderDish::className(), ['dish_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderToppings() {
        return $this->hasMany(\common\models\OrderTopping::className(), ['dish_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToppingGroups() {
        return $this->hasMany(\common\models\ToppingGroup::className(), ['dish_id' => 'id']);
    }


}
