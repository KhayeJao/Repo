<?php use yii\helpers\Url; ?>
$("document").ready(function() {


    $("#restaurant_id").change(function() {
        if($(this).val() != ""){
            $.ajax({
                url:'<?php echo Url::to(['restaurant/basicinfo']); ?>'+'?restaurant_id='+$("#restaurant_id").val(),
                Type:'GET',
                success:function(data){
                    if(data){
                        $('#restaurant_info_div').html(data);
                        $("#restaurant_info_div").hide();
                    }else{
                        $("#restaurant_info_div").hide();
                        $('#restaurant_info_div').html('');
                    }
                }      
            });
        }
        
    });
    $("#select_restaurant_btn").click(function() {
        if($("#restaurant_id").val() == ""){
            alert('Please select restaurant.');
        }else{
            var redirect_url = "<?= Url::to(['delivery-boy/index', 'restaurant_id' => 'restaurant_id_value','user_id' => 'user_id_value']) ?>";
            //alert(redirect_url);
		    redirect_url = redirect_url.replace("user_id_value", $('#user_id').val());
            //redirect_url = redirect_url.replace("user_type_value", $('#user_type').val());
            redirect_url = redirect_url.replace("restaurant_id_value", $('#restaurant_id').val());
            window.location = redirect_url;
        }
    });
    
    
    $("#restaurant_id_table").change(function() {
        if($(this).val() != ""){
            $.ajax({
                url:'<?php echo Url::to(['restaurant/basicinfo']); ?>'+'?restaurant_id='+$("#restaurant_id_table").val(),
                Type:'GET',
                success:function(data){
                    if(data){
                        $('#restaurant_info_div_table').html(data);
                        $("#restaurant_info_div_table").show();
                    }else{
                        $("#restaurant_info_div_table").hide();
                        $('#restaurant_info_div_table').html('');
                    }
                }      
            });
        }
        
    });
    $("#select_restaurant_table_btn").click(function() {
        if($("#restaurant_id_table").val() == ""){
            alert('Please select restaurant.');
        }else{
            var redirect_url = "<?= Url::to(['deliveryBoy/selectRestaurant/', 'restaurant_id' => 'restaurant_id_value','user_id' => 'user_id_value']) ?>";
            redirect_url = redirect_url.replace("user_id_value", $('#user_id_table').val());
            redirect_url = redirect_url.replace("restaurant_id_value", $('#restaurant_id_table').val());
            window.location = redirect_url;
        }
    });

});
