<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use dmstr\bootstrap\Tabs;
use yii\bootstrap\Modal;

use yii\bootstrap\ActiveForm; 
use demogorgorn\ajax\AjaxSubmitButton; 
 
/**
 * @var yii\web\View $this
 * @var common\models\Order $model takeorder/index
 */
$this->title = 'New Order';
$this->params['breadcrumbs'][] = ['label' => ' Dine-In', 'url' => ['takeorder/index']];
$this->params['breadcrumbs'][] = 'New Order';
?>
<div class="order-view"> 
    <div class="col-md-8">
        <h3 class="red-col"> Order Menu </h3>
        
        <div class="tab-pane fade in active" id="tab1default">  
			<input aria-haspopup="true" aria-autocomplete="list" role="textbox" autocomplete="off" name="menu" id="search_menu" class="form-input ui-autocomplete-input" placeholder="Search Menu" type="text">
				 <div class="amenu">
					<div class="col-md-3 no-padding col-sm-3"> 
						<nav class="nav-sidebar menu-tab-res"> 
							<ul class="nav tabs">
								<span id="search_menu_result"></span>
								<span id="search_menu_main">
								<?php 
								
	
								  $restaurant_menus = $restaurant_model->menus;
	
								foreach ($restaurant_menus as $restaurant_menus_key => $restaurant_menus_value) {  
	 
									?> 
									<li class="<?= ($restaurant_menus_key != 0 ? '' : 'active') ?>"><a href="#menu_tab_<?= $restaurant_menus_key ?>" data-toggle="tab"><?= $restaurant_menus_value->title ?></a></li>
	
									<?php
	
								}?>
								</span>
								<?php
	
								$restaurant_combos = $restaurant->combos; 
	
								if ($restaurant_combos) {
	
	
									?>
									<li><a href="#combo_tab" data-toggle="tab">Combos</a></li>
	
								<?php }
								?>
	
							</ul> 
						</nav> 
	
					</div>
					<!-- tab content -->
					<div class="tab-content col-md-9 no-padding-mobile col-sm-9 search-part">  
						<?php 
						
							 
						foreach ($restaurant_menus as $restaurant_menus_key => $restaurant_menus_value) { 
							 
							 ?>
	
			
	
							<div class="tab-pane <?= ($restaurant_menus_key != 0 ? '' : 'active') ?> text-style" id="menu_tab_<?= $restaurant_menus_key ?>">
	
				  
			
	
			
	
								<div class="col-md-12 no-padding-right1 no-padding-mobile">
	
			
	
									<div class="menu-right-side">
	
			
	
										<div class="menu-cat-img">
	
			
	
			
	
			
	
											<img src="<?= $restaurant_menus_value->imageUrl ?>" alt="" class="img-responsive lazy">
	
			
	
										</div>
	
			
	
										<div class="menu-cat-title"><?= $restaurant_menus_value->title ?></div>
	
			
	
									</div>  
	
			
	
									<?php foreach ($restaurant_menus_value->dishes as $dishesKey => $dishesValue) { ?>
	
			
	
										<div class="dish-wise-tab <?= ($dishesKey % 2 == 0 ? 'odd' : 'even') ?>">
	
			
	
														                         <!--<div class="col-md-2 col-sm-2 col-xs-2 ">
	
			
	
			
	

	
												<a href="javascript:void(0);" class="add_dish_to_fav my-fav" data-dish_id="<?= $dishesValue->id ?>">
	
			
	
													<span class="<?= (in_array($dishesValue->id, $user_fav_dishes) ? 'active' : '') ?>"><i class="fa fa-heart"></i></span>
	
			
	
													<div style="clear:both"></div>
	
			
	
													<p id="fav_count_<?= $dishesValue->id ?>"><?= count($dishesValue->favDishes) ?></p>
	
			
	
												</a>
	                                      
			
	
											</div>-->
	
			
	
			
	
			
	
											<div class="col-md-11 col-sm-10 col-xs-10 no-padding-right1 no-padding-mobile">
	
			
	
												<?php if ($dishesValue->toppingGroups) { ?>
	
			
	
													<a href="javascript:void(0);" data-model-id="model_<?= $dishesValue->id ?>" class="open_model dish-order">
	
			
	
														<div class="col-md-8 col-sm-7 col-xs-8 no-padding">
	
			
	
															<div class="dish-title"><?= $dishesValue->title ?></div>
	
			
	
															<div style="clear:both"></div> 
			
	
														</div>
	
			
	
														<div class="col-md-4 col-sm-5 col-xs-4 no-padding-right1">
	
			
	
															<p class="pull-right"><span><i class="fa fa-inr"></i></span><?= $dishesValue->price ?><span class="cart"><i class="right-arrow"></i></span></p>
	
			
	
														</div>
	
			
	
													</a>
	
			
	
													<?php
	
			
	
													Modal::begin([
	
			
	
														'header' => '<h2>Choose toppings for ' . $dishesValue->title . '</h2>',
	
			
	
														'id' => 'model_' . $dishesValue->id,
	
			
	
													]);
	
			
	
													?>
	
			
	
													<?php
	
			
	
													$dish_topping_id_ele_arr = array();
	
			
	
													foreach ($dishesValue->toppingGroups as $tgroupskey => $tgroupsvalue) {
	
			
	
														?>
	
			
	
														<div class="row padding-15 border-b">
	
			
	
															<h5><?= $tgroupsvalue->title; ?></h5>
	
			                                                	<!--	<div class="dish-contant"><?= $tgroupsvalue->description ?></div> -->
	
															<?php foreach ($tgroupsvalue->dishToppings as $dtKey => $dtValue) { ?>
	
			
	
																<div class="col-md-6 p-r-50">
	
			
	
																	<div class="row padding-15">
	
			
	
																		<?= Html::radio('topping_' . $dishesValue->id . '_' . $tgroupskey, (!$dtKey ? TRUE : FALSE), ['value' => $dtValue->id, 'id' => 'topping_' . $dishesValue->id . '_' . $tgroupskey]) ?>
	
			
	
																		<label for="<?= 'topping_' . $dishesValue->id . '_' . $tgroupskey ?>"><?= $dtValue->topping->title; ?></label>
	
			
	
																		<span class="pull-right"><?= ($dtValue->price > 0 ? '<i class="fa fa-inr"></i> ' . $dtValue->price : 'Free') ?></span>
	
			
	
																	</div>
	
			
	
																</div>
	
			
	
																<?php
	
			
	
															}
	
			
	
															array_push($dish_topping_id_ele_arr, 'topping_' . $dishesValue->id . '_' . $tgroupskey);
	
			
	
															?>
	
			
	
														</div>
	
			
	
													<?php } ?>
	
			
	
													<div class="row p-t-10">
	
			
	
														<?php echo Html::button('Add to cart', ['id' => 'select_topping_btn_' . $tgroupskey, 'class' => 'select_topping_btn btn btn-default pull-right', 'data-topping-id-ele' => implode('^_^', $dish_topping_id_ele_arr), 'data-dish_id' => $dishesValue->id]); ?>
	
			
	
													</div>
	
			
	
													<?php
	
			
	
													Modal::end();
	
			
	
												} else {
	
			
	
													?>
	
			
	
													<a href="javascript:void(0);" data-dish_id="<?= $dishesValue->id ?>" class="add_to_cart_dish dish-order">
	
			
	
														<div class="col-md-8 col-sm-7 col-xs-8 no-padding">
	
			
	
															<div class="dish-title"><?= $dishesValue->title ?></div>
	
			
	
															<div style="clear:both"></div>
	
			
	
														<!--	<div class="dish-contant"><?= $dishesValue->description ?></div> -->
	
			
	
														</div>
	
			
	
														<div class="col-md-4 col-sm-5 col-xs-4 no-padding-right1">
	
			
	
															<p class="pull-right"><span><i class="fa fa-inr"></i></span><?= $dishesValue->price ?><span class="cart"><i class="fa fa-shopping-cart"></i></span></p>
	
			
	
														</div>
	
			
	
													</a>
	
			
	
												<?php } ?>
	
			
	
											</div>
	
			
	
			
	
			
	
										</div>
	
			
	
									<?php } ?>
	
			
	
								</div>
	
			
	
							</div>
	
			
	
						<?php } ?>
	
			
	
			
	
			
	
						<?php if ($restaurant_combos) { ?>
	
			
	
							<div class="tab-pane text-style" id="combo_tab">
	
			
	
			
	
			
	
								<div class="col-md-12 no-padding-right no-padding-mobile">
	
			
	
									<?php foreach ($restaurant_combos as $combosKey => $combosValue) { ?>
	
			
	
										<div class="dish-wise-tab <?= ($combosKey % 2 == 0 ? 'odd' : 'even') ?>">
	
			
	
											<div class="col-md-12 col-sm-12 col-xs-12 no-padding-right no-padding-mobile">
	
			
	
												<a href="javascript:void(0);"  class="add_to_cart_combo dish-order" data-combo_id="<?= $combosValue->id ?>">
	
			
	
													<div class="col-md-8 col-sm-7 col-xs-8 no-padding">
	
			
	
			
	
			
	
														<div class="dish-title"><?= $combosValue->title ?></div>
	
			
	
														<div style="clear:both"></div>
	
			
	
														<?php if ($combosValue->combo_type != "Genral") { ?>
	
			
	
															<div class="dish-contant"><?= $combosValue->combo_type ?></div>
	
			
	
														<?php }
	
			
	
														?>
	
			
	
													</div>
	
			
	
			
	
			
	
													<div class="col-md-4 col-sm-5 col-xs-4 no-padding-right1">
	
			
	
														<p class="pull-right"><span><i class="fa fa-inr"></i></span><?= $combosValue->price ?><span class="cart"><i class="fa fa-shopping-cart"></i></span></p>
	
			
	
													</div>
	
			
	
												</a>
	
			
	
											</div> 
			
	
										</div>
	
			
	
									<?php } ?>  
			
	
								</div> 
	
							</div> 
	
						<?php } ?>  
	
					</div>
				</div>
			</div>
			 
        
        </div> 
        
        
        <div class="col-md-4 col-sm-12 col-xs-12" id="order_container">
            <div>
                <h2 class="list-view-fake-header" id="cart_icon">Shopping Cart <a href="javascript:void(0);" title="Empty Cart" class="empty_cart pull-right"><span class="fa fa-trash fa-2x"></span></a></h2>
               
            </div>

            <?php if (trim($restaurant_model->coupon_text)) { ?>
                <div class = "col-md-12 m-t-30">
                    <?= $restaurant_model->coupon_text ?>
                </div>
            <?php } else if ($restaurant_model->restaurantCoupons) { ?>
                <div class = "col-md-12 m-t-30">
                    <?php
                    $restaurant_coupons = $restaurant_model->restaurantCoupons;
                    echo $restaurant_coupons[0]->coupon->title . '</br>' . $restaurant_coupons[0]->coupon->code;
                    ?>
                </div>
                <?php
            }else{
                $open_coupons = \common\models\base\Coupons::findOne(['type' => 'Open', 'notify' => 'Yes', 'status' => 'Active']);
                if ($open_coupons) {
                    ?>
                    <div class = "col-md-12 m-t-30">
                        <?php
                        echo $open_coupons->title . '</br>' . $open_coupons->code;
                        ?>
                    </div>

                    <?php
                }
            }
            ?>
            <div class="col-md-12">
                <p class="pull-left" ><?= Html::input('text', 'coupon_code', (isset($_SESSION['cart']['coupon_data']['coupon_code']) ? $_SESSION['cart']['coupon_data']['coupon_code'] : ''), ['id' => 'coupon_code', 'placeholder' => 'Coupon Code', 'class' => 'form-control text-uppercase']) ?></p>
                <p class="" id="total_p"><button type="button" id="apply_coupon_code_button" class="btn btn-default">Apply</button></p>
            </div>
            <div id="discount_div" class="row">

            </div> 
            <div id="cart_div" >

            </div>
          <div id="apply_discount">

            </div>
        </div>
    </div>
</div>

<div id="wait"><img src='<?= BASE_URL?>frontend/web/images/spinner.gif' width="64" height="64" /><br>Loading..</div>
<?php $this->registerJs('var table_no = '.($table_model ? $table_model->id : 0).'; var restaurant_id = '.$restaurant_model->id.';', \yii\web\VIEW::POS_END); ?>
<?php $this->registerJs($this->render('place_order_js'), \yii\web\VIEW::POS_END); ?>
 
<?php $this->registerCss("#finale_print{display:none}.table.table-condensed thead tr th, .table.table-condensed tbody tr td, .table.table-condensed tbody tr td *{white-space:normal} .sub-tr td{border-bottom : none !important; padding:0 20px !important;}"); ?>

 
