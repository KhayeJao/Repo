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
        <label class="control-label col-sm-3" for="paid_dish_id">Paid Dish</label>
        <div class="col-sm-6">
            <?php
            echo Select2::widget([
                'name' => 'discount_perameters[week_days]',
                'id' => 'paid_dish_id',
                'data' => ['Monday' => 'Monday', 'Tuesday' => 'Tuesday', 'Wednesday' => 'Wednesday', 'Thursday' => 'Thursday', 'Friday' => 'Friday', 'Saturday' => 'Saturday', 'Sunday' => 'Sunday'],
                'value' => ($coupon_details ? $coupon_perameter['week_days'] : ''),
                'options' => [
                    'placeholder' => 'Select Week Days...', 'required' => 'required',
                    'multiple' => true,
                ],
            ]);
            ?>
            <div class="help-block help-block-error ">Select week days on which you want to give discount</div>
        </div>
    </div>
    <div class="form-group field-coupons-code required">
        <label class="control-label col-sm-3" for="menu_id">Menu</label>
        <div class="col-sm-6">
            <?php
            echo Html::dropDownList('discount_perameters[menu_id]', ($coupon_details ? $coupon_perameter['menu_id'] : ''), ArrayHelper::map(common\models\base\Menu::findAll(['restaurant_id' => $restaurant_id]), 'id', 'title'), ['class' => 'form-control', 'required' => 'required', 'id' => 'menu_id']);
            ?>
            <div class="help-block help-block-error ">Select menu to apply discount</div>
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
    <div class="form-group field-coupons-code required">
        <label class="control-label col-sm-3" for="discount_percentage">Discount</label>
        <div class="col-sm-6">
            <?php
            echo Html::input('number', 'discount_perameters[discount_percentage]', ($coupon_details ? $coupon_perameter['discount_percentage'] : 10), ['class' => 'form-control', 'required' => 'required', 'id' => 'discount_percentage']);
            ?>
        </div>
    </div>
    <div class="form-group field-coupons-code required">
        <label class="control-label col-sm-3" for="maximum_discount">Maximum Discount</label>
        <div class="col-sm-6">
            <?php
            echo Html::input('number', 'discount_perameters[maximum_discount]', ($coupon_details ? $coupon_perameter['maximum_discount'] : 500), ['class' => 'form-control', 'required' => 'required', 'id' => 'maximum_discount']);
            ?>
            <div class="help-block help-block-error ">Maximum discount in Rs</div>
        </div>
    </div>
</div>