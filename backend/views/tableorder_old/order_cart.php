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
	
	<div class="table-responsive" >
		
    <table class="table table-hover table-condensed dataTable no-footer" id="condensedTable" role="grid">
        <tbody>
			 
            <?php
            if (!(empty($_SESSION['cart']['dishes']) && empty($_SESSION['cart']['combos']))) {
                if (!empty($_SESSION['cart']['dishes'])) {
                    foreach ($_SESSION['cart']['dishes'] as $dishesKey => $dishesValue) {
                        $dish_model = Dish::findOne(['id' => $dishesValue['id']]);
                        $subtotal += $dishesValue['price'] * $dishesValue['qty'];
                        ?>
                        <tr role="row" class="<?= ($boolVar ? 'odd' : 'even') ?>" >
                            <td class="v-align-middle semi-bold sorting_1">
                                <div class="row">
									<div class="col-md-6 col-xs-6 col-sm-6 gutter">
                                <input name="item_number" class="item-number" value="<?= $dishesValue['qty'] ?>" data-dish_id="<?= $dishesValue['id'] ?>">
                                <?php if (!isset($dishesValue['toppings'])) { ?>
									
                                    <a href="javascript:void(0);" class="add_to_cart_dish  plus-button" data-dish_id="<?= $dishesValue['id'] ?>">+</a>
                                    <a href="javascript:void(0);" class="remove_from_cart_dish  minus-button" data-dish_id="<?= $dishesValue['id'] ?>">-</a>
                                <?php } ?>
                                </div>
                                </div>
                            </td>
                            <td class="v-align-middle" style="width: 58%"><?= $dish_model->title ?>
                                <a href="javascript:void(0);" key='<?= $dishesKey ?>' class="pull-right add_comment_a"><span class="fa fa-comment"></span></a>
                                <br/><span id="comment_span_<?= $dishesKey ?>">
                                    <?php if (trim($dishesValue['comment'])) { ?>
                                        <span class="fa fa-comment-o"></span>
                                    <?php } ?>
                                    <?= $dishesValue['comment'] ?></span>
                                <span id="comment_input_span_<?= $dishesKey ?>" style="display: none" kay='<?= $dishesKey ?>'><?= Html::textarea('comment_ta_' . $dishesKey, $dishesValue['comment'], ['data_dish_id' => $dishesValue['id'], 'class' => 'dish_comment_ta', 'key' => $dishesKey, 'id' => 'dish_comment_ta' . $dishesKey]) ?></span>
                            </td>
                            <td class="v-align-middle semi-bold" align="right"> <i class="fa fa-inr"></i> <?= $dishesValue['price'] * $dishesValue['qty'] ?> </td>
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
                                <a href="javascript:void(0);" class="add_to_cart_combo plus-button" data-combo_id="<?= $combosValue['id'] ?>">+</a>
                                <a href="javascript:void(0);" class="remove_from_cart_combo minus-button" data-combo_id="<?= $combosValue['id'] ?>">-</span></a>
                            </td>
                            <td class="v-align-middle"><?= $combo_model->title ?></td>
                            <td class="v-align-middle semi-bold"><i class="fa fa-inr"></i> <?= $combosValue['price'] * $combosValue['qty'] ?></td>
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
    
    </div>
    
    
    <div id="chharges">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <p class="pull-left">Sub Total</p>
            <p class="pull-right" id="subtotal_p"> <i class="fa fa-inr"></i> <?= $subtotal; ?> </p>
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
                <p class="pull-right" id="tax_p">  <i class="fa fa-inr"></i> <?= $_SESSION['cart']['coupon_data']['discount_amount']; ?> </p>
            </div>
            <?php
        }$tax_amount = ($discounted_subtotal * $restaurant_model->tax / 100);
        $grand_total = $discounted_subtotal + $tax_amount;
        ?>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <p class="pull-left">Tax</p>
            <p class="pull-right" id="tax_p"><i class="fa fa-inr"></i> <?= $tax_amount; ?> </p>
        </div>
        <?php
        $vat_amount = ($discounted_subtotal * $restaurant_model->vat / 100);
        $grand_total = $grand_total + $vat_amount;
        ?>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <p class="pull-left">Vat</p>
            <p class="pull-right" id="vat_p"> <i class="fa fa-inr"></i> <?= $vat_amount; ?> </p>
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
			<div class="col-md-12 col-sm-12 col-xs-12">
				<p class="pull-left">Service Charge + Delivery Charge (If applicable)</p>
				<p class="pull-right" id="service_charge_p"><i class="fa fa-inr"></i> <?= $service_charge_amount ?></p>
			</div>
			<div class="col-md-12 bg-master-light col-sm-12 col-xs-12">
				<p class="pull-left">Total</p>
				<p class="pull-right" id="total_p"> <i class="fa fa-inr"></i> <?= $grand_total; ?></p>
			</div>
        </div>
    
			
				 <div class="col-md-12 bg-master-light col-sm-12 col-xs-12"> 
					<p  id="save_order">&nbsp;  </p>
				</div> 
			 	<div class="button-sav">
				<ul>
                <li><button type="button" id="save_button"   class="btn btn-sm btn-danger ">Save </button></li>
                <li>
                <?php if($subtotal): ?> 
				  <?php if($discounted_subtotal > $restaurant_model->min_amount){ ?>
				 <button type="button" id="kitchen_print"  class="btn btn-sm btn-danger">Kitchen Print</button>  
				 <?php }  ?>
				 <button type="button" id="finale_print"  class="btn btn-sm btn-danger">Final Print</button> 
			   
				<?php endif?>
                </li>
                <li>
                <button type="button" id="checkout_button" <?= ($discounted_subtotal < $restaurant_model->min_amount ? 'disabled' : '') ?> class="btn btn-sm btn-danger ">Checkout <?= ($subtotal < $restaurant_model->min_amount ? '(Min. order value ' . $restaurant_model->min_amount . ' Rs.)' : '') ?></button>
                </li>
                </ul>
				<!--
				 <div class="col-md-4 m-t-5 col-sm-4 col-xs-4">
					<button type="button" id="save_button"   class="btn btn-sm btn-danger ">Save </button>
				</div>
				<?php if($subtotal): ?>
				 <div class="col-md-4 m-t-5 col-sm-4 col-xs-4 text-center"> 
					 <?php if($discounted_subtotal > $restaurant_model->min_amount){ ?>
					<button type="button" id="kitchen_print"  class="btn btn-sm btn-danger">Kitchen Print</button>  
					<?php }  ?>
					<button type="button" id="finale_print"  class="btn btn-sm btn-danger">Final Print</button> 
				</div>
				<?php endif?>
				<div class="col-md-4 m-t-5 col-sm-4 col-xs-4 text-center">
					<button type="button" id="checkout_button" <?= ($discounted_subtotal < $restaurant_model->min_amount ? 'disabled' : '') ?> class="btn btn-sm btn-danger pull-right newn">Checkout<?= ($subtotal < $restaurant_model->min_amount ? '<br> Min. order value ' . $restaurant_model->min_amount . ' Rs.)' : '') ?></button>
				</div>
				
				-->
				
        
    </div>
</div>

<!-- print start for print kichen -->
<div id="print_ID" class="for-print table-responsive">
	<table width="500">
		<?php $restaurant_model = \common\models\base\Restaurant::findOne(['id' => $_SESSION['cart']['restaurant_id']]); ?>
		<h2> <?= $restaurant_model->title?></h2>
		<h3>Table No: <?= $_SESSION['cart']['table_no']?></h3>
		  <tr>
			<td><strong>Qty</strong></td> 
			<td><strong>Product Name</strong></td> 
		  </tr>
		   <?php
					if (!(empty($_SESSION['cart']['dishes']) && empty($_SESSION['cart']['combos']))) {
						if (!empty($_SESSION['cart']['dishes'])) {
							foreach ($_SESSION['cart']['dishes'] as $dishesKey => $dishesValue) {
								$dish_model = Dish::findOne(['id' => $dishesValue['id']]);
								//$subtotal += $dishesValue['price'] * $dishesValue['qty'];
								?>
								<tr role="row" class="<?= ($boolVar ? 'odd' : 'even') ?>">
									<td class="v-align-middle semi-bold sorting_1" style="width: 15%">
										<?= $dishesValue['qty'] ?>
										<?php if (!isset($dishesValue['toppings'])) { ?>
											<a href="javascript:void(0);" class="add_to_cart_dish" data-dish_id="<?= $dishesValue['id'] ?>"><span class="fa fa-plus"></span></a>
											<a href="javascript:void(0);" class="remove_from_cart_dish" data-dish_id="<?= $dishesValue['id'] ?>"><span class="fa fa-minus"></span></a>
										<?php } ?>
									</td>
									<td class="v-align-middle" style="width: 55%"><?= $dish_model->title ?> 
									</td> 
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
											 
										</tr>
										<?php
									}
								} $boolVar = !$boolVar;
							}
						}
						 if (!empty($_SESSION['cart']['combos'])) {
							foreach ($_SESSION['cart']['combos'] as $combosKey => $combosValue) {
								$combo_model = Combo::findOne(['id' => $combosValue['id']]);
								//$subtotal += $combo_model['price'] * $combosValue['qty'];
								?>
								<tr role="row" class="<?= ($boolVar ? 'odd' : 'even') ?>">
									<td class="v-align-middle semi-bold sorting_1">
										<?= $combosValue['qty'] ?>
										<a href="javascript:void(0);" class="add_to_cart_combo" data-combo_id="<?= $combosValue['id'] ?>"><span class="fa fa-plus"></span></a>
										<a href="javascript:void(0);" class="remove_from_cart_combo" data-combo_id="<?= $combosValue['id'] ?>"><span class="fa fa-minus"></span></a>
									</td>
									<td class="v-align-middle"><?= $combo_model->title ?></td>
									 
								</tr>
								<?php
								$boolVar = !$boolVar;
							}
						}
					} else {
						?>
					<h4>Please put something in the cart</h4><?php } ?>
				
	</table>

	 
