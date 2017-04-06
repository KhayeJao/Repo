<?php

namespace api\modules\v1\models;

use Yii;

/**
 * This is the base-model class for table "tbl_restaurant_review".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $restaurant_id
 * @property string $title
 * @property string $comment
 * @property integer $rate
 * @property string $created_on
 * @property string $status
 *
 * @property \common\models\User $user
 * @property \common\models\Restaurant $restaurant
 */
class RestaurantReview extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_restaurant_review';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['user_id', 'restaurant_id', 'title', 'comment', 'rate', 'created_on', 'status'], 'required'],
            [['user_id', 'restaurant_id', 'rate'], 'integer'],
            [['created_on'], 'safe'],
            [['status'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['comment'], 'string', 'max' => 750],
            [['user_id', 'restaurant_id'], 'unique', 'targetAttribute' => ['user_id', 'restaurant_id'], 'message' => 'The combination of User ID and Restaurant ID has already been taken.']
        ];
    }

    

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_id' => 'User',
            'restaurant_id' => 'Restaurant',
            'title' => 'Title',
            'comment' => 'Comment',
            'rate' => 'Rate',
            'created_on' => 'Created On',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(\common\models\User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant() {
        return $this->hasOne(\common\models\Restaurant::className(), ['id' => 'restaurant_id']);
    }

}
