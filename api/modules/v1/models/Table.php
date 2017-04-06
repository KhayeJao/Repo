<?php

namespace api\modules\v1\models;

use Yii;

/**
 * This is the base-model class for table "tbl_table".
 *
 * @property integer $id
 * @property integer $restaurant_id
 * @property integer $no_of_seats
 * @property double $price
 * @property string $status
 * @property integer $table_id
 *
 * @property \common\models\Restaurant $restaurant
 * @property \common\models\TableBookingTables[] $tableBookingTables
 */
class Table extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_table';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['restaurant_id', 'no_of_seats', 'price', 'status', 'table_id'], 'required'],
            [['restaurant_id', 'no_of_seats', 'table_id'], 'integer'],
            [['price'], 'number'],
            [['status'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'restaurant_id' => 'Restaurant ID',
            'no_of_seats' => 'No Of Seats',
            'price' => 'Price',
            'status' => 'Status',
            'table_id' => 'Table ID',
        ];
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
    public function getTableBookingTables() {
        return $this->hasMany(\common\models\TableBookingTables::className(), ['table_id' => 'id']);
    }

}
