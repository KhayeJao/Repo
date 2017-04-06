<?php

namespace api\modules\v1\models;

use Yii;

/**
 * This is the base-model class for table "tbl_fav_dish".
 *
 * @property integer $id
 * @property integer $dish_id
 * @property integer $user_id
 * @property string $created_on
 *
 * @property \common\models\Dish $dish
 * @property \common\models\User $user
 */
class FavDish extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_fav_dish';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dish_id', 'user_id', 'created_on'], 'required'],
            [['dish_id', 'user_id'], 'integer'],
            [['created_on'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dish_id' => 'Dish ID',
            'user_id' => 'User ID',
            'created_on' => 'Created On',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDish()
    {
        return $this->hasOne(\common\models\Dish::className(), ['id' => 'dish_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'user_id']);
    }
}
