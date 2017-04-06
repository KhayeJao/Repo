<?php

namespace api\modules\v1\models;

use Yii;

/**
 * This is the base-model class for table "tbl_dish_topping".
 *
 * @property integer $id
 * @property integer $topping_group_id
 * @property integer $topping_id
 * @property double $price
 *
 * @property \common\models\ToppingGroup $toppingGroup
 * @property \common\models\Topping $topping
 */
class DishTopping extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_dish_topping';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['topping_group_id', 'topping_id', 'price'], 'required'],
            [['topping_group_id', 'topping_id'], 'integer'],
            [['price'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'topping_group_id' => 'Topping Group',
            'topping_id' => 'Topping',
            'price' => 'Price',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToppingGroup()
    {
        return $this->hasOne(\common\models\ToppingGroup::className(), ['id' => 'topping_group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTopping()
    {
        return $this->hasOne(\common\models\Topping::className(), ['id' => 'topping_id']);
    }
}
