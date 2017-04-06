<?php

namespace api\modules\v1\models;

use Yii;

/**
 * This is the base-model class for table "tbl_topping_group".
 *
 * @property integer $id
 * @property string $title
 * @property integer $dish_id
 *
 * @property \common\models\DishTopping[] $dishToppings
 * @property \common\models\Dish $dish
 */
class ToppingGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_topping_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'dish_id'], 'required'],
            [['dish_id'], 'integer'],
            [['title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'dish_id' => 'Dish ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDishToppings()
    {
        return $this->hasMany(\common\models\DishTopping::className(), ['topping_group_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDish()
    {
        return $this->hasOne(\common\models\Dish::className(), ['id' => 'dish_id']);
    }
}
