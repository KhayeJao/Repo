<?php

use yii\helpers\Json;
use yii\helpers\Html;
//use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
$coupon_details = common\models\base\Coupons::findOne($coupon_id);
if ($coupon_details) {
    $coupon_perameter = Json::decode($coupon_details->coupon_perameter);
}
?>
<div class="row">
    <div class="form-group field-coupons-code required">
        <label class="control-label col-sm-3" for="dish_id">Dish</label>
        <div class="col-sm-6">
            <?php
            echo Html::dropDownList('discount_perameters[dish_id]', ($coupon_details ? $coupon_perameter['dish_id'] : ''), ArrayHelper::map(common\models\base\Dish::findAll(['restaurant_id' => $restaurant_id,'is_deleted' => 'No','status' => 'Active']),'id','title'),['class'=>'form-control','required' => 'required','id' => 'dish_id']);
            ?>
            <div class="help-block help-block-error ">Select dish to apply discount</div>
        </div>
    </div>
    <div class="form-group field-coupons-code required">
        <label class="control-label col-sm-3" for="discount_type">Discount Type</label>
        <div class="col-sm-6">
            <?php
            echo Html::dropDownList('discount_perameters[discount_type]', ($coupon_details ? $coupon_perameter['discount_type'] : 'Percentage'), ['Percentage' => 'Percentage', 'Rs' => 'Rs'], ['class' => 'form-control', 'required' => 'required', 'id' => 'discount_type'])
            ?>
        </div>
    </div>
    
     <div class="form-group field-coupons-code">
        <label class="control-label col-sm-3" for="maximum_discount">Maximum Discount</label>
        <div class="col-sm-6">
            <?php
            echo Html::input('number', 'discount_perameters[maximum_discount]', ($coupon_details ? $coupon_perameter['maximum_discount'] : ''), ['class' => 'form-control', 'id'=>'maximum_discount']);
            ?>
            <div class="help-block help-block-error ">Maximum discount in Rs</div>
        </div>
    </div>
    
    
    <div class="form-group field-coupons-code required">
        <label class="control-label col-sm-3" for="discount_percentage">Discount</label>
        <div class="col-sm-6">
            <?php
            echo Html::input('number', 'discount_perameters[discount_percentage]', ($coupon_details ? $coupon_perameter['discount_percentage'] : 10), ['class' => 'form-control', 'required' => 'required', 'id' => 'discount_percentage']);
            ?>
        </div>
    </div>
</div>
