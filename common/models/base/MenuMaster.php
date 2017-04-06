<?php

namespace common\models\base;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the base-model class for table "tbl_master_menu".
 *
 * @property integer $id
 * @property integer $parent_id 
 * @property string $title
 * @property string $excerpt
 * @property string $image
 * @property double $discount
 *
 * @property \common\models\Dish[] $dishes
 * @property \common\models\Menu $parent
 * @property \common\models\Menu[] $menus
 * @property \common\models\Restaurant $restaurant
 */
Yii::$app->params['MImagePath'] = Yii::getAlias('@uploadPath') . '/uploads/menu_image/';
Yii::$app->params['MImageUrl'] = Yii::getAlias('@uploadUrl') . '/uploads/menu_image/';

class MenuMaster extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_master_menu';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [ 
            [['title', 'order','excerpt', 'discount'], 'required'],
            [['discount','order'], 'number'],
            [['title','is_deleted'], 'string', 'max' => 250],
            [['excerpt'], 'string', 'max' => 500],
            [['status'], 'string'],
//            ['image','image', 'extensions' => ['png', 'jpg', 'gif'],'maxWidth' => '550','maxHeight' => '120'],
            [['image'], 'image', 'extensions' => 'jpg, gif, png', 'maxSize' => 1024 * 1024 * 2, 'minWidth' => 250, 'maxWidth' => 550, 'minHeight' => 110, 'maxHeight' => 125]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID', 
            'title' => 'Title',
            'excerpt' => 'Excerpt',
            'image' => 'Image',
            'discount' => 'Discount',
            'order'=>'Order In Menu List',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDishes() {
        return $this->hasMany(\common\models\Dish::className(), ['menu_id' => 'id'])->andWhere(['tbl_dish.status' => 'Active','tbl_dish.is_deleted' => 'No']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent() {
        return $this->hasOne(\common\models\Menu::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenus() {
        return $this->hasMany(\common\models\Menu::className(), ['parent_id' => 'id']);
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

        return isset($this->image) ? Yii::$app->params['MImagePath'] . $this->image : null;
    }

    public function getResizeImageFile() {

        return isset($this->image) ? Yii::$app->params['MImagePath'] . "resize/" . $this->image : null;
    }

    /**
     * fetch stored image url
     * @return string
     */
    public function getImageUrl() {

        if (trim($this->image) != "") {
            $avatar = isset($this->image) ? $this->image : 'default.jpg';
            //return Yii::getAlias('@uploadPath') . $avatar;
            return Yii::$app->params['MImageUrl'] . $avatar;
        } else {
            return FALSE;
        }
    }

    /**
     * fetch stored image url
     * @return string
     */
    public function getResizeImageUrl() {

        // return a default image placeholder if your source avatar is not found
//        $avatar = isset($this->image) ? $this->image : 'default.jpg';
        //return Yii::getAlias('@uploadPath') . $avatar;
        if (trim($this->image) != "")
            return Yii::$app->params['MImageUrl'] . "resize/" . $this->image;
        else
            return FALSE;
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParentMenu() {
        if ($this->root == 0) {
            return (object) ['title' => 'Root Menu'];
        } else {
            return $this->hasOne(\common\models\Category::className(), ['id' => 'root']);
        }
    }

}
