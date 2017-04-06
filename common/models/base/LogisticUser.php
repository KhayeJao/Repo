<?php

namespace common\models\base;

use Yii;

/**
 * This is the base-model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $first_name
 * @property string $last_name
 * @property string $mobile_no
 * @property string $fb_id
 * @property string $fb_profile
 * @property string $mobile_v_code
 * @property string $is_mobile_verified
 * @property string $is_email_verified
 * @property string $type
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $last_login_ip
 *
 * @property \common\models\Address[] $addresses
 * @property \common\models\FavDish[] $favDishes
 * @property \common\models\Guest[] $guests
 * @property \common\models\Order[] $orders
 * @property \common\models\Restaurant[] $restaurants
 * @property \common\models\RestaurantReview[] $restaurantReviews
 * @property \common\models\TblTableBooking[] $tblTableBookings
 *
 */
class LogisticUser extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_logistic_user';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['restaurant_id','username', 'email', 'first_name', 'last_name', 'mobile_no'], 'filter', 'filter' => function($value) {
            return trim(htmlentities(strip_tags(\yii\helpers\HtmlPurifier::process($value)), ENT_QUOTES, 'UTF-8'));
        }],
            ['username', 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/', 'message' => 'Your username can only contain alphanumeric characters, underscores and dashes.'],
            [['username', 'email', 'first_name', 'last_name', 'mobile_no'], 'required'],
            ['mobile_no', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This mobile number has already been registered.'],
            [['is_mobile_verified', 'is_email_verified', 'type','order_to','restaurant_id'], 'string'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'email', 'fb_id', 'fb_profile', 'password_hash', 'password_reset_token'], 'string', 'max' => 255],
            [['first_name', 'last_name'], 'string', 'max' => 50],
            ['mobile_no', 'validateMobile'],
            [['mobile_no'], 'string', 'max' => 10, 'min' => 10],
            [['mobile_v_code'], 'string', 'max' => 10],
            [['auth_key'], 'string', 'max' => 32],
            [['last_login_ip'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'email' => 'Email',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'mobile_no' => 'Mobile No',
            'fb_id' => 'Facebook ID',
            'fb_profile' => 'Facebook Profile',
            'mobile_v_code' => 'Mobile Verification Code',
            'is_mobile_verified' => 'Is Mobile Verified',
            'is_email_verified' => 'Is Email Verified',
            'type' => 'Type',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'status' => 'Status',
            'created_at' => 'Registered',
            'updated_at' => 'Updated At',
            'last_login_ip' => 'Last Login IP',
        ];
    }

    public function validateMobile($attribute, $params){
        if ($this->mobile_no) {
            $user_found = User::findOne(['mobile_no' => $this->mobile_no]);
            if ($user_found) {
                if ($user_found->id != $this->id) {
                    $this->addError($attribute, 'Mobile No has alreary been registered with us');
                }
            }
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddresses() {
        return $this->hasMany(\common\models\AddressL::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFavDishes() {
        return $this->hasMany(\common\models\FavDish::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGuests() {
        return $this->hasMany(\common\models\Guest::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders() {
        return $this->hasMany(\common\models\Order::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurants() {
        return $this->hasMany(\common\models\Restaurant::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantReviews() {
        return $this->hasMany(\common\models\RestaurantReview::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblTableBookings() {
        return $this->hasMany(\common\models\TableBooking::className(), ['user_id' => 'id']);
    }

    public function getFull_Name() {
        return $this->first_name . " " . $this->last_name;
    }
    public function getMobile() {
        return $this->mobile_no;
    }
     public function getRestaurantID() {
        return $this->restaurant_id;
    }
    

}
