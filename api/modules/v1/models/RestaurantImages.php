<?php

namespace api\modules\v1\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the base-model class for table "tbl_restaurant_images".
 *
 * @property integer $id
 * @property integer $restaurant_id
 * @property string $image
 * @property string $title
 *
 * @property \common\models\Restaurant $restaurant
 */
Yii::$app->params['RImagePath'] = Yii::getAlias('@uploadPath') . '/uploads/restaurant_image/';
Yii::$app->params['RIamgeUrl'] = Yii::getAlias('@uploadUrl') . '/uploads/restaurant_image/';

class RestaurantImages extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_restaurant_images';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['restaurant_id', 'image', 'title'], 'required'],
            [['restaurant_id'], 'integer'],
            [['image'], 'string', 'max' => 200],
            [['title'], 'string', 'max' => 150]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'restaurant_id' => 'Restaurant',
            'image' => 'Image',
            'title' => 'Title',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant() {
        return $this->hasOne(\common\models\Restaurant::className(), ['id' => 'restaurant_id']);
    }

    /**
     * fetch stored image file name with complete path 
     * @return string
     */
    public function getImageFile() {

        return isset($this->image) ? Yii::$app->params['RImagePath'] . $this->image : null;
    }

    public function getResizeImageFile() {

        return isset($this->image) ? Yii::$app->params['RImagePath'] . "resize/" . $this->image : null;
    }

    /**
     * fetch stored image url
     * @return string
     */
    public function getImageUrl() {

        // return a default image placeholder if your source avatar is not found
        $avatar = isset($this->image) ? $this->image : 'default.jpg';
        //return Yii::getAlias('@uploadPath') . $avatar;
        return Yii::$app->params['RIamgeUrl'] . $avatar;
    }

    /**
     * fetch stored image url
     * @return string
     */
    public function getResizeImageUrl() {

        // return a default image placeholder if your source avatar is not found
        $avatar = isset($this->image) ? $this->image : 'default.jpg';
        //return Yii::getAlias('@uploadPath') . $avatar;
        return Yii::$app->params['RIamgeUrl'] . "resize/" . $avatar;
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
        $image = UploadedFile::getInstance($this, 'image');
        // if no image was uploaded abort the upload
        if (empty($image))
            return false;
        $ext = end((explode(".", $image->name)));
        // generate a unique file name
        $this->image = Yii::$app->security->generateRandomString() . ".$ext";
        // the uploaded image instance

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
        if (empty($file) || !file_exists($file) || empty($Rfile) || !file_exists($Rfile)) {
            return false;
        }

        // check if uploaded file can be deleted on server
        if (!unlink($file) || !unlink($Rfile)) {
            return false;
        }

        // if deletion successful, reset your file attributes
        $this->image = null;
        return true;
    }

}
