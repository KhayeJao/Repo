<?php

namespace common\models\base;

use yii\web\UploadedFile;
use Yii;
use yii\helpers\Url;
use yii\helpers\CommonHelper;

/**
 * This is the base-model class for table "tbl_restaurant".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $title
 * @property string $slogan
 * @property string $address
 * @property string $area
 * @property string $city
 * @property string $sms_number
 * @property string $order_number
 * @property string $latitude
 * @property string $longitude
 * @property double $min_amount
 * @property string $logo
 * @property double $delivery_network
 * @property integer $delivery_mins
 * @property string $food_type Type of food of restaurant (Vegetarian,Non Vegetarian)
 * @property string $open_datetime_1
 * @property string $close_datetime_1
 * @property string $open_datetime_2
 * @property string $close_datetime_2
 * @property double $tax
 * @property double $vat
 * @property integer $service_charge
 * @property string $scharge_type
 * @property double $kj_share
 * @property string $does_tablebooking
 * @property double $prior_table_booking_time
 * @property double $table_booking_close_paddding_time
 * @property integer $table_slot_time
 * @property string $who_delivers
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $coupon_text
 * @property double $avg_rating
 * @property integer $is_featured
 * @property string $featured_image
 * @property integer $is_featured_table
 * @property string $status
 * @property string $created_date
 *
 * @property \common\models\Combo[] $combos
 * @property \common\models\Dish[] $dishes
 * @property \common\models\Menu[] $menus
 * @property \common\models\Order[] $orders
 * @property \common\models\User $user
 * @property \common\models\RestaurantArea[] $restaurantAreas
 * @property \common\models\RestaurantCoupons[] $restaurantCoupons
 * @property \common\models\RestaurantCuisine[] $restaurantCuisines
 * @property \common\models\RestaurantImages[] $restaurantImages
 * @property \common\models\RestaurantPhone[] $restaurantPhones
 * @property \common\models\RestaurantReview[] $restaurantReviews
 * @property \common\models\RestaurantServices[] $restaurantServices
 * @property \common\models\Topping[] $toppings
 */
Yii::$app->params['logoPath'] = Yii::getAlias('@uploadPath') . '/uploads/restaurant/';
Yii::$app->params['logoUrl'] = Yii::getAlias('@uploadUrl') . '/uploads/restaurant/';

