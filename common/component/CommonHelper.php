<?php

/*

 * To change this license header, choose License Headers in Project Properties.

 * To change this template file, choose Tools | Templates

 * and open the template in the editor.

 */

namespace yii\helpers;

use Yii;
use common\models\User;
use common\models\Restaurant;
use yii\web\ForbiddenHttpException;

class CommonHelper {

    public static function resize($imagePath, $destinationWidth, $destinationHeight, $destinationPath) {
        if (file_exists($imagePath)) {

            $imageInfo = getimagesize($imagePath);

            $sourceWidth = $imageInfo[0];

            $sourceHeight = $imageInfo[1];

            $source_aspect_ratio = $sourceWidth / $sourceHeight;

            $thumbnail_aspect_ratio = $destinationWidth / $destinationHeight;

            if ($sourceWidth <= $destinationWidth && $sourceHeight <= $destinationHeight) {

                $thumbnail_image_width = $sourceWidth;

                $thumbnail_image_height = $sourceHeight;
            } elseif ($thumbnail_aspect_ratio > $source_aspect_ratio) {

                $thumbnail_image_width = (int) ($destinationHeight * $source_aspect_ratio);

                $thumbnail_image_height = $destinationHeight;
            } else {

                $thumbnail_image_width = $destinationWidth;

                $thumbnail_image_height = (int) ($destinationWidth / $source_aspect_ratio);
            }

            $destinationWidth = $thumbnail_image_width;

            $destinationHeight = $thumbnail_image_height;

            $mimeType = $imageInfo['mime'];

            $destinationWidth = $thumbnail_image_width;

            $destinationHeight = $thumbnail_image_height;

            $destination = imagecreatetruecolor($destinationWidth, $destinationHeight);

            if ($mimeType == 'image/jpeg' || $mimeType == 'image/pjpeg') {

                $source = imagecreatefromjpeg($imagePath);

                imagecopyresampled($destination, $source, 0, 0, 0, 0, $destinationWidth, $destinationHeight, $sourceWidth, $sourceHeight);

                $destinationPath = $destinationPath;

                imagejpeg($destination, $destinationPath);
            } else if ($mimeType == 'image/gif') {

                $source = imagecreatefromgif($imagePath);

                imagecopyresampled($destination, $source, 0, 0, 0, 0, $destinationWidth, $destinationHeight, $sourceWidth, $sourceHeight);

                $destinationPath = $destinationPath;

                imagegif($destination, $destinationPath);
            } else if ($mimeType == 'image/png' || $mimeType == 'image/x-png') {

                $source = imagecreatefrompng($imagePath);

                imagecopyresampled($destination, $source, 0, 0, 0, 0, $destinationWidth, $destinationHeight, $sourceWidth, $sourceHeight);

                $destinationPath = $destinationPath;

                imagepng($destination, $destinationPath);
            } else {

                echo 'This image type is not supported.';
            }
        } else {

            echo 'The requested file does not exist.';
        }
    }

    public static function restaurantAccessControl($id) {



        $loginID = Yii::$app->user->getId();

        $user_profile = User::findOne($loginID);

        if ($user_profile->type == "restaurant") {

            $rest = Restaurant::findAll(['user_id' => $loginID]);

            $restArray = array();

            for ($i = 0; $i < count($rest); $i++) {

                $restArray[] = $rest[$i]->id;
            }

            if (!in_array($id, $restArray)) {

                throw new ForbiddenHttpException("You are not allow to use this page");
            } else {

                return true;
            }
        }
    }

    public static function comboAccessControl($id) {

        $loginID = Yii::$app->user->getId();

        $user_profile = User::findOne($loginID);

        if ($user_profile->type == "restaurant") {

            $rest = Restaurant::findAll(['user_id' => $loginID]);

            $comboArray = array();

            for ($i = 0; $i < count($rest); $i++) {

                echo $rest[$i]->id;

                $combo = \common\models\Combo::findAll(['restaurant_id' => $rest[$i]->id]);

                for ($c = 0; $c < count($combo); $c++) {

                    $comboArray[] = $combo[$c]->id;
                }
            }

            if (!in_array($id, $comboArray)) {

                throw new ForbiddenHttpException("You are not allow to use this page");
            } else {

                return true;
            }
        }
    }

    public static function dishAccessControl($id) {

        $loginID = Yii::$app->user->getId();

        $user_profile = User::findOne($loginID);

        if ($user_profile->type == "restaurant") {

            $rest = Restaurant::findAll(['user_id' => $loginID]);

            $dishArray = array();

            for ($i = 0; $i < count($rest); $i++) {

                $dish = \common\models\Dish::findAll(['restaurant_id' => $rest[$i]->id]);

                for ($c = 0; $c < count($dish); $c++) {

                    $dishArray[] = $dish[$c]->id;
                }
            }

            if (!in_array($id, $dishArray)) {

                throw new ForbiddenHttpException("You are not allow to use this page");
            } else {

                return true;
            }
        }
    }

    public static function dishtoppingAccessControl($id) {

        $loginID = Yii::$app->user->getId();

        $user_profile = User::findOne($loginID);

        if ($user_profile->type == "restaurant") {

            $rest = Restaurant::findAll(['user_id' => $loginID]);

            $toppingArray = array();

            for ($i = 0; $i < count($rest); $i++) {

                $dish = \common\models\Dish::findAll(['restaurant_id' => $rest[$i]->id]);

                for ($c = 0; $c < count($dish); $c++) {

                    $topping = \common\models\ToppingGroup::findAll(['dish_id' => $dish[$c]->id]);

                    for ($t = 0; $t < count($topping); $t++) {

                        $toppingArray[] = $topping[$t]->id;
                    }
                }
            }

            if (!in_array($id, $toppingArray)) {

                throw new ForbiddenHttpException("You are not allow to use this page");
            } else {

                return true;
            }
        }
    }

    public static function getUrlFriendlyString($str) {

        // convert spaces to '-', remove characters that are not alphanumeric
        // or a '-', combine multiple dashes (i.e., '---') into one dash '-'.

        $str = ereg_replace("[-]+", "-", ereg_replace("[^a-z0-9-]", "", strtolower(str_replace(" ", "-", $str))));

        return $str;
    }

}
