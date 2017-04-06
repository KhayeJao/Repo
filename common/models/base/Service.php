<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "tbl_service".
 *
 * @property integer $id
 * @property string $title
 * @property string $status
 *
 * @property \common\models\RestaurantServices[] $restaurantServices
 */
class Service extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_service';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['title', 'status'], 'required'],
            [['status'], 'string'],
            [['title'], 'string', 'max' => 150]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantServices() {
        return $this->hasMany(\common\models\RestaurantServices::className(), ['service_id' => 'id']);
    }

}
