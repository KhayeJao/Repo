<?php

use common\models\base\Dish;
use common\models\base\DishTopping;
use common\models\base\Combo;
use yii\helpers\Html;

$boolVar = TRUE;
$subtotal = 0;
$grand_total = 0;
if (!isset($_SESSION['cart']['delivery_charge'])) {
    $_SESSION['cart']['delivery_charge'] = 0;
}
//echo '<pre>';print_r($_SESSION['cart']); echo '</pre>'; exit;
?>
<div class="dataTables_wrapper form-inline no-footer">
    <table class="table table-hover table-condensed dataTable no-footer" id="condensedTable" role="grid">
        <tbody>
            <?php
            if (!(empty($_SESSION['cart']['dishes']) && empty($_SESSION['cart']['combos']))) {
                if (!empty($_SESSION['cart']['dishes'])) {
                    foreach ($_SESSION['cart']['dishes'] as $dishesKey => $dishesValue) {
                        $dish_model = Dish::findOne(['id' => $dishesValue['id']]);
                        $subtotal += $dishesValue['price'] * $dishesValue['qty'];
                        ?>
                        <tr role="row" class="<?= ($boolVar ? 'odd' : 'even') ?>">
                            <td class="v-align-middle semi-bold sorting_1" style="width: 15%">
                                <?= $dishesValue['qty'] ?>
                                <?php if (!isset($dishesValue['toppings'])) { ?>
                                    <a href="javascript:void(0);" class="add_to_cart_dish" data-dish_id="<?= $dishesValue['id'] ?>"><span class="fa fa-plus"></span></a>
                                    <a href="javascript:void(0);" class="remove_from_cart_dish" data-dish_id="<?= $dishesValue['id'] ?>"><span class="fa fa-minus"></span></a>
                                <?php } ?>
                            </td>
                            <td class="v-align-middle" style="width: 66%"><?= $dish_model->title ?>
                                <a href="javascript:void(0);" key='<?= $dishesKey ?>' class="pull-right add_comment_a"><span class="fa fa-comment"></span></a>
                                <br/><span id="comment_span_<?= $dishesKey ?>">
                                    <?php if (trim($dishesValue['comment'])) { ?>
                                        <span class="fa fa-comment-o"></span>
                                    <?php } ?>
                                    <?= $dishesValue['comment'] ?></span>
                                <span id="comment_input_span_<?= $dishesKey ?>" style="display: none" kay='<?= $dishesKey ?>'><?= Html::textarea('comment_ta_' . $dishesKey, $dishesValue['comment'], ['data_dish_id' => $dishesValue['id'], 'class' => 'dish_comment_ta', 'key' => $dishesKey, 'id' => 'dish_comment_ta' . $dishesKey]) ?></span>
                            </td>
                            <td class="v-align-middle semi-bold"><?= $dishesValue['price'] * $dishesValue['qty'] ?> Rs.</td>
                        </tr>
                        <?php
                        if (isset($dishesValue['toppings'])) {
                            foreach ($dishesValue['toppings'] as $dishToppingKey => $dishToppingValue) {
                                $dish_topping_model = DishTopping::findOne(['id' => $dishToppingValue['dish_topping_id']]);
                                $subtotal += $dishToppingValue['price'];
                                ?>
                                <tr role="row" class="sub-tr <?= ($boolVar ? 'odd' : 'even') ?>">
                                    <td class="v-align-middle semi-bold sorting_1"></td>
                                    <td class="v-align-middle"><?= $dish_topping_model->topping->title ?></td>
                                    <td class="v-align-middle semi-bold"><?= ($dishToppingValue['price'] ? $dishToppingValue['price'] . ' Rs.' : 'Free') ?></td>
                                </tr>
                                <?php
                            }
                        } $boolVar = !$boolVar;
                    }
                } if (!empty($_SESSION['cart']['combos'])) {
                    foreach ($_SESSION['cart']['combos'] as $combosKey => $combosValue) {
                        $combo_model = Combo::findOne(['id' => $combosValue['id']]);
                        $subtotal += $combo_model['price'] * $combosValue['qty'];
                        ?>
                        <tr role="row" class="<?= ($boolVar ? 'odd' : 'even') ?>">
                            <td class="v-align-middle semi-bold sorting_1">
                                <?= $combosValue['qty'] ?>
                                <a href="javascript:void(0);" class="add_to_cart_combo" data-combo_id="<?= $combosValue['id'] ?>"><span class="fa fa-plus"></span></a>
                                <a href="javascript:void(0);" class="remove_from_cart_combo" data-combo_id="<?= $combosValue['id'] ?>"><span class="fa fa-minus"></span></a>
                            </td>
                            <td class="v-align-middle"><?= $combo_model->title ?></td>
                            <td class="v-align-middle semi-bold"><?= $combosValue['price'] * $combosValue['qty'] ?> Rs.</td>
                        </tr>
                        <?php
                        $boolVar = !$boolVar;
                    }
                }
            } else {
                ?>
            <h4>Please put something in the cart</h4><?php } ?>
        </tbody>
    </table>
    <div id="chharges">
        <div class="col-md-12">
            <p class="pull-left">Sub Total</p>
            <p class="pull-right" id="subtotal_p"><?= $subtotal; ?> Rs.</p>
        </div>
        <?php
        $discounted_subtotal = $subtotal;
        $_SESSION['cart']['subtotal'] = $subtotal;
        if (isset($_SESSION['cart']['coupon_data'])) {
            \Yii::$app->runAction('coupons/applycoupon');
            //CALCULATE NEW SUBTOAL AS DISCOUNT IS APPLIED
            $discounted_subtotal = $subtotal - $_SESSION['cart']['coupon_data']['discount_amount'];
            ?>
            <div class="col-md-12">
                <p class="pull-left">Discount</p>
                <p class="pull-right" id="tax_p"><?= $_SESSION['cart']['coupon_data']['discount_amount']; ?> Rs.</p>
            </div>
            <?php
        }$tax_amount = ($discounted_subtotal * $restaurant_model->tax / 100);
        $grand_total = $discounted_subtotal + $tax_amount;
        ?>
        <div class="col-md-12">
            <p class="pull-left">Tax</p>
            <p class="pull-right" id="tax_p"><?= $tax_amount; ?> Rs.</p>
        </div>
        <?php
        $vat_amount = ($discounted_subtotal * $restaurant_model->vat / 100);
        $grand_total = $grand_total + $vat_amount;
        ?>
        <div class="col-md-12">
            <p class="pull-left">Vat</p>
            <p class="pull-right" id="vat_p"><?= $vat_amount; ?> Rs.</p>
        </div>
        <?php
        if ($restaurant_model->scharge_type == 'Percentage') {
            $service_charge_amount = ($discounted_subtotal * $restaurant_model->service_charge / 100);
        } else if ($restaurant_model->scharge_type == 'Fixed Amount') {
            $service_charge_amount = $restaurant_model->service_charge;
        }
        $service_charge_amount += $_SESSION['cart']['delivery_charge'];
        $grand_total = $grand_total + $service_charge_amount;
        ?>
        <div class="col-md-12">
            <p class="pull-left">Service Charge + Delivery Charge (If applicable)</p>
            <p class="pull-right" id="service_charge_p"><?= $service_charge_amount ?> Rs.</p>
        </div>
        <div class="col-md-12 bg-master-light">
            <p class="pull-left">Total</p>
            <p class="pull-right" id="total_p"><?= $grand_total; ?> Rs.</p>
        </div>
        <div class="col-md-12 m-t-10">
            <button type="button" id="checkout_button" <?= ($discounted_subtotal < $restaurant_model->min_amount ? 'disabled' : '') ?> class="btn btn-lg btn-success pull-right">Checkout<?= ($subtotal < $restaurant_model->min_amount ? '<br> Min. order value ' . $restaurant_model->min_amount . ' Rs.' : '') ?></button>
        </div>
    </div>
</div>
<?php
$_SESSION['cart']['discounted_subtotal'] = $discounted_subtotal;
$_SESSION['cart']['tax'] = $tax_amount;
$_SESSION['cart']['vat'] = $vat_amount;
$_SESSION['cart']['service_charge'] = $service_charge_amount;
$_SESSION['cart']['grand_total'] = $grand_total;
?>