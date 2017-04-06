<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "tbl_sms_template".
 *
 * @property integer $id
 * @property string $title
 * @property string $status
 *
 * @property \common\models\RestaurantServices[] $restaurantServices
 */
class Groupe extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_sms_template';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['sms'], 'required'], 
            [['mobile_no','required'], 'string', 'max' => 150]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'mobile_no' => 'mobile',
            'sms' => 'Massage',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantServices() {
        return $this->hasMany(\common\models\RestaurantServices::className(), ['sms_id' => 'id']);
    }
      

}
