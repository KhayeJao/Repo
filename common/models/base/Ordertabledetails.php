<?php

namespace common\models\base; 

use Yii;
use yii\helpers\Url;
use yii\helpers\CommonHelper;

/**
 * This is the base-model class for table "tbl_table_order_details'".
 *
 * @property integer $id
 * @property integer $table_no
  
 */
 
class Ordertabledetails extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_table_order_details';
    }

     
 /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'table_no'], 'required'],
            [['table_no'], 'integer']
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'table_no' => 'Table No',
            
        ];
    }

     

}