class Restaurant extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_restaurant';
    }

    public function scenarios() {
        parent::scenarios();
        return [
            // on signup allow mass assignment of username
            'default' => ['user_id', 'title', 'slogan','db_app_logo','is_sponserd','logistics','is_takeorder','is_delivery_boy','is_callcenter','delivery_boy_validity','number_of_delivery_boy', 'amount','plan_as','delivery_boy_validity','is_sendemail', 'address', 'area', 'city', 'latitude', 'longitude', 'min_amount', 'delivery_network','delivery_mins', 'food_type', 'open_datetime_1', 'close_datetime_1', 'open_datetime_2', 'close_datetime_2', 'tax', 'vat', 'service_charge', 'scharge_type', 'kj_share', 'does_tablebooking', 'prior_table_booking_time', 'table_booking_close_paddding_time', 'table_slot_time', 'who_delivers', 'meta_keywords', 'meta_description', 'is_featured', 'is_featured_table', 'status', 'sms_number', 'order_number'],
            'create' => ['user_id', 'title', 'slogan','db_app_logo','is_sponserd','logistics','is_takeorder','is_delivery_boy','is_callcenter','delivery_boy_validity','number_of_delivery_boy','plan_as','amount','is_sendemail', 'address', 'area', 'city', 'latitude', 'longitude', 'min_amount', 'logo','advertise', 'delivery_network','delivery_mins', 'food_type', 'open_datetime_1', 'close_datetime_1', 'open_datetime_2', 'close_datetime_2', 'tax', 'vat', 'service_charge', 'scharge_type', 'kj_share', 'does_tablebooking', 'prior_table_booking_time', 'table_booking_close_paddding_time', 'table_slot_time', 'who_delivers', 'meta_keywords', 'meta_description', 'is_featured', 'is_featured_table', 'status', 'sms_number', 'order_number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['user_id', 'title', 'slogan','is_sponserd','logistics','is_takeorder','is_callcenter','is_delivery_boy','is_sendemail', 'address', 'area', 'city', 'latitude', 'longitude', 'min_amount', 'logo','advertise', 'delivery_network','delivery_mins', 'food_type', 'open_datetime_1', 'close_datetime_1', 'open_datetime_2', 'close_datetime_2', 'tax', 'vat', 'service_charge', 'scharge_type', 'kj_share', 'prior_table_booking_time', 'table_booking_close_paddding_time', 'table_slot_time', 'does_tablebooking', 'who_delivers', 'meta_keywords', 'meta_description', 'is_featured', 'is_featured_table', 'status', 'sms_number', 'order_number'], 'required'],
            ['logo', 'required', 'on' => 'create'],
            [['created_date'], 'required', 'on' => ['create']],
            [['user_id', 'service_charge', 'table_slot_time', 'is_takeorder','is_featured', 'is_featured_table'], 'integer'],
            [['min_amount', 'delivery_network', 'tax', 'vat', 'kj_share', 'prior_table_booking_time', 'table_booking_close_paddding_time', 'avg_rating','delivery_mins'], 'number'],
            [['scharge_type', 'does_tablebooking', 'who_delivers', 'status'], 'string'],
            [['title', 'logo', 'coupon_text', 'featured_image'], 'string', 'max' => 200],
            [['slogan'], 'string', 'max' => 300],
            [['address', 'meta_keywords'], 'string', 'max' => 500],
            [['area', 'city', 'latitude', 'longitude'], 'string', 'max' => 50],
            [['open_datetime_1', 'close_datetime_1', 'open_datetime_2', 'close_datetime_2'], 'string', 'max' => 15],
            [['meta_description'], 'string', 'max' => 700],
            [['logo'], 'image', 'extensions' => 'jpg, gif, png', 'maxSize' => 1024 * 1024 * 2, 'minWidth' => 250, 'maxWidth' => 500, 'minHeight' => 150, 'maxHeight' => 300],
            [['advertise'], 'image', 'extensions' => 'jpg, gif, png', 'maxSize' => 1024 * 1024 * 2, 'minWidth' => 250, 'maxWidth' => 500, 'minHeight' => 150, 'maxHeight' => 300]
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_id' => 'User',
            'title' => 'Title',
            'slogan' => 'Slogan', 
            'is_takeorder'=>' Takeorder show in  menue List',
			'is_sponserd' => 'Show in Sponsered List',
			'logistics' => 'Show logistics User',
            'address' => 'Address',
            'area' => 'Area',
            'city' => 'City',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'min_amount' => 'Min Amount',
            'sms_number' => 'SMS Number',
            'order_number' => 'Order Number',
            'logo' => 'Logo',
            'advertise' =>'Advertise',
            'delivery_network' => 'Delivery Network',
            'delivery_mins' => 'Delivery Munites',
            'food_type' => 'Type of food of restaurant (Vegetarian,Non Vegetarian)',
            'open_datetime_1' => 'Open Datetime 1',
            'close_datetime_1' => 'Close Datetime 1',
            'open_datetime_2' => 'Open Datetime 2',
            'close_datetime_2' => 'Close Datetime 2',
            'tax' => 'Tax',
            'vat' => 'Vat',
            'service_charge' => 'Service Charge',
            'scharge_type' => 'Scharge Type',
            'kj_share' => 'Kj Share',
            'does_tablebooking' => 'Does Tablebooking',
            'prior_table_booking_time' => 'Prior Table Booking Time',
            'table_booking_close_paddding_time' => 'Table Booking Close Paddding Time',
            'table_slot_time' => 'Table Slot Time',
            'who_delivers' => 'Who Delivers',
            'meta_keywords' => 'Meta Keywords',
            'meta_description' => 'Meta Description',
            'coupon_text' => 'Coupon Text',
            'avg_rating' => 'Avg Rating',
            'is_featured' => 'Is Featured',
            'featured_image' => 'Featured Image',
            'is_featured_table' => 'Is Featured Table',
            'is_sendemail' => 'Send Email To Logistic Customer',
			'is_callcenter'=>'Call Center',
            'is_delivery_boy' => 'DB APP Subscription',
			'delivery_boy_validity' => 'EXPIRED ON',
			'plan_as' => 'Choose Plan',
			'number_of_delivery_boy' => 'Number Of Delivery Boy',
			'db_app_logo'=>'DB APP Logo',
			'amount' => 'Amount',			 
            'status' => 'Status',
            'created_date' => 'Created Date'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCombos() {
        return $this->hasMany(\common\models\Combo::className(), ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDishes() {
        return $this->hasMany(\common\models\Dish::className(), ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenus() { 
        return $this->hasMany(\common\models\Menu::className(), ['restaurant_id' => 'id'])->andWhere(['tbl_menu.status' => 'Active'])->orderBy('order');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders() {
        return $this->hasMany(\common\models\Order::className(), ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(\common\models\User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantAreas() {
        return $this->hasMany(\common\models\RestaurantArea::className(), ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantCoupons() {
        return $this->hasMany(\common\models\RestaurantCoupons::className(), ['restaurant_id' => 'id']);
    }

    /*
     * CUSTOM FUNCTION ADDED BY ANISH M. WHICH WILL RETURN ONLY ACTIVE COUPONS OF RESTAURANTS
     */

    public function getRestaurantActiveCoupons() {
        return $this->hasMany(\common\models\Coupons::className(), ['id' => 'coupon_id'])->viaTable('tbl_restaurant_coupons', ['restaurant_id' => 'id'])->andWhere(['tbl_coupons.status' => 'Active']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantCuisines() {
        return $this->hasMany(\common\models\RestaurantCuisine::className(), ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantImages() {
        return $this->hasMany(\common\models\RestaurantImages::className(), ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantPhones() {
        return $this->hasMany(\common\models\RestaurantPhone::className(), ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantReviews() {
        return $this->hasMany(\common\models\RestaurantReview::className(), ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantServices() {
        return $this->hasMany(\common\models\RestaurantServices::className(), ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToppings() {
        return $this->hasMany(\common\models\Topping::className(), ['restaurant_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTables() {
        return $this->hasMany(\common\models\Table::className(), ['restaurant_id' => 'id']);
    }

    /*
     * CUSTOM FUNCTION BY ANISH M. WHICH RETURNS TRUE IF RESTAURANT IS LESS THEN 3 MONTHS OLD ELSE FALSE
     */

    public function getIsRestaurantNew() {
        if (strtotime($this->created_date) < strtotime('-90 days')) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * fetch stored image file name with complete path
     * @return string
     */
    public function getImageFile() {

        return isset($this->logo) ? Yii::$app->params['logoPath'] . $this->logo : null;
    }
	
	public function getAPPImageFile() {

        return isset($this->db_app_logo) ? Yii::$app->params['logoPath'] . $this->db_app_logo : null;
    }


    public function getResizeImageFile() {

        return isset($this->logo) ? Yii::$app->params['logoPath'] . "resize/" . $this->logo : null;
    }
	
	 public function getResizeAPPImageFile() {

        return isset($this->db_app_logo) ? Yii::$app->params['logoPath'] . "resize/" . $this->db_app_logo : null;
    }

    public function getImageFileImage() {

        return isset($this->featured_image) ? Yii::$app->params['logoPath'] . $this->featured_image : null;
    }

    public function getResizeImageFileImage() {

        return isset($this->featured_image) ? Yii::$app->params['logoPath'] . "resize/" . $this->featured_image : null;
    }
    
    /* advertige image  */
 public function getAdvertigeImage() {

        return isset($this->advertise) ? Yii::$app->params['logoPath'] . $this->advertise : null;
    }

    public function getResizeAdvertigeImage() {

        return isset($this->advertise) ? Yii::$app->params['logoPath'] . "resize/" . $this->advertise : null;
    }


    /**
     * fetch stored image url
     * @return string
     */
    public function getImageUrl() {

        // return a default image placeholder if your source avatar is not found
        $avatar = isset($this->logo) ? $this->logo : 'default.jpg';
        //return Yii::getAlias('@uploadPath') . $avatar;
        return Yii::$app->params['logoUrl'] . $avatar;
    }

	public function getAPPImageUrl() {

        // return a default image placeholder if your source avatar is not found
        $avatar = isset($this->db_app_logo) ? $this->db_app_logo : 'default.jpg';
        //return Yii::getAlias('@uploadPath') . $avatar;
        return Yii::$app->params['logoUrl'] . $avatar;
    }
	
    public function getImageFUrl() {

        // return a default image placeholder if your source avatar is not found
        $avatar = isset($this->featured_image) ? $this->featured_image : 'default.jpg';
        //return Yii::getAlias('@uploadPath') . $avatar;
        return Yii::$app->params['logoUrl'] . $avatar;
    }

 public function getImageAUrl() {

        // return a default image placeholder if your source avatar is not found
        $avatar = isset($this->advertise) ? $this->advertise : 'default.jpg';
        //return Yii::getAlias('@uploadPath') . $avatar;
        return Yii::$app->params['logoUrl'] . $avatar;
    }

    /**
     * fetch stored image url
     * @return string
     */
    public function getResizeImageUrl() {

        // return a default image placeholder if your source avatar is not found
        $avatar = isset($this->logo) ? $this->logo :
                'default.jpg';
        //return Yii::getAlias('@uploadPath') . $avatar;
        return Yii::$app->params['logoUrl'] . "resize/" . $avatar;
    }
	
	 public function getResizeAPPImageUrl() {

        // return a default image placeholder if your source avatar is not found
        $avatar = isset($this->db_app_logo) ? $this->db_app_logo :
                'default.jpg';
        //return Yii::getAlias('@uploadPath') . $avatar;
        return Yii::$app->params['logoUrl'] . "resize/" . $avatar;
    }

    public function getResizeImageFUrl() {

        // return a default image placeholder if your source avatar is not found
        $avatar = isset($this->featured_image) ? $this->featured_image :
                'default.jpg';
        //return Yii::getAlias('@uploadPath') . $avatar;
        return Yii::$app->params['logoUrl'] . "resize/" . $avatar;
    }

    /**
     * Process upload of image
     *
     * @return mixed the uploaded image instance
     */
    public function uploadImage() {
        // get the uploaded file instance. for multiple file uploads
        // the following data will return an array (you may need to use
        // getInstances method)
        $image = UploadedFile::getInstance($this, 'logo');
        // if no image was uploaded abort the upload
        if (empty($image))
            return false;
        $ext = end((explode(".", $image->name)));
        // generate a unique file name
        $this->logo = Yii::$app->security->generateRandomString() . ".$ext";
        return $image;
    }

	
	 public function uploadAppmage() {
        // get the uploaded file instance. for multiple file uploads
        // the following data will return an array (you may need to use
        // getInstances method)
        $image = UploadedFile::getInstance($this, 'db_app_logo');
        // if no image was uploaded abort the upload
        if (empty($image))
            return false;
        $ext = end((explode(".", $image->name)));
        // generate a unique file name
        $this->db_app_logo = Yii::$app->security->generateRandomString() . ".$ext";
        return $image;
    }
	
	
    public function uploadFImage() {
        // get the uploaded file instance. for multiple file uploads
        // the following data will return an array (you may need to use
        // getInstances method)
        $image = UploadedFile::getInstance($this, 'featured_image');
        // if no image was uploaded abort the upload
        if (empty($image))
            return false;
        $ext = end((explode(".", $image->name)));
        // generate a unique file name
        $this->featured_image = Yii::$app->security->generateRandomString() . ".$ext";
        return $image;
    }
    
     public function uploadAImage() {
        // get the uploaded file instance. for multiple file uploads
        // the following data will return an array (you may need to use
        // getInstances method)
        $image = UploadedFile::getInstance($this, 'advertise');
        // if no image was uploaded abort the upload
        if (empty($image))
            return false;
        $ext = end((explode(".", $image->name)));
        // generate a unique file name
        $this->advertise = Yii::$app->security->generateRandomString() . ".$ext";
        return $image;
    }

    /**
     * Process deletion of image
     * @var the location of file name with path
     *
     * @return boolean the status of deletion
     */
    public function deleteImage() {
        $file = $this->getImageFile();
        $Rfile = $this->getResizeImageFile();
		$APPfile = $this->getAPPImageFile();
        $APPRfile = $this->getResizeAPPImageFile();

        $fileFI = $this->getImageFileImage();
        $RfileFI = $this->getResizeImageFileImage();
        /* advertise  */
        
        $fileAV = $this->getAdvertigeImage();
        $RfileAV = $this->getResizeAdvertigeImage();
        
        /* End  */
        
        if (empty($file) || !file_exists($file) || empty($Rfile) || !file_exists($Rfile) || empty($APPfile) || !file_exists($APPfile) || empty($APPRfile) || !file_exists($APPRfile) ||  empty($fileFI) || !file_exists($RfileFI) || empty($fileAV) || !file_exists($RfileAV)) {
            return false;
        }

        // check if uploaded file can be deleted on server
        if (!unlink($file) || !unlink($Rfile) ||  !unlink($APPfile) || !unlink($APPRfile) || !unlink($fileFI) || !unlink($RfileFI) || !unlink($fileAV) || !unlink($RfileAV)) {
            return false;
        }

        // if deletion successful, reset your file attributes
        $this->logo = null;
		$this->db_app_logo = null;
        $this->featured_image = null;
        $this->advertise = null;
        return true;
    }

    public function isOpen($time = '') {
        if (!$time) {
            $time = strtotime(date('H:i:s'));
        }
        if (($time > strtotime($this->open_datetime_1) AND $time < strtotime($this->close_datetime_1)) OR ( $time > strtotime($this->open_datetime_2) AND $time < strtotime($this->close_datetime_2))) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function isOpenForTableBooking($time = '') {
        if (!$time) {
            $time = strtotime(date('H:i:s'));
        }
        if (($time > strtotime($this->open_datetime_1) AND $time < (strtotime($this->close_datetime_1) - (60 * $this->table_booking_close_paddding_time))) OR ( $time > strtotime($this->open_datetime_2) AND $time < (strtotime($this->close_datetime_2) - (60 * $this->table_booking_close_paddding_time)))) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function getRestaurantUrl() {
        return URL::to(['restaurant/view', 'id' => $this->id, 'title' => CommonHelper::getUrlFriendlyString($this->title)]);
    }

//    public function beforeSave($insert) {
//        if ($this->isNewRecord) {
//            $datetime = new \DateTime('now');
//            $this->created_date = $datetime->format('Y-m-d H:i:s');
//        }
//        parent::beforeSave($insert);
//    }

    public function beforeValidate() {
        parent::beforeValidate();
        if ($this->is_featured) {
            if (!$this->featured_image) {
                $this->addError('featured_image', "Select featured image");
                return FALSE;
            }
        }
        return TRUE;
    }

}
