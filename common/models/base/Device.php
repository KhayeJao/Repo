<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "tbl_device".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $device_id
 * @property string $device_platform
 *
 * @property \common\models\User $user
 */
class Device extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_device';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['device_id', 'device_platform'], 'required'],
            [['device_id', 'device_platform'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'device_id' => 'Device ID',
            'device_platform' => 'Device Platform',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'user_id']);
    }
}
