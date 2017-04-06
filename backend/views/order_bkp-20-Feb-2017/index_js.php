<?php use yii\helpers\Url; ?>
$("document").ready(function() { 
/*  
 setTimeout(function(){        window.location.reload(1);    }, 70000);   */


 $(document).on('change', '.kv-editable-input', function () {        if($(this).val() == "Rejected"){        

 $(this).closest('.kv-editable-content').find(".editable_status_dd_div:first").show();        }
 else{          
 $(this).closest('.kv-editable-content').find(".editable_status_dd_div:first").hide();            
 $(this).closest('.kv-editable-content').find(".editable_status_ta_div:first").hide();      
 }    
 });        $(document).on('change', '.change_status_dd', function () {        if($(this).val() == "other"){            $(this).closest('.kv-editable-content').find(".editable_status_ta_div:first").show();        }else{            $(this).closest('.kv-editable-content').find(".editable_status_ta_div:first").hide();        }    });
	
	
	
	$(".sendmsg").click(function(){  
						var id   = ('#id_r').val(); 
						var cmsg = ('#cmsg').val(); 
						 
						
							$.ajax({ 
								url:'<?php echo Url::to(['order/orderchangestatus']); ?>?id='+ id + '&act=reject' +'&cmsg'+cmsg,
								data: push_str, 
								type: 'get',
								cache: false,
									success: function(data){ 
										
                                         $('#msgModal').modal('hide');
									     $('#err').html(data);
									   
									}
							});
						 
						 
					 }); 
	
	});
