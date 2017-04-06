<?php

namespace common\models\base;

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
class DishMaster extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_master_dish';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['title', 'is_deleted', 'status'], 'required'],
            [['id'], 'integer'],
            [['is_deleted', 'status'], 'string'],
            [['title'], 'string', 'max' => 250], 
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID', 
            'title' => 'Title', 
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
        return $this->hasOne(\common\models\MenuMaster::className(), ['id' => 'menu_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    

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
