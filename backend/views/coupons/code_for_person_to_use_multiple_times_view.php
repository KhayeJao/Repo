<?php

use yii\helpers\Json;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;

$coupon_details = common\models\base\Coupons::findOne($coupon_id);
if ($coupon_details) {
    $coupon_perameter = Json::decode($coupon_details->coupon_perameter);
}
?>
<div class="row">

    <?php
//    echo Html::hiddenInput('discount_perameters[user_id]', '', ['id' => 'user_id','required' => 'required']);
    ?>

    <div class="form-group field-coupons-code required">
        <label class="control-label col-sm-3" for="user_id">User</label>
        <div class="col-sm-6">

            <?php
            echo Select2::widget([
                'name' => 'discount_perameters[user_id]',
                'id' => 'user_id',
                'data' => ArrayHelper::map(\common\models\base\User::findAll(['type' => 'customer']), 'id', 'username'),
                'value' => ($coupon_details ? $coupon_perameter['user_id'] : ''),
                'options' => [
                    'placeholder' => 'Select User...', 'required' => 'required',
                ],
            ]);
            ?>
            <div class="help-block help-block-error ">Select a user to whom you want to allow to use this coupon</div>
        </div>
    </div>

    <div class="form-group field-coupons-code required">
        <label class="control-label col-sm-3" for="discount_value">Discount</label>
        <div class="col-sm-6">
            <?php
            echo Html::input('number', 'discount_perameters[discount_value]', ($coupon_details ? $coupon_perameter['discount_value'] : 10), ['class' => 'form-control', 'required' => 'required', 'id' => 'discount_value']);
            ?>
            <div class="help-block help-block-error ">Discount in %</div>
        </div>
    </div>
    
    <div class="form-group field-coupons-code required">
        <label class="control-label col-sm-3" for="max_no_of_usage">Max. No of usage</label>
        <div class="col-sm-6">
            <?php
            echo Html::input('number', 'discount_perameters[max_no_of_usage]', ($coupon_details ? $coupon_perameter['max_no_of_usage'] : 4), ['class' => 'form-control', 'required' => 'required', 'id' => 'max_no_of_usage']);
            ?>
            <div class="help-block help-block-error ">Total no of times allow user to use this code</div>
        </div>
    </div>
    
    <div class="form-group field-coupons-code required">
        <label class="control-label col-sm-3" for="no_of_usage">No of usage</label>
        <div class="col-sm-6">
            <?php
            echo Html::input('number', 'discount_perameters[no_of_usage]', ($coupon_details ? $coupon_perameter['no_of_usage'] : 0), ['class' => 'form-control', 'required' => 'required', 'id' => 'no_of_usage']);
            ?>
            <div class="help-block help-block-error ">No of times user has used this code already</div>
        </div>
    </div>

</div>