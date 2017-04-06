<?php use yii\helpers\Url; ?>
$("document").ready(function() {
    refreshAddresses();
    refreshCart();
    $('#address_form').submit(function(e) {
    e.preventDefault();
    e.stopImmediatePropagation();
        $.ajax({
            url:'<?php echo Url::to(['address/createajax']); ?>',
            type:'POST',
            data:$( this ).serialize(),
            dataType:'json',
            success:function(data){
                if(data.status){
                    $('#model_add_address').modal('toggle');
                    $('#address_form')[0].reset();
                    refreshAddresses();
                    //alert("Address added successfully!");
                }else{
                    alert("Could not add address :(");
                }
            }      
        });
        return false;
    });
    
    $(document).on('click', '.remove_address', function () {
        $.ajax({
            url:'<?php echo Url::to(['address/removeaddress']); ?>',
            type:'POST',
            data:{id:$(this).attr('data-id')},
            dataType:'json',
            success:function(data){
                if(data.status){
                    refreshAddresses();
                    alert(data.message);
                }else{
                    alert(data.message);
                }
            }      
        });
    });
    
    $(document).on('click', '.address_radio', function () {   
        $("#address_id").val($(this).val());
        $.ajax({
            url:'<?php echo Url::to(['order/selectaddress']); ?>',
            type:'POST',
            data:{id:$(this).attr('data_area_id'),delivery_type:$('#order-delivery_type').val()},
            dataType:'json',
            success:function(data){
                if(data.status){
                    refreshCart();
                }
            }      
        });
    });
	
    
    
});


function refreshAddresses(){
    $.ajax({
            url:'<?php echo Url::to(['address/randeraddresses']); ?>',
            Type:'GET',
            success:function(data){
                if(data){
                    $('#addresses_div').html(data);
                    if($( ".address_radio:first" ).length){
                        $(".address_radio:first").trigger('click');
                    }else{
                        $("#address_id").val('');
                    }
                }else{
                    $('#addresses_div').html('');
                }
            }      
        });
}

