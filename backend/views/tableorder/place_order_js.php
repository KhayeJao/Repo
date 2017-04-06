<?php use yii\helpers\Url;
   //use yii\helpers\Json;
 ?>
 
  $(document).on('click', '.add_to_cart_dish', function () {
        add_dish_to_cart($(this).attr('data-dish_id'));
         var cart = $('#cart_icon');
        var imgtodrag =  $('<img id="dynamic">');
        imgtodrag.attr('src', '<?= BASE_URL. 'backend/web/images/step-4-process.png'; ?>');
        if (imgtodrag) {
            var imgclone = imgtodrag.clone()
                .offset({
                top: $(this).offset().top,
                left: $(this).offset().left
            })
                .css({
                //'opacity': '0.5',
                    'position': 'absolute',
                    'width': '150px',
                    'z-index': '100'
            });

                imgclone.appendTo($('body'))
                .animate({
                'top': cart.offset().top + 10,
                    'left': cart.offset().left + 10,
                    'width': 75,
            }, 1000, 'easeInOutExpo');

            setTimeout(function () {
                cart.effect("shake", {
                    times: 2
                }, 200);
            }, 1500);

            imgclone.animate({
                'width': 0,
                    'height': 0
            }, function () {
                $(this).detach()
            });
        }
    });

$("document").ready(function() {
	
	
	$("li").click(function () {
    $("li").removeClass("active");
     $(".li").addClass("active"); // instead of this do the below 
    //$(this).addClass("active");   
    });
    
    
/*
    $(document).on('click', '.add_to_cart_dish', function () {
        add_dish_to_cart($(this).attr('data-dish_id'));
    });
    */
    
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
            url:'<?php echo Url::to(['tableorder/addcomment']); ?>',
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
    window.location = "<?= Url::to(['tableorder/checkout']); ?>"+"?restaurant_id="+restaurant_id;
    });
    
    $(document).on('click', '#save_button', function () { 
    
     $.ajax({
            url:"<?= Url::to(['tableorder/saveorder']); ?>"+"?restaurant_id="+restaurant_id+"&table_no="+<?= $_SESSION['cart']['table_no']?>,
            Type:'GET',
            dataType:'json',
            success:function(data){ 
                if(data.status=='1'){ 
                    $('#save_order').html(data.message);
                }else{
                    $('#save_order').html(data.message);
                }
            }      
        });
    
    
    });
    
     $(document).on('change', "input[name='item_number']",function(){
		var items = $(this).val(); 
		add_dish_to_cart_input($(this).attr('data-dish_id'),items);   
			   
	});

    refreshCart();
    
});

function add_dish_to_cart(dish_id){
	 
    $.ajax({
            url:'<?php echo Url::to(['tableorder/adddish']); ?>'+'?id='+dish_id+"&type=dish",
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

function add_dish_to_cart_input(dish_id,items){
    $.ajax({
            url:'<?php echo Url::to(['order/adddish']); ?>'+'?id='+dish_id+"&type=dish&type1=input&items="+items,
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
            url:'<?php echo Url::to(['tableorder/removedish']); ?>'+'?id='+dish_id+"&type=dish",
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
            url:'<?php echo Url::to(['tableorder/adddish']); ?>'+'?id='+dish_id+"&type=dish_with_topping&dish_topping_str="+dish_topping_str,
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
            url:'<?php echo Url::to(['tableorder/adddish']); ?>'+'?id='+combo_id+"&type=combo",
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
            url:'<?php echo Url::to(['tableorder/removedish']); ?>'+'?id='+combo_id+"&type=combo",
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
            url:'<?php echo Url::to(['tableorder/refreshcart']); ?>',
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
            url:'<?php echo Url::to(['tableorder/emptycart']); ?>',
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


 $(document).ajaxStart(function(){
        $("#wait").css("display", "block");
    });
    $(document).ajaxComplete(function(){
        $("#wait").css("display", "none");
    });
  

 

$("#search_menu").keyup(function() {
	restaurant_id ='<?=  $_SESSION['cart']['restaurant_id']; ?>';
	//alert(restaurant_id);
   $.ajax({
       url: '<?php echo Yii::$app->request->baseUrl. '/menu/menu' ?>',
       type: 'get',
       data: {title: $("#search_menu").val() ,restaurant_id: '<?=  $_SESSION['cart']['restaurant_id']; ?>'},
       success:  function(data){     
                if(data.search.list){ 
					 $(".amenu").html(data.search.list);
					 $(".amenu").show();
				}else{     
                    $(".amenu").html('No Record Found in your search criteria..');            
			    }
			    
				$("li").click(function () {
					$("li").removeClass("active");
					 $(".li").addClass("active"); // instead of this do the below 
					//$(this).addClass("active");   
				});
				
            }
  });
   
}); 


 
    
    $(document).on('click', '#kitchen_print', function () { 
	      
            var prtContent = document.getElementById('print_ID');  
            var WinPrint = window.open('', '', 'letf=0,top=0,width=400,height=400,toolbar=0,scrollbars=0,status=0');
            
            WinPrint.document.write(prtContent.innerHTML);
            WinPrint.document.close();
            WinPrint.focus();
            WinPrint.print();
            WinPrint.close();
    });
    
    
    
function addElement(parentId, elementTag, elementId, html) {
    // Adds an element to the document
    var p = document.getElementById(parentId);
    var newElement = document.createElement(elementTag);
    newElement.setAttribute('id', elementId);
    newElement.innerHTML = html;
    p.appendChild(newElement);
}

function removeElement(elementId) {
    // Removes an element from the document
    var element = document.getElementById(elementId);
    element.parentNode.removeChild(element);
}
 
/*
 $("#search_menu").autocomplete({
  source: function(request, response) {
    $.getJSON("<?php echo Yii::$app->request->baseUrl. '/menu/menu' ?>", { title: $('#search_menu').val(),restaurant_id: '<?=  $_SESSION['cart']['restaurant_id']; ?>' }, 
              function(data){     
                if(data.search.list){ 
					 $(".amenu").html(data.search.list);
					 $(".amenu").show();
				}else{     
                    $(".amenu").html('No Record Found in your search criteria..');            
			    }
			    
				$("li").click(function () {
					$("li").removeClass("active");
					 $(".li").addClass("active"); // instead of this do the below 
					//$(this).addClass("active");   
				});
				
            });
  },
  minLength: 0,
  select: function(event, ui){  
            
            return false;   
  }
    
    
   

});
*/




