<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "tbl_guest".
 *
 * @property integer $id
 * @property string $unique_id
 * @property integer $user_id
 * @property string $gcm_id
 * @property string $last_login_on
 * @property string $created_on
 *
 * @property \common\models\User $user
 */
class Guest extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_guest';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['unique_id', 'gcm_id', 'last_login_on', 'created_on'], 'required'],
            [['user_id'], 'integer'],
            [['last_login_on', 'created_on'], 'safe'],
            [['unique_id'], 'string', 'max' => 15],
            [['gcm_id'], 'string', 'max' => 500],
            [['unique_id'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'unique_id' => 'Unique ID',
            'user_id' => 'User ID',
            'gcm_id' => 'Gcm ID',
            'last_login_on' => 'Last Login On',
            'created_on' => 'Created On',
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
