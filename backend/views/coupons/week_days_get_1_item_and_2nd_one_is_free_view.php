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
        <label class="control-label col-sm-3" for="paid_dish_id">Paid Dish</label>
        <div class="col-sm-6">
            <?php
            echo Select2::widget([
                'name' => 'discount_perameters[paid_dish_id]',
                'id' => 'paid_dish_id',
                'data' => ArrayHelper::map(common\models\base\Dish::findAll(['restaurant_id' => $restaurant_id, 'is_deleted' => 'No', 'status' => 'Active']), 'id', 'title'),
                'value' => ($coupon_details ? $coupon_perameter['paid_dish_id'] : ''),
                'options' => [
                    'placeholder' => 'Select Dish...', 'required' => 'required',
                ],
            ]);
            ?>
            <div class="help-block help-block-error ">Select dish which user have to buy</div>
        </div>
    </div>

    <div class="form-group field-coupons-code required">
        <label class="control-label col-sm-3" for="free_dish_id">Free Dish</label>
        <div class="col-sm-6">
            <?php
            echo Select2::widget([
                'name' => 'discount_perameters[free_dish_id]',
                'id' => 'free_dish_id',
                'data' => ArrayHelper::map(common\models\base\Dish::findAll(['restaurant_id' => $restaurant_id, 'is_deleted' => 'No', 'status' => 'Active']), 'id', 'title'),
                'value' => ($coupon_details ? $coupon_perameter['free_dish_id'] : ''),
                'options' => [
                    'placeholder' => 'Select Dish...', 'required' => 'required',
                ],
            ]);
            ?>
            <div class="help-block help-block-error ">Select dish which user will get for free</div>
        </div>
    </div>

</div>