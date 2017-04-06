<?php

namespace common\models\base; 

use Yii;
use yii\helpers\Url;
use yii\helpers\CommonHelper;

/**
 * This is the base-model class for table "tbl_ordertable".
 *
 * @property integer $id
 * @property integer $table_no
  
 */
 
class Ordertablestatus extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_ordertablestatus';
    }

     
 /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['restaurant_id','table_no','order_details','status'], 'required'],
            [['id','table_no','restaurant_id'], 'integer']
            
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