</div>
<!-- end -->


<!-- print start final print -->
<div id="print_IDs" class="for-print table-responsive">
	<?php 
	header('Content-Type: image/jpeg,png');
	?>
	<table width="500">
		<?php $restaurant_model = \common\models\base\Restaurant::findOne(['id' => $_SESSION['cart']['restaurant_id']]); ?>
		<h2> <?= $restaurant_model->title?></h2>
		<h3>Table No: <?= $_SESSION['cart']['table_no']?></h3>
		  <tr>
			<td><strong>Qty</strong></td>
			<td><strong>Product Name</strong></td>
			<td><strong>Price</strong></td>
		  </tr>
		   <?php
					if (!(empty($_SESSION['cart']['dishes']) && empty($_SESSION['cart']['combos']))) {
						if (!empty($_SESSION['cart']['dishes'])) {
							foreach ($_SESSION['cart']['dishes'] as $dishesKey => $dishesValue) {
								$dish_model = Dish::findOne(['id' => $dishesValue['id']]);
								//$subtotal += $dishesValue['price'] * $dishesValue['qty'];
								?>
								<tr role="row" class="<?= ($boolVar ? 'odd' : 'even') ?>">
									<td class="v-align-middle semi-bold sorting_1" style="width: 15%">
										<?= $dishesValue['qty'] ?>
										<?php if (!isset($dishesValue['toppings'])) { ?>
											<a href="javascript:void(0);" class="add_to_cart_dish" data-dish_id="<?= $dishesValue['id'] ?>"><span class="fa fa-plus"></span></a>
											<a href="javascript:void(0);" class="remove_from_cart_dish" data-dish_id="<?= $dishesValue['id'] ?>"><span class="fa fa-minus"></span></a>
										<?php } ?>
									</td>
									<td class="v-align-middle" style="width: 60%"><?= $dish_model->title ?>
										<a href="javascript:void(0);" key='<?= $dishesKey ?>' class="pull-right add_comment_a"><span class="fa fa-comment"></span></a>
										<br/><span id="comment_span_<?= $dishesKey ?>">
											<?php if (trim($dishesValue['comment'])) { ?>
												<span class="fa fa-comment-o"></span>
											<?php } ?>
											<?= $dishesValue['comment'] ?></span>
										<span id="comment_input_span_<?= $dishesKey ?>" style="display: none" kay='<?= $dishesKey ?>'><?= Html::textarea('comment_ta_' . $dishesKey, $dishesValue['comment'], ['data_dish_id' => $dishesValue['id'], 'class' => 'dish_comment_ta', 'key' => $dishesKey, 'id' => 'dish_comment_ta' . $dishesKey]) ?></span>
									</td>
									<td class="v-align-middle semi-bold">&#8377; <?= $dishesValue['price'] * $dishesValue['qty'] ?></td>
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
						}
						 if (!empty($_SESSION['cart']['combos'])) {
							foreach ($_SESSION['cart']['combos'] as $combosKey => $combosValue) {
								$combo_model = Combo::findOne(['id' => $combosValue['id']]);
								//$subtotal += $combo_model['price'] * $combosValue['qty'];
								?>
								<tr role="row" class="<?= ($boolVar ? 'odd' : 'even') ?>">
									<td class="v-align-middle semi-bold sorting_1">
										<?= $combosValue['qty'] ?>
										 
									</td>
									<td class="v-align-middle"><?= $combo_model->title ?></td>
									<td class="v-align-middle semi-bold">&#8377; <?= $combosValue['price'] * $combosValue['qty'] ?> </td>
								</tr>
								<?php
								$boolVar = !$boolVar;
							}
						}
					} else {
						?>
					<h4>Please put something in the cart</h4><?php } ?>
				
	</table>

	<table width="500">
		  <tr>
			<td><strong>Sub Total</strong></td>
			
			<td><strong> &#8377; <?= $subtotal; ?></strong></td>
		  </tr>
			 <?php
				$discounted_subtotal = $subtotal;
				$_SESSION['cart']['subtotal'] = $subtotal;
				if (isset($_SESSION['cart']['coupon_data'])) {
					\Yii::$app->runAction('coupons/applycoupon');
					//CALCULATE NEW SUBTOAL AS DISCOUNT IS APPLIED
					$discounted_subtotal = $subtotal - $_SESSION['cart']['coupon_data']['discount_amount'];
					?>
		   <tr>
			<td><strong> Discount </strong></td>
			
			<td><strong> &#8377; <?= $_SESSION['cart']['coupon_data']['discount_amount']; ?> </strong></td>
		  </tr>
		  
		  <?php }?>
		  
		  <tr>
			<td>Tax</td>
		  
			<td> &#8377; <?= $tax_amount; ?>  </td>
		  </tr>
		  <tr>
			<td>Vat</td>
			
			<td>&#8377; <?= $vat_amount; ?></td>
			
		  </tr>
		  <tr>
			<td>Service + Delivery </td>

			<td> &#8377; <?= $service_charge_amount ?></td>
		  </tr>
		  <tr>
			<td><strong>Total </strong></td>

			<td> <strong> &#8377;  <?= $grand_total; ?> </strong></td>
		   
		  </tr>
	</table>
</div>
<!-- end -->  


<?php
$_SESSION['cart']['discounted_subtotal'] = $discounted_subtotal;
$_SESSION['cart']['tax'] = $tax_amount;
$_SESSION['cart']['vat'] = $vat_amount;
$_SESSION['cart']['service_charge'] = $service_charge_amount;
$_SESSION['cart']['grand_total'] = $grand_total;
//echo '<pre>';print_r($_SESSION['cart']); echo '</pre>'; exit;
?>
