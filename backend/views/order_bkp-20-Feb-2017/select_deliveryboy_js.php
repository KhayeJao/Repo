<?php use yii\helpers\Url; ?>
$("document").ready(function() {


    
    $("#select_deliveryboy_btn").click(function() {
        if($("#user_id").val() == ""){
            alert('Please Select Delivery Boy.');
        }else{
            var redirect_url = "<?= Url::to(['order/manuallyassign/', 'user_id' => 'user_id_value','order_id' => 'order_id_value']) ?>";
            redirect_url = redirect_url.replace("user_id_value", $('#user_id').val());
            redirect_url = redirect_url.replace("order_id_value", $('#order_id').val()); 
            window.location = redirect_url;
        }
    }); 
    

});
