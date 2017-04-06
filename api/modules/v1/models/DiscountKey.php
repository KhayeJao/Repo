<?php

namespace api\modules\v1\models;

use Yii;

/**
 * This is the base-model class for table "tbl_discount_key".
 *
 * @property integer $id
 * @property string $key_type
 * @property string $type
 * @property string $description
 *
 * @property \common\models\Coupons[] $coupons
 */
class DiscountKey extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_discount_key';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key_type', 'type', 'description'], 'required'],
            [['type'], 'string'],
            [['key_type'], 'string', 'max' => 250],
            [['description'], 'string', 'max' => 700],
            [['key_type'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'key_type' => 'Key',
            'type' => 'Type',
            'description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoupons()
    {
        return $this->hasMany(\common\models\Coupons::className(), ['coupon_key' => 'key_type']);
    }
}
