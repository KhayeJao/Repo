<?php

namespace api\modules\v1\models;

use Yii;

/**
 * This is the base-model class for table "tbl_combo".
 *
 * @property integer $id
 * @property integer $restaurant_id
 * @property string $title
 * @property double $price
 * @property string $combo_type
 *
 * @property \common\models\Restaurant $restaurant
 * @property \common\models\ComboDish[] $comboDishes
 * @property \common\models\OrderCombo[] $orderCombos
 */
class Combo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_combo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['restaurant_id', 'title', 'price', 'combo_type'], 'required'],
            [['restaurant_id'], 'integer'],
            [['price'], 'number'],
            [['combo_type'], 'string'],
            [['title'], 'string', 'max' => 250]
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
            'title' => 'Title',
            'price' => 'Price',
            'combo_type' => 'Combo Type',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant()
    {
        return $this->hasOne(\common\models\Restaurant::className(), ['id' => 'restaurant_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComboDishes()
    {
        return $this->hasMany(\common\models\ComboDish::className(), ['combo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderCombos()
    {
        return $this->hasMany(\common\models\OrderCombo::className(), ['combo_id' => 'id']);
    }
}
