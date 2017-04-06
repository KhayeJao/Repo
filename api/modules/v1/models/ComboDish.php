<?php

namespace api\modules\v1\models;

use Yii;

/**
 * This is the base-model class for table "tbl_combo_dish".
 *
 * @property integer $id
 * @property integer $combo_id
 * @property integer $dish_id
 * @property integer $dish_qty
 *
 * @property \common\models\Combo $combo
 * @property \common\models\Dish $dish
 */
class ComboDish extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_combo_dish';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['combo_id', 'dish_id', 'dish_qty'], 'required'],
            [['combo_id', 'dish_id', 'dish_qty'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'combo_id' => 'Combo ID',
            'dish_id' => 'Dish ID',
            'dish_qty' => 'Dish Qty',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCombo()
    {
        return $this->hasOne(\common\models\Combo::className(), ['id' => 'combo_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDish()
    {
        return $this->hasOne(\common\models\Dish::className(), ['id' => 'dish_id']);
    }
}
