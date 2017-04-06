<?php use yii\helpers\Url; ?>

$('document').ready(function() { 
	
			  var data ="<?php echo \Yii::$app->session->getFlash('warning');?>";
			  
					// alert(data);
						if(data){
							$('#deliveryBoyModal-warning').html(data);
							$('#deliveryBoyModal').modal('show');
							 
						}else{
							$('#deliveryBoyModal-warning').html(data); 
							$('#deliveryBoyModal').modal('hide');
						}
						
 				 
	
});


function loadlink(){
	 
    $.ajax({
            url:'<?php echo Url::to(['tableorder/delivery_location']); ?>', 
            dataType:'json',
            success:function(data){  
			 google.maps.event.addDomListener(window, 'load', initAutocomplete);
				 var locations =data; 
        var infowindow = new google.maps.InfoWindow();
		var marker, i;
		var image = {
			url: '<?= BASE_URL. 'backend/web/images/step-4-process.png'; ?>', // image is 512 x 512
			scaledSize : new google.maps.Size(70, 70)
		};
		for (i = 0; i < locations.length; i++) { 
		  marker = new google.maps.Marker({
			position: new google.maps.LatLng(locations[i][1], locations[i][2]),
			icon: image ,
			map: map
		  });
	
		  google.maps.event.addListener(marker, 'mouseover', (function(marker, i) {
			return function() {
			  infowindow.setContent(locations[i][0]);
			  infowindow.open(map, marker);
			}
		  })(marker, i));
		}
            }      
        });
}

loadlink(); // This will run on page load
setInterval(function(){
    loadlink() // this will run after every 5 seconds
}, 30000);