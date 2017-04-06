<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "tbl_restaurant_phone".
 *
 * @property integer $id
 * @property integer $restaurant_id
 * @property string $label
 * @property string $phone_no
 *
 * @property \common\models\Restaurant $restaurant
 */
class RestaurantPhone extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_restaurant_phone';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['restaurant_id', 'label', 'phone_no'], 'required'],
            [['restaurant_id'], 'integer'],
            [['label'], 'string', 'max' => 20],
            [['phone_no'], 'string', 'max' => 15]
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
            'label' => 'Label',
            'phone_no' => 'Phone No',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant()
    {
        return $this->hasOne(\common\models\Restaurant::className(), ['id' => 'restaurant_id']);
    }
}
