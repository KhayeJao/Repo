<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use dmstr\bootstrap\Tabs;
use yii\bootstrap\Modal;

/**
 * @var yii\web\View $this
 * @var common\models\Order $model
 */
$this->title = 'New Order';
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'New Order';
?>
<div class="order-view">
    <div class="col-md-12">
        <h3>Restaurant Info</h3>
        <div class="form-group col-md-4">
            <label class="control-label col-sm-3" >Restaurant : </label>
            <div class="col-sm-9">
                <?= $restaurant_model->title ?> (<?= $restaurant_model->food_type ?>)
            </div>
        </div>
        <div class="form-group col-md-4">
            <div class="row">
                <label class="control-label col-sm-3" >Address : </label>
                <div class="col-sm-9">
                    <?= $restaurant_model->address ?>
                </div>
            </div>
            <div class="row">
                <label class="control-label col-sm-3" >Area : </label>
                <div class="col-sm-9">
                    <?= $restaurant_model->area ?>
                </div>
            </div>
            <div class="row">
                <label class="control-label col-sm-3" >City : </label>
                <div class="col-sm-9">
                    <?= $restaurant_model->city ?>
                </div>
            </div>
        </div>
        <div class="form-group col-md-4">
            <label class="control-label col-sm-3" >Min. Order : </label>
            <div class="col-sm-9">
                <?= $restaurant_model->min_amount ?> Rs.
            </div>
        </div>
        <div class="form-group col-md-4">
            <label class="control-label col-sm-3" >Timings : </label>
            <div class="col-sm-9">
                <?= $restaurant_model->open_datetime_1 ?> - <?= $restaurant_model->close_datetime_1 ?></br>
                <?= $restaurant_model->open_datetime_2 ?> - <?= $restaurant_model->close_datetime_2 ?>
            </div>
        </div>
        <div class="form-group col-md-4">
            <label class="control-label col-sm-3" >Avg. Rating : </label>
            <div class="col-sm-9">
                <?= $restaurant_model->avg_rating ?>
            </div>
        </div>
        <div class="form-group col-md-4">
            <label class="control-label col-sm-3" >Delivery Areas : </label>
            <div class="col-sm-9">
                <?php $restaurant_delivery_areas = $restaurant_model->restaurantAreas; ?>

                <?php
                $areas_arr = array();
                foreach ($restaurant_delivery_areas as $key => $value) {
                    array_push($areas_arr, $value->area->area_name);
                }
                echo implode(', ', $areas_arr);
                ?>

            </div>
        </div>

    </div>
    <div class="col-md-12">
        <h3>Order Info</h3>
        <div class="col-md-8" id="dishes_container">

            <?php
            $tabs_array = array();
            $restaurant_menus = $restaurant_model->menus;
            foreach ($restaurant_menus as $key => $value) {
                $this->beginBlock($value->id);
                ?>
                <div class="row">
                    <h4><?php echo $value->excerpt ?></h4>
                    <div class="row">
                        <?php foreach ($value->dishes as $dishesKey => $dishesValue) { ?>
                            <div class="col-md-3 p-r-50">
                                <?php if ($dishesValue->toppingGroups) { ?>
                                    <div class="row">
                                        <label><?= $dishesValue->title ?></label>
                                        <span class="pull-right"><?= $dishesValue->price ?> Rs.</span>
                                    </div>

                                    <?php
                                    Modal::begin([
                                        'header' => '<h2>Choose toppings for ' . $dishesValue->title . '</h2>',
                                        'toggleButton' => ['label' => 'Select topping', 'class' => 'btn btn-success pull-right'],
                                        'id' => 'model_' . $dishesValue->id,
                                    ]);
                                    ?>
                                    <?php
                                    $dish_topping_id_ele_arr = array();
                                    foreach ($dishesValue->toppingGroups as $tgroupskey => $tgroupsvalue) {
                                        ?>
                                        <div class="row">
                                            <h5><?= $tgroupsvalue->title; ?></h5>
                                            <?php foreach ($tgroupsvalue->dishToppings as $dtKey => $dtValue) { ?>
                                                <div class="col-md-6 p-r-50">
                                                    <div class="row">
                                                        <?= Html::radio('topping_' . $dishesValue->id . '_' . $tgroupskey, (!$dtKey ? TRUE : FALSE), ['value' => $dtValue->id]) ?>

                                                        <label><?= $dtValue->topping->title; ?></label>
                                                        <span class="pull-right"><?= ($dtValue->price > 0 ? $dtValue->price . ' Rs.' : 'Free') ?></span>
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
                                    ?>

                                <?php } else { ?>
                                    <div class="row">
                                        <label><?= $dishesValue->title ?></label>
                                        <span class="pull-right"><?= $dishesValue->price ?> Rs.</span>
                                    </div>
                                    <div class="row">
                                        <a href="javascript:void(0);" data-dish_id="<?= $dishesValue->id ?>" class="add_to_cart_dish pull-right">Add to cart</a>
                                    </div>

                                <?php }
                                ?>
                            </div>
                        <?php }
                        ?>
                    </div>
                </div>

                <?php
                $this->endBlock();
                array_push($tabs_array, [
                    'label' => $value->title,
                    'content' => $this->blocks[$value->id],
                    'active' => ($key == 0 ? true : 0),
                ]);
            }
            $restaurant_combos = $restaurant_model->combos;
            if ($restaurant_combos) {
                $this->beginBlock('combo');
                ?>
                <div class="row">
                    <h4><?php echo $value->excerpt ?></h4>
                    <div class="row">
                        <?php foreach ($restaurant_combos as $combosKey => $combosValue) { ?>
                            <div class="col-md-3 p-r-50">
                                <div class="row">
                                    <label><?= $combosValue->title ?></label>
                                    <span class="pull-right"><?= $combosValue->price ?> Rs.</span>
                                </div>
                                <div class="row">

                                    <?php
                                    $combo_dishes_arr = array();
                                    foreach ($combosValue->comboDishes as $comboDishesKey => $comboDishesValue) {
                                        array_push($combo_dishes_arr, ($comboDishesValue->dish_qty > 1 ? $comboDishesValue->dish_qty : '') . " " . $comboDishesValue->dish->title);
                                    }
                                    echo implode(', ', $combo_dishes_arr);
                                    ?>
                                </div>
                                <div class="row">
                                    <a href="javascript:void(0);" class="add_to_cart_combo pull-right" data-combo_id="<?= $combosValue->id ?>">Add to cart</a>
                                </div>
                            </div>
                        <?php }
                        ?>
                    </div>
                </div>
                <?php
                $this->endBlock();
                array_push($tabs_array, [
                    'label' => 'Combos',
                    'content' => $this->blocks['combo'],
                    'active' => FALSE,
                ]);
            }


            echo Tabs::widget(
                    [
                        'id' => 'relation-tabs',
                        'encodeLabels' => false,
                        'items' => $tabs_array,
            ]);
            ?>
        </div>
        <div class="col-md-4" id="order_container">
            <div class="col-md-12">
                <h2 class="list-view-fake-header">Shopping Cart <a href="javascript:void(0);" title="Empty Cart" class="empty_cart pull-right"><span class="fa fa-trash fa-2x"></span></a></h2>
                <a href="#"><span class="fa fa-trash fa-2x pull-right" style="color: black"></span></a>
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
            } else {
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
                <p class="pull-left"><?= Html::input('text', 'coupon_code', (isset($_SESSION['cart']['coupon_data']['coupon_code']) ? $_SESSION['cart']['coupon_data']['coupon_code'] : ''), ['id' => 'coupon_code', 'placeholder' => 'Coupon Code', 'class' => 'form-control text-uppercase']) ?></p>
                <p class="pull-right" id="total_p"><button type="button" id="apply_coupon_code_button" class="btn btn-default">Apply</button></p>
            </div>
            <div id="discount_div" class="row">

            </div>
            <div id="cart_div" class="col-md-12">

            </div>
<!--            <div id="apply_discount">

            </div>-->
        </div>
    </div>
</div>
<?php $this->registerJs('var user_id = '.($user_model ? $user_model->id : 0).'; var restaurant_id = '.$restaurant_model->id.';', \yii\web\VIEW::POS_END); ?>
<?php $this->registerJs($this->render('place_order_js'), \yii\web\VIEW::POS_END); ?>

<?php $this->registerCss(".table.table-condensed thead tr th, .table.table-condensed tbody tr td, .table.table-condensed tbody tr td *{white-space:normal} .sub-tr td{border-bottom : none !important; padding:0 20px !important;}"); ?>
