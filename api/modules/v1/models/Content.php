<?php

namespace api\modules\v1\models;

use Yii;

/**
 * This is the base-model class for table "tbl_content".
 *
 * @property integer $id
 * @property string $page_key
 * @property string $Title
 * @property string $content
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_desctiption
 * @property string $status
 */
class Content extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_content';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['page_key', 'Title', 'content', 'meta_title', 'meta_keywords', 'meta_desctiption', 'status'], 'required'],
            [['content', 'meta_title', 'meta_keywords', 'meta_desctiption', 'status'], 'string'],
            [['page_key'], 'string', 'max' => 200],
            [['Title'], 'string', 'max' => 255],
            [['page_key'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'page_key' => 'Page Key',
            'Title' => 'Title',
            'content' => 'Content',
            'meta_title' => 'Meta Title',
            'meta_keywords' => 'Meta Keywords',
            'meta_desctiption' => 'Meta Desctiption',
            'status' => 'Status',
        ];
    }

}
