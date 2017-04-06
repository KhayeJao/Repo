<?php use yii\helpers\Url; ?>

var upload_str = "";
var mobile_arr="";
var coupon_type; 

$("document").ready(function() {
	

	
 /*
 
  var fileInput = document.getElementById("csv"), 
    readFile = function () {
        var reader = new FileReader();
        reader.onload = function () {
           upload_str   = reader.result; 
           //refresh_perameter_div(upload_str);
        };
        // start reading the file. When it is done, calls the onload event defined above.
        reader.readAsBinaryString(fileInput.files[0]);
    };
    
fileInput.addEventListener('change', readFile); 
 */ 
 
$('.upload-form').on('click',function (e){    
	
	var data = new FormData();
jQuery.each(jQuery('#file')[0].files, function(i, file) {
    data.append('file-'+i, file);
});
	
	upload_str =$('#upload-form').serialize();
       $.ajax({ 
			url:'<?php echo Url::to(['groupesms/upload']); ?>',
			data: data,
			cache: false,
			contentType: false,
			processData: false,
			type: 'POST',
		 success: function(data){ 
			 
				 $('#sendsms').show(); 
				 $('#sendsms').html(data);
				 $('#sendmarkeing').hide();  
				 
					 $(".template-sms_m").click(function(){
						var id  = this.id;
						var val = $("#" +id).html();  
						document.getElementById("sms-textarea_m").value =val; 
					 });
					 
					 $("#send-sms").click(function(){ 
						var sms_str =$('#send-sms-form').serialize(); 
							$.ajax({ 
								url:'<?php echo Url::to(['groupesms/marketingsms']); ?>',
								data: sms_str, 
								type: 'post',
								cache: false,
									success: function(data){
                                       
									     $('#err').html(data);
									   
									}
							});
						 
						 
					 }); 
	 
	$("#checkAllm").change(function () {
		$("input:checkbox").prop('checked', $(this).prop("checked"));
	});	 
						
			/*}else{
				
				 $('#upload-form').show(); 
				 $('#err').html(data);
			}*/
				 
		  }     
             
   });
 });  


 $("#send-sms-p").click(function(){ 
						var sms_str =$('#send-sms-form-p').serialize(); 
							$.ajax({ 
								url:'<?php echo Url::to(['groupesms/marketingsms']); ?>',
								data: sms_str, 
								type: 'post',
								cache: false,
									success: function(data){
                                       
									     $('#err').html(data);
									   
									}
							});
						 
						 
					 }); 

 
});
