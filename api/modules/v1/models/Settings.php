<?php

namespace api\modules\v1\models;

use Yii;
use yii\web\UploadedFile;
use pendalf89\filemanager\behaviors\MediafileBehavior;
/**
 * This is the base-model class for table "tbl_settings".
 *
 * @property integer $id
 * @property string $title
 * @property string $key
 * @property string $value
 * @property string $type
 */
Yii::$app->params['ImagePath'] = Yii::getAlias('@uploadPath') . '/uploads/settings/';
Yii::$app->params['ImageUrl'] = Yii::getAlias('@uploadUrl') . '/uploads/settings/';

class Settings extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_settings';
    }
    
    public function behaviors()
{
    return [
        'mediafile' => [
            'class' => MediafileBehavior::className(),
            'name' => 'settings',
            'attributes' => [
                'value',
            ],
        ]
    ];
}

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['value'], 'required'],
            [['value'], 'string'],
            [['title'], 'string', 'max' => 500],
            [['key'], 'string', 'max' => 200],
            [['type'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'key' => 'Key',
            'value' => 'Value',
            'type' => 'Type',
        ];
    }

    /**
     * fetch stored value file name with complete path 
     * @return string
     */
    public function getImageFile() {

        return isset($this->value) ? Yii::$app->params['ImagePath'] . $this->value : null;
    }

    public function getResizeImageFile() {

        return isset($this->value) ? Yii::$app->params['ImagePath'] . "resize/" . $this->value : null;
    }

    /**
     * fetch stored value url
     * @return string
     */
    public function getImageUrl() {

        // return a default value placeholder if your source avatar is not found
        $avatar = isset($this->value) ? $this->value : 'default.jpg';
        //return Yii::getAlias('@uploadPath') . $avatar;
        return Yii::$app->params['ImageUrl'] . $avatar;
    }

    /**
     * fetch stored value url
     * @return string
     */
    public function getResizeImageUrl() {

        // return a default value placeholder if your source avatar is not found
        $avatar = isset($this->value) ? $this->value : 'default.jpg';
        //return Yii::getAlias('@uploadPath') . $avatar;
        return Yii::$app->params['ImageUrl'] . "resize/" . $avatar;
    }

    /**
     * Process upload of value
     *
     * @return mixed the uploaded value instance
     */
    public function uploadImage() {
        // get the uploaded file instance. for multiple file uploads
        // the following data will return an array (you may need to use
        // getInstances method)
        $value = UploadedFile::getInstance($this, 'value');
        // if no value was uploaded abort the upload
        if (empty($value))
            return false;
        $ext = end((explode(".", $value->name)));
        // generate a unique file name
        $this->value = Yii::$app->security->generateRandomString() . ".$ext";
        // the uploaded value instance

        return $value;
    }

    /**
     * Process deletion of value
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
        if (!unlink($file) || !unlink($Cfile)) {
            return false;
        }

        // if deletion successful, reset your file attributes
        $this->value = null;
        return true;
    }

}
