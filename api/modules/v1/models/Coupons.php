<?php

namespace api\modules\v1\models;

use Yii;
use yii\helpers\Json;

/**
 * This is the base-model class for table "tbl_coupons".
 *
 * @property integer $id
 * @property string $code
 * @property string $coupon_key
 * @property string $title
 * @property string $description
 * @property string $type
 * @property string $coupon_perameter
 * @property string $notify
 * @property string $status
 * @property string $created_on
 * @property string $updated_on
 *
 * @property \common\models\DiscountKey $couponKey
 * @property \common\models\RestaurantCoupons[] $restaurantCoupons
 */
class Coupons extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_coupons';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['code', 'coupon_key', 'title', 'description', 'type', 'coupon_perameter', 'notify', 'status', 'created_on'], 'required'],
            [['type', 'notify', 'status'], 'string'],
            [['created_on'], 'safe'],
            [['code'], 'string', 'max' => 30],
            [['coupon_key'], 'string', 'max' => 100],
            [['title'], 'string', 'max' => 200],
            [['description'], 'string', 'max' => 500],
            [['updated_on'], 'safe'],
            [['coupon_perameter'], 'string', 'max' => 700]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'coupon_key' => 'Coupon Key',
            'title' => 'Title',
            'description' => 'Description',
            'type' => 'Type',
            'coupon_perameter' => 'Perameters',
            'notify' => 'Notify',
            'status' => 'Status',
            'created_on' => 'Created On',
            'updated_on' => 'Updated On',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCouponKey() {
        return $this->hasOne(\common\models\DiscountKey::className(), ['key' => 'coupon_key']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantCoupons() {
        return $this->hasMany(\common\models\RestaurantCoupons::className(), ['coupon_id' => 'id']);
    }

    public function beforeValidate() {

        parent::beforeValidate();
        $this->coupon_perameter = Json::encode(Yii::$app->request->post('discount_perameters'));
        if ($this->isNewRecord) {
            $datetime = new \DateTime('now');
            $this->created_on = $datetime->format('Y-m-d H:i:s');
        }
        return TRUE;
    }

    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        if ($this->type == "Restaurant") {
            $restaurant_coupon_model = new \common\models\base\RestaurantCoupons();
            $restaurant_coupon_model->restaurant_id = Yii::$app->request->post('restaurant');
            $restaurant_coupon_model->coupon_id = $this->id;
            $restaurant_coupon_model->insert(FALSE);
        }
    }

}
