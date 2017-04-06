<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "tbl_notification".
 *
 * @property integer $id
 * @property string $user_id
 * @property string $resturant_id
 * @property string $order_id
 *
 * @property \common\models\Address[] $addresses 
 * @property \common\models\Order[] $orders 
 * @property \common\models\RestaurantArea[] $restaurantAreas
 */
class Notifications extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_notification';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['user_id'], 'required'],
            [['resturant_id','order_id','is_view','is_delete','created_at'], 'integer'],
			[['masage'], 'string', 'max' => 550], 
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'is_view' => 'View',
            'masage' => 'Masage Suggested',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotification() {
        return $this->hasMany(\common\models\Notifications::className(), ['masage' => 'resturant_id']);
    }

    /**
     * @return \yii\db\ActiveQuery 
     */
    public function getUserNotification() {
        return $this->hasMany(\common\models\User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant() {
        return $this->hasMany(\common\models\Restaurant::className(), ['resturant_id' => 'resturant_id']);
    }

}
