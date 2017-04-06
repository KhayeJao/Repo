<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "tbl_table_booking_tables".
 *
 * @property integer $id
 * @property integer $table_booking_id
 * @property integer $table_id
 * @property double $table_price
 * 
 *
 * @property \common\models\Table $table
 * @property \common\models\TableBooking $tableBooking
 */
class TableBookingTables extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_table_booking_tables';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['table_booking_id', 'table_id', 'table_price'], 'required'],
            [['table_booking_id', 'table_id'], 'integer'],
            [['table_price'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'table_booking_id' => 'Table Booking ID',
            'table_id' => 'Table ID',
            'table_price' => 'Table Price',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTable()
    {
        return $this->hasOne(\common\models\Table::className(), ['id' => 'table_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTableBooking()
    {
        return $this->hasOne(\common\models\TableBooking::className(), ['id' => 'table_booking_id']);
    }
}
