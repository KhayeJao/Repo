<?php

namespace api\modules\v1\controllers;

use yii\rest\ActiveController;
use yii\web\Response;
use yii\filters\AccessControl;

/**
 * Country Controller API
 *
 * @author Budi Irawan <deerawan@gmail.com>
 */
class AreaController extends ActiveController {

    public $modelClass = 'api\modules\v1\models\Country';

    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_JSON;
//        $behaviors['access'] = [
//            'class' => AccessControl::className(),
//        ];
        return $behaviors;
    }

    public function actionSearch() {
        return array(
            'key3' => 'val3',
            'key4' => 'val4',
        );
    }

}
