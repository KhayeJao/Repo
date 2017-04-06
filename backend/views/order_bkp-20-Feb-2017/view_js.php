<?php use yii\helpers\Url; ?>
 
	$("document").ready(function() { 
	 $('#custume_msg').hide();
	
	$(".reasonmsg").click(function(){  
	         	
						var id   = $('#id_r').val(); 
						var cmsg = $('#cmsg').val();  
						
							var push_str =$('#sendpushmsg').serialize(); 
							$.ajax({ 
								url:'<?php echo Url::to(['order/orderchangestatus']); ?>',
								data: push_str, 
								type: 'post',
								cache: false,
									success: function(data){ 
										
                                         $('#msgModal').modal('hide'); 
									     $('#err').html(data);
									      load();
									   
									}
							});
						 
						 
					 }); 
					 
					 
					 $('select').on('change', function() {
						 
							var value = $(this).val();
							if(value=='Other'){
								$('#custume_msg').show(); 
							}else{
							    $('#custume_msg').hide(); 	
							}
                     });
	
 
	});
