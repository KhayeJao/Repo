<?php
use yii\helpers\Html;
use common\models\base\Area;
use common\models\base\RestaurantArea;
 ?>
<div class="row">
    <?php
    foreach ($user_model->addresses as $addressesKey => $addressesValue) {
        $area_model = Area::findOne(['id' => $addressesValue->area]);
        ?>
        <div class="col-lg-3">

            <?php
            $label = "<label class='control-label'>Address</label><p> $addressesValue->address_line_1, $addressesValue->address_line_2, $area_model->area_name, $addressesValue->city, $addressesValue->pincode </p>
            <hr/>
            <a href='javascript:void(0);' class='remove_address' data-id = $addressesValue->id><span class='fa fa-trash'> Remove</span></a>";
            echo Html::radio('address_radio',$addressesKey == 0 ? TRUE : FALSE , ['label' => $label,'value' => $addressesValue->id,'class' => 'address_radio','id' => 'address_radio'.$addressesKey,'data_area_id' => $addressesValue->area]);
            
            ?>

        </div>
<?php 
    if($addressesKey == 0){
        $restaurant_area_model = RestaurantArea::findOne(['restaurant_id' => $_SESSION['cart']['restaurant_id'],'area_id' => $addressesValue->area]);
        if($restaurant_area_model){
            $_SESSION['cart']['delivery_charge'] = $restaurant_area_model->delivery_charge;
        }else{
            $_SESSION['cart']['delivery_charge'] = 0;
        }
        
    }
    } ?>
    <div class="col-lg-3">
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#model_add_address"><span class="fa fa-plus"></span> Add Address</button>
    </div>
</div>