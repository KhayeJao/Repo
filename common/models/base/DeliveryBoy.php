<?php

namespace common\models\base;

use Yii;
use yii\web\UploadedFile;

 
Yii::$app->params['CImagePath'] = Yii::getAlias('@uploadPath') . '/frontend/web/uploads/delivery_boy/';
Yii::$app->params['CImageUrl'] = Yii::getAlias('@uploadUrl') . '/frontend/web/uploads/delivery_boy/';
 
class  DeliveryBoy extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_delivery_boy';
    }

    /**
     * @inheritdoc area_id
     */
    public function rules() {
        return [ 
            ['username', 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/', 'message' => 'Your username can only contain alphanumeric characters, underscores and dashes.'],
            [['username', 'email', 'first_name', 'last_name', 'mobile_no','password_hash','status'], 'required'],
			['username', 'unique', 'targetClass' => '\common\models\DeliveryBoy', 'message' => 'This username has already been taken.'],
            ['mobile_no', 'unique', 'targetClass' => '\common\models\DeliveryBoy', 'message' => 'This mobile number has already been registered.'], 
            ['license_number', 'unique', 'targetClass' => '\common\models\DeliveryBoy', 'message' => 'This license number has already been registered.'], 
            [['created_at', 'updated_at'], 'integer'], 
			[['restaurant_id'], 'integer'],
            [['first_name', 'last_name'], 'string', 'max' => 50],
            [['area_id','delivery_time','license_number'], 'string', 'max' => 150],
            ['mobile_no', 'validateMobile'], 
            [['mobile_no'], 'string', 'max' => 10, 'min' => 10],
            [['mobile_v_code'], 'string', 'max' => 10],
            [['auth_key'], 'string', 'max' => 32],
            [['last_login_ip'], 'string', 'max' => 20],
            [['profile_pic'], 'string', 'max' => 200],
            [['license_image'], 'string', 'max' => 200]
             
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
            'area_id' => 'Delivery Area',
            'delivery_time' => 'Delivery Timing',
            'license_number' => 'License Number',
            'license_image' => 'License Image',
            'profile_pic'=>'Profile Pic',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password',
            'password_reset_token' => 'Password Reset Token',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'last_login_ip' => 'Last Login IP',
        ];
    }

    public function validateMobile($attribute, $params) {
        if ($this->mobile_no) {
            $user_found = DeliveryBoy::findOne(['mobile_no' => $this->mobile_no]);
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
        return $this->hasMany(\common\models\Address::className(), ['user_id' => 'id']);
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
    
     /**
     * fetch stored image file name with complete path 
     * @return string
     */
    public function getImageFile() {

        return isset($this->profile_pic) ? Yii::$app->params['CImagePath'] . $this->profile_pic : null;
    }

    public function getResizeImageFile() {

        return isset($this->profile_pic) ? Yii::$app->params['CImagePath'] . "resize/" . $this->profile_pic : null;
    }
    // lisence number image
    
     public function getImageFileImage() {

        return isset($this->license_image) ? Yii::$app->params['CImagePath'] . $this->license_image : null;
    }
    
    public function getResizeImageFileImage() {

        return isset($this->license_image) ? Yii::$app->params['CImageUrl'] . "resize/" . $this->license_image : null;
    }

    /**
     * fetch stored image url
     * @return string
     */
    public function getImageUrl() {

        // return a default image placeholder if your source avatar is not found
        $avatar = isset($this->profile_pic) ? $this->profile_pic : 'default.jpg';
        //return Yii::getAlias('@uploadPath') . $avatar;
        return Yii::$app->params['CImageUrl'] . $avatar;
    }


public function getImageFUrl() {

        // return a default image placeholder if your source avatar is not found
        $avatar = isset($this->license_image) ? $this->license_image : 'default.jpg';
        //return Yii::getAlias('@uploadPath') . $avatar;
        return Yii::$app->params['CImageUrl'] . $avatar;
    }
    /**
     * fetch stored image url
     * @return string
     */
    public function getResizeImageUrl() {

        // return a default image placeholder if your source avatar is not found
        $avatar = isset($this->profile_pic) ? $this->profile_pic : 'default.jpg';
        //return Yii::getAlias('@uploadPath') . $avatar;
        return Yii::$app->params['CImageUrl'] . "resize/" . $avatar;
    }



public function getResizeImageFUrl() {

        // return a default image placeholder if your source avatar is not found
        $avatar = isset($this->license_image) ? $this->license_image :
                'default.jpg';
        //return Yii::getAlias('@uploadPath') . $avatar;
        return Yii::$app->params['CImageUrl'] . "resize/" . $avatar;
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
        $profile_pic = UploadedFile::getInstance($this, 'profile_pic');
 
        // if no image was uploaded abort the upload
        if (empty($profile_pic))
            return false;
        $ext = end((explode(".", $profile_pic->name)));
        // generate a unique file name
        $this->profile_pic = Yii::$app->security->generateRandomString() . ".$ext";
        // the uploaded image instance

        return $profile_pic;
    }
	
     public function uploadFImage() {
        // get the uploaded file instance. for multiple file uploads
        // the following data will return an array (you may need to use
        // getInstances method)
        $image = UploadedFile::getInstance($this, 'license_image');
        // if no image was uploaded abort the upload
        if (empty($image))
            return false;
        $ext = end((explode(".", $image->name)));
        // generate a unique file name
        $this->license_image = Yii::$app->security->generateRandomString() . ".$ext";
        return $image;
    }
    
   

    /**
     * Process deletion of image
     * @var the location of file name with path
     *
     * @return boolean the status of deletion
     */
    public function deleteImage() {
		// profile image
		
        $file = $this->getImageFile();
        $Cfile = $this->getResizeImageFile();
        // linces image 
        $fileFI = $this->getImageFileImage();
        $RfileFI = $this->getResizeImageFileImage();
        
        
        if (empty($file) || !file_exists($file) || empty($Cfile) || !file_exists($Cfile) || empty($fileFI) || !file_exists($RfileFI) ) {
            return false;
        }

        // check if uploaded file can be deleted on server
        if (is_file($file)){
            unlink($file);
        }
        
        if(is_file($Cfile)){
            unlink($Cfile);
        }
         // check if uploaded file can be deleted on server
        if (!unlink($file) || !unlink($Cfile) || !unlink($fileFI) || !unlink($RfileFI)) {
            return false;
        }
        

        // if deletion successful, reset your file attributes
        $this->profile_pic = null;
        $this->license_image = null;
        return true;
    }
	
	
	/// psasword //
	
	/**
     * Finds user by email
     *
     * @param string email
     * @return static|null
     */
    public static function findByEmail($email) {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }
    
    
    
    /*  CUSTOM FUNCTIONS ADDED BY ANISH M. ENDS  */

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token) {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
                    'password_reset_token' => $token,
                    'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token) {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['tbl_delivery_boy.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId() {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey() {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password) {
        
        $native_method = (md5($password) == $this->password_hash);
        if ($native_method) {
            return $native_method;
        } else {
            return Yii::$app->security->validatePassword($password, $this->password_hash,$native_method); /* THIRD PARAMETER ADDED BY ANISH M. AS WE WANT TO CHECK IF LOGIN AS HAPPENING THROUGH YII METHOD OR MD5 METHOD */
        }
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password) {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
       // echo $this->password_hash;die;
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey() {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken() {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken() {
        $this->password_reset_token = null;
    }
	
	// end  //
	
	
	

}
