<?php

namespace api\modules\v1\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the base-model class for table "tbl_cuisine".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $image
 * @property string $status
 * @property string $is_featured
 *
 * @property \common\models\RestaurantCuisine[] $restaurantCuisines
 */
Yii::$app->params['CImagePath'] = Yii::getAlias('@uploadPath') . '/uploads/cuisine/';
Yii::$app->params['CImageUrl'] = Yii::getAlias('@uploadUrl') . '/uploads/cuisine/';

class Cuisine extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_cuisine';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['title', 'description', 'image', 'status','is_featured'], 'required'],
            [['status','is_featured'], 'string'],
            [['title'], 'string', 'max' => 150],
            [['description'], 'string', 'max' => 250],
            [['image'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'image' => 'Image',
            'status' => 'Status',
            'is_featured' => 'Is Featured',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantCuisines() {
        return $this->hasMany(\common\models\RestaurantCuisine::className(), ['cuisine_id' => 'id']);
    }

    /**
     * fetch stored image file name with complete path 
     * @return string
     */
    public function getImageFile() {

        return isset($this->image) ? Yii::$app->params['CImagePath'] . $this->image : null;
    }

    public function getResizeImageFile() {

        return isset($this->image) ? Yii::$app->params['CImagePath'] . "resize/" . $this->image : null;
    }

    /**
     * fetch stored image url
     * @return string
     */
    public function getImageUrl() {

        // return a default image placeholder if your source avatar is not found
        $avatar = isset($this->image) ? $this->image : 'default.jpg';
        //return Yii::getAlias('@uploadPath') . $avatar;
        return Yii::$app->params['CImageUrl'] . $avatar;
    }

    /**
     * fetch stored image url
     * @return string
     */
    public function getResizeImageUrl() {

        // return a default image placeholder if your source avatar is not found
        $avatar = isset($this->image) ? $this->image : 'default.jpg';
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
        $Cfile = $this->getResizeImageFile();
        if (empty($file) || !file_exists($file) || empty($Cfile) || !file_exists($Cfile)) {
            return false;
        }

        // check if uploaded file can be deleted on server
        if (is_file($file)){
            unlink($file);
        }
        
        if(is_file($Cfile)){
            unlink($Cfile);
        }
        

        // if deletion successful, reset your file attributes
        $this->image = null;
        return true;
    }

}
