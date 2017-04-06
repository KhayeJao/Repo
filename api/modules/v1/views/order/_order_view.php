<?php
use common\models\base\OrderPayments;
use common\models\base\OrderTopping; ?>
<div class="col-md-12">
    <div class="container">
        <div class="row">
            <div class="col-xs-6 text-right pull-right">
                <h1><small>Order #<?= $model->order_unique_id ?></small></h1>
                <h4><small>Delivery Time : <?= Yii::$app->formatter->asDatetime($model->delivery_time); ?></small></h4>
                <h4><small><?= $model->delivery_type . " - " . $model->order_items ?> Item(s)</small></h4>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-5">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Customer Name : <?= $model->user_full_name ?></h4>
                    </div>
                    <div class="panel-body">
                        <p>
                            Delivery Address : <?= $model->address_line_1 . ($model->address_line_2 ? ", " . $model->address_line_2 : '') . ", " . $model->area0->area_name . ", " . $model->city . ", " . $model->pincode ?><br>
                            Mobile : <?= $model->mobile ?><br>
                            Email : <?= $model->email ?> <br>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xs-5 col-xs-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Restaurant: <?= $model->restaurant->title ?></h4>
                    </div>
                    <div class="panel-body">
                        <p>
                            Address : <?= $model->restaurant->address . ", " . $model->restaurant->area . ", " . $model->restaurant->city ?><br>
                            Open :  <?= $model->restaurant->open_datetime_1 . " - " . $model->restaurant->close_datetime_1 . " AND " . $model->restaurant->open_datetime_2 . " - " . $model->restaurant->close_datetime_2 ?><br>
                        </p>
                    </div>
                </div>

            </div>
        </div>
        <!-- / end client details section -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>
            <h4>Dish/Combo</h4>
            </th>
            <th>
            <h4>Description/Comment</h4>
            </th>
            <th>
            <h4>Qty</h4>
            </th>
            <th>
            <h4>Price</h4>
            </th>
            <th>
            <h4>Sub Total</h4>
            </th>
            </tr>
            </thead>
            <tbody>
                <?php foreach ($model->orderDishes as $dishKey => $dishValue) { ?>
                    <tr>
                        <td><?= $dishValue->dish_title ?></td>
                        <td><?= ($dishValue->comment ? $dishValue->comment : '-') ?></td>
                        <td class="text-right"><?= $dishValue->dish_qty ?></td>
                        <td class="text-right"><span class="rupyaINR">Rs</span> <?= $dishValue->dish_price ?></td>
                        <td class="text-right"><span class="rupyaINR">Rs</span> <?= $dishValue->dish_price * $dishValue->dish_qty ?></td>
                    </tr>
                    <?php
                    $order_toppings = OrderTopping::findAll(['dish_id' => $dishValue->dish_id, 'order_id' => $model->id]);
                    foreach ($order_toppings as $orderToppingKey => $orderToppingValue) {
                        ?>
                        <tr>
                            <td><?= $dishValue->dish_title . " - " . $orderToppingValue->topping->title ?></td>
                            <td>-</td>
                            <td class="text-right">-</td>
                            <td class="text-right">
                                <?php if ($orderToppingValue->price) { ?>
                                    <span class="rupyaINR">Rs</span> <?= $orderToppingValue->price ?>
                                <?php } else { ?>
                                    <i>Free</i>
                                <?php } ?>
                            </td>
                            <td class="text-right">
                                <?php if ($orderToppingValue->price) { ?>
                                    <span class="rupyaINR">Rs</span> <?= $orderToppingValue->price ?>
                                <?php } else { ?>
                                    <i>Free</i>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php }
                    ?>
                <?php } ?>

                <?php foreach ($model->orderCombos as $comboKey => $comboValue) { ?>
                    <tr>
                        <td><?= $comboValue->combo->title ?></td>
                        <td>
                            <?php
                            $combo_dishes_arr = array();
                            foreach ($comboValue->orderComboDishes as $comboDishesKey => $comboDishesValue) {
                                array_push($combo_dishes_arr, ($comboDishesValue->dish_qry > 1 ? $comboDishesValue->dish_qry : '') . " " . $comboDishesValue->dish->title);
                            }
                            echo implode(', ', $combo_dishes_arr);
                            ?>
                        </td>
                        <td class="text-right"><?= $comboValue->combo_qty ?></td>
                        <td class="text-right"><span class="rupyaINR">Rs</span> <?= $comboValue->price ?></td>
                        <td class="text-right"><span class="rupyaINR">Rs</span> <?= $comboValue->price * $comboValue->combo_qty ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <div class="row">
            <div class="col-xs-5">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h4>Payment details</h4>
                        <hr>
                    </div>
                    <div class="panel-body">
                        <p>Payment Mode : <?= ($model->payment_mode == 'COD' ? 'Cash On Delivery' : 'Through '.$model->payment_mode) ?></p>
                        <p>Order Date-Time : <?= Yii::$app->formatter->asDatetime($model->booking_time) ?></p>
                        <?php
                        if($model->payment_mode != "COD"){
                            $order_payment_model = OrderPayments::findOne(['order_id' => $model->id]); ?>
                            <p>Payment Details : <br/><?= $order_payment_model->payment_info ?></p>
                            <p>Payment Date-time : <?= Yii::$app->formatter->asDatetime($order_payment_model->payment_datetime) ?></p>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-xs-4 pull-right table-responsive" >
                <table class="table">
                    <tr>
                        <th>
                            Sub Total : 
                        </th>
                        <td class="text-right">
                            <span class="rupyaINR">Rs</span> <?= $model->sub_total ?>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            DISCOUNT : 
                        </th>
                        <td class="text-right">
                            <?php if ($model->coupon_code) { ?><span class="rupyaINR">Rs</span> <?= $model->discount_amount ?><?= ($model->discount_text ? "( ".$model->discount_text.")" : "") ?><?php } else { ?> N/A <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            TAX : 
                        </th>
                        <td class="text-right">
                            <span class="rupyaINR">Rs</span> <?= $model->tax ?> (<?= $model->tax_text ?>)
                        </td>
                    </tr>
                    <tr>
                        <th>
                            VAT : 
                        </th>
                        <td class="text-right">
                            <span class="rupyaINR">Rs</span> <?= $model->vat ?> (<?= $model->vat_text ?>)
                        </td>
                    </tr>
                    <tr>
                        <th>
                            SERVICE CHARGE : 
                        </th>
                        <td class="text-right">
                            <span class="rupyaINR">Rs</span> <?= $model->service_charge ?> <?= ($model->service_charge_text ? "( ".$model->service_charge_text.")" : "") ?>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Total : 
                        </th>
                        <td class="text-right">
                            <b><span class="rupyaINR">Rs</span> <?= $model->grand_total ?></b>
                        </td>
                    </tr>
                </table>
                
            </div>
            
        </div>
        
    </div>

    <?php
    foreach ($model->orderDishes as $dishKey => $dishValue) {
        
    }
    ?>
</div>