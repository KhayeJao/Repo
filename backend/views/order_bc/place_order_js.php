<?php use yii\helpers\Url; ?>
$("document").ready(function() {

    $(document).on('click', '.add_to_cart_dish', function () {
        add_dish_to_cart($(this).attr('data-dish_id'));
    });
    
    $(document).on('click', '.select_topping_btn', function () {
        var topping_id_ele_arr = $(this).attr('data-topping-id-ele').split('^_^');
        var topping_id_arr = [];
        for(var i = 0; i < topping_id_ele_arr.length; i++ ){
            topping_id_arr.push($("input:radio[name ='"+topping_id_ele_arr[i]+"']:checked").val());
        }
        add_dish_topping_to_cart($(this).attr('data-dish_id'),topping_id_arr.join('^_^'));
        $('#model_'+$(this).attr('data-dish_id')).modal('hide');
    });
    
    $(document).on('click', '.add_to_cart_combo', function () {
        add_combo_to_cart($(this).attr('data-combo_id'));
    });
    
    $(document).on('click', '.remove_from_cart_dish', function () {
        remove_dish_from_cart($(this).attr('data-dish_id'));
    });
    
    $(document).on('click', '.remove_from_cart_combo', function () {
        remove_combo_from_cart($(this).attr('data-combo_id'));
    });
    
    $(document).on('click', '.empty_cart', function () {
        empty_cart();
        refreshCart();
    });
    
    $(document).on('click', '.add_comment_a', function () {
        $("#comment_span_"+$(this).attr("key")).hide();
        $("#comment_input_span_"+$(this).attr("key")).show();
        $("#dish_comment_ta"+$(this).attr("key")).focus();
    });
    
    $(document).on('focusout', '.dish_comment_ta', function () {
        
        $("#comment_input_span_"+$(this).attr("key")).hide();
        $.ajax({
            url:'<?php echo Url::to(['order/addcomment']); ?>',
            type:'POST',
            data:{id:$(this).attr('data_dish_id'),comment:$(this).val()},
            dataType:'json',
            success:function(data){
                if(data.status == 1){
                    $("#comment_input_span_"+$(this).attr("key")).hide();
                    refreshCart();
                }else{
                    $("#comment_span_"+$(this).attr("key")).show();
                    $("#comment_input_span_"+$(this).attr("key")).hide();
                    alert(data.message);
                }
                
            }      
        });
    });
    
    
    
    $(document).on('click', '#apply_coupon_code_button', function () {
    var coupon_code = $.trim($("#coupon_code").val());
    if(coupon_code != ""){
        $.ajax({
            url:'<?php echo Url::to(['coupons/applycoupon']); ?>'+'?couponcode='+coupon_code,
            Type:'GET',
            dataType:'json',
            success:function(data){
                if(data.status == 1){
                    $('#discount_div').html(data.message);
                }else{
                    $('#discount_div').html(data.message);
                }
                refreshCart();
            }      
        });
    }else{
        alert('Please enter coupon code');
    }
        
    });
    
    $(document).on('click', '#checkout_button', function () {
    window.location = "<?= Url::to(['order/checkout']); ?>"+"?restaurant_id="+restaurant_id+"&user_id="+user_id;
    });
    
    refreshCart();
    
});

function add_dish_to_cart(dish_id){
    $.ajax({
            url:'<?php echo Url::to(['order/adddish']); ?>'+'?id='+dish_id+"&type=dish",
            Type:'GET',
            success:function(data){
                if(data){
                    $('#cart_div').html(data);
                }else{
                    $('#cart_div').html('');
                }
            }      
        });
}

function remove_dish_from_cart(dish_id){
    $.ajax({
            url:'<?php echo Url::to(['order/removedish']); ?>'+'?id='+dish_id+"&type=dish",
            Type:'GET',
            success:function(data){
                if(data){
                    $('#cart_div').html(data);
                }else{
                    $('#cart_div').html('');
                }
            }      
        });
}

function add_dish_topping_to_cart(dish_id,dish_topping_str){
    $.ajax({
            url:'<?php echo Url::to(['order/adddish']); ?>'+'?id='+dish_id+"&type=dish_with_topping&dish_topping_str="+dish_topping_str,
            Type:'GET',
            success:function(data){
                if(data){
                    $('#cart_div').html(data);
                }else{
                    $('#cart_div').html('');
                }
            }      
        });
}

function add_combo_to_cart(combo_id){
    $.ajax({
            url:'<?php echo Url::to(['order/adddish']); ?>'+'?id='+combo_id+"&type=combo",
            Type:'GET',
            success:function(data){
                if(data){
                    $('#cart_div').html(data);
                }else{
                    $('#cart_div').html('');
                }
            }      
        });
}

function remove_combo_from_cart(combo_id){
    $.ajax({
            url:'<?php echo Url::to(['order/removedish']); ?>'+'?id='+combo_id+"&type=combo",
            Type:'GET',
            success:function(data){
                if(data){
                    $('#cart_div').html(data);
                }else{
                    $('#cart_div').html('');
                }
            }      
        });
}


function refreshCart(){
    $.ajax({
            url:'<?php echo Url::to(['order/refreshcart']); ?>',
            Type:'GET',
            success:function(data){
                if(data){
                    $('#cart_div').html(data);
                }else{
                    $('#cart_div').html('');
                }
            }      
        });
}

function empty_cart(){
    $.ajax({
            url:'<?php echo Url::to(['order/emptycart']); ?>',
            Type:'GET',
            success:function(data){
                if(data){
                    $('#cart_div').html(data);
                }else{
                    $('#cart_div').html('');
                }
            }      
        });
}
