<?php

namespace api\modules\v1\models;

/**
 * This is the base-model class for table "tbl_address".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $address_line_1
 * @property string $address_line_2
 * @property integer $area
 * @property integer $city
 * @property integer $pincode
 * @property integer $country
 * @property string $created_on
 *
 * @property \common\models\User $user
 */
class Address extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_address';
    }
    
    /**
     * @inheritdoc
     */
    public static function primaryKey()
    {
        return ['id'];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['user_id', 'address_line_1', 'address_line_2', 'area', 'city', 'pincode', 'country', 'created_on'], 'required'],
            [['user_id', 'pincode', 'area'], 'integer'],
            [['created_on'], 'safe'],
            [['address_line_1', 'address_line_2','city','country'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_id' => 'User',
            'address_line_1' => 'Address Line 1',
            'address_line_2' => 'Address Line 2',
            'area' => 'Area',
            'city' => 'City',
            'pincode' => 'Pincode',
            'country' => 'Country',
            'created_on' => 'Created On',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArea0() {
        return $this->hasOne(\common\models\Area::className(), ['id' => 'area']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(\common\models\User::className(), ['id' => 'user_id']);
    }
        
//    public function beforeValidate() {
////        $this->created_on = date('Y-m-d H:i:s');
////        parent::beforeValidate();
//    }

}
