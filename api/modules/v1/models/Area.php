<?php

namespace api\modules\v1\models;

use Yii;

/**
 * This is the base-model class for table "tbl_area".
 *
 * @property integer $id
 * @property string $area_name
 *
 * @property \common\models\Address[] $addresses 
 * @property \common\models\Order[] $orders 
 * @property \common\models\RestaurantArea[] $restaurantAreas
 */
class Area extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_area';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['area_name'], 'required'],
            [['area_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'area_name' => 'Area Name',
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantAreas() {
        return $this->hasMany(\common\models\RestaurantArea::className(), ['area_id' => 'id']);
    }

}
