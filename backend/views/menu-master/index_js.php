<?php use yii\helpers\Url; ?>
$("document").ready(function() {
	
  $("td[contenteditable=true]").blur(function(){  
        var id    = $(this).closest('tr').data('id');
        var order = $(this).text();
        $.ajax({
            url:'<?php echo Url::to(['menu/updateajax']); ?>'+'?id='+id+'&order='+order,
            Type:'GET',
            dataType:'json',
            success:function(data){
                if(data){
                    $('#discount_div').html(data);
                }else{
                    $('#discount_div').html(data);
                }
                 
            }      
        }); 
        
    });
    
    
});

 
