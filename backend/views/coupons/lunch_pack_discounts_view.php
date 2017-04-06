<?php

use yii\helpers\Json;
use yii\helpers\Html;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;

$coupon_details = common\models\base\Coupons::findOne($coupon_id);
if ($coupon_details) {
    $coupon_perameter = Json::decode($coupon_details->coupon_perameter);
}
?>
<div class="row">
    <div class="form-group field-coupons-code required">
        <label class="control-label col-sm-3" for="combo_id">Lunch Pack</label>
        <div class="col-sm-6">

            <?php
            echo Select2::widget([
                'name' => 'discount_perameters[combo_id]',
                'id' => 'combo_id',
                'data' => ArrayHelper::map(\common\models\base\Combo::findAll(['restaurant_id' => $restaurant_id, 'combo_type' => 'Lunch Special']), 'id', 'title'),
                'value' => ($coupon_details ? $coupon_perameter['combo_id'] : ''),
                'options' => [
                    'placeholder' => 'Select Lunch Pack...', 'required' => 'required',
                    'multiple' => true
                ],
            ]);
            ?>
            <div class="help-block help-block-error ">Select a user to whom you want to allow to use this coupon</div>
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
        <label class="control-label col-sm-3" for="discount_value">Discount</label>
        <div class="col-sm-6">
            <?php
            echo Html::input('number', 'discount_perameters[discount_value]', ($coupon_details ? $coupon_perameter['discount_value'] : 10), ['class' => 'form-control', 'required' => 'required', 'id' => 'discount_value']);
            ?>
        </div>
    </div>
</div>
