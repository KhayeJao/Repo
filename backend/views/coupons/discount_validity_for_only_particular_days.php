<?php

use yii\helpers\Json;
use yii\helpers\Html;
use kartik\datecontrol\DateControl;

$coupon_details = common\models\base\Coupons::findOne($coupon_id);
if ($coupon_details) {
    $coupon_perameter = Json::decode($coupon_details->coupon_perameter);
}
?>
<div class="row">
    <div class="form-group field-coupons-code required">
        <label class="control-label col-sm-3" for="from_date">From Date</label>
        <div class="col-sm-6">
            <?php /* ?><div style="display: none;">
                <?php
                echo DateControl::widget([
                    'name' => 'DFSI3453859',
                    'value' => '',
                    'type' => DateControl::FORMAT_DATE,
                    'displayFormat' => 'php:D, d-M-Y',
                    'saveFormat' => 'php:Y-m-d',
                    'options' => [
                        'pluginOptions' => [
                            'autoclose' => true
                        ]
                    ]
                ]);
                ?>
            </div>
            <?php*/
            echo DateControl::widget([
                'name' => 'discount_perameters[from_date]',
                'value' => ($coupon_details ? $coupon_perameter['from_date'] : time()),
                'type' => DateControl::FORMAT_DATE,
                'displayFormat' => 'php:D, d-M-Y',
                'saveFormat' => 'php:Y-m-d',
                'options' => [
                    'pluginOptions' => [
                        'autoclose' => true
                    ]
                ]
            ]);
//            echo DateControl::widget([
//                'name' => 'discount_perameters[from_date]',
//                'value' => time(),
//                'type' => DateControl::FORMAT_DATE,
//                'autoWidget' => false,
//                'options' => [
//                    'class' => 'form-control', 'required' => 'required', 'id' => 'from_date',
//                    'pluginOptions' => [
//                        'autoclose' => true
//                    ]
//                ]
//            ]);
//            echo Html::input('date', 'discount_perameters[from_date]', ($coupon_details ? $coupon_perameter['from_date'] : ""), ['class' => 'form-control', 'required' => 'required', 'id' => 'from_date']);
            ?>
            <div class="help-block help-block-error ">Discount applies from</div>
        </div>
    </div>
    <div class="form-group field-coupons-code required">
        <label class="control-label col-sm-3" for="to_date">Until Date</label>
        <div class="col-sm-6">
            <?php
//            echo DateControl::widget([
//                'name' => 'discount_perameters[to_date]',
//                'value' => time(),
//                'type' => DateControl::FORMAT_DATE,
//                'autoWidget' => false,
//                'options' => [
//                    'class' => 'form-control', 'required' => 'required', 'id' => 'to_date',
//                    'pluginOptions' => [
//                        'autoclose' => true
//                    ]
//                ]
//            ]);
            echo DateControl::widget([
                'name' => 'discount_perameters[to_date]',
                'value' => ($coupon_details ? $coupon_perameter['to_date'] : time()),
                'type' => DateControl::FORMAT_DATE,
                'displayFormat' => 'php:D, d-M-Y',
                'saveFormat' => 'php:Y-m-d',
                'options' => [
                    'pluginOptions' => [
                        'autoclose' => true
                    ]
                ]
            ]);
            
//            echo Html::input('date', 'discount_perameters[to_date]', ($coupon_details ? $coupon_perameter['to_date'] : ""), ['class' => 'form-control', 'required' => 'required', 'id' => 'to_date']);
            ?>
            <div class="help-block help-block-error ">Discount applies until</div>
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
        <label class="control-label col-sm-3" for="discount_type">Discount Type</label>
        <div class="col-sm-6">
            <?php
            echo Html::dropDownList('discount_perameters[discount_type]', ($coupon_details ? $coupon_perameter['discount_type'] : 'Percentage'), ['Percentage' => 'Percentage', 'Rs' => 'Rs'], ['class' => 'form-control', 'required' => 'required', 'id' => 'discount_type'])
            ?>
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
