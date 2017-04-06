<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

 
class DeliveryBoy extends ActiveRecord implements IdentityInterface {

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%tbl_delivery_boy}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username) {
		//echo $username;die;
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }
    
    /*  CUSTOM FUNCTIONS ADDED BY ANISH M. STARTS  */
    /**
     * Finds user by mobile_no
     *
     * @param string mobile_no
     * @return static|null
     */
    public static function findByMobileno($mobileno) {
        return static::findOne(['mobile_no' => $mobileno, 'status' => self::STATUS_ACTIVE]);
    }
    
    
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
         //return $this->password_hash;
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
 public function edit($id, $model) {  
        $user = static::findOne($id);
        $img_name = uniqid().".png";
        $this->convertStringToImage($model['profile_pic'],$img_name);
        $user->username    = $model['username'];
        $user->profile_pic = $img_name;
        $user->email      = $model['email'];
        $user->first_name = $model['first_name'];
        $user->last_name  = $model['last_name'];
        $user->mobile_no  = $model['mobile_no']; 
		$user->setPassword($model['password_hash']); 
        $user->generateAuthKey();
         
        if ($user->save()) {
            return $user;
        } else {
            return $user->errors;
        }
        return null;
    }
 
  

public function convertStringToImage($string,$img_name)
{ 
         
// Get image string posted from Android App 
     
    // Decode Image
        $path ='';
       // $path =BASE_URL;
        $path ="uploads/delivery_boy/"; 
	$img = str_replace('data:image/png;base64,', '', $string);
	$img = str_replace(' ', '+', $string);
	$data = base64_decode($string);
	$file = $path.$img_name;
	$success = file_put_contents($file, $data);
	//print $success ? $file : 'Unable to save the file.';  
}
 

}
