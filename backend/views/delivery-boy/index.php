<?php
 
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use \dmstr\bootstrap\Tabs;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use kartik\export\ExportMenu;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\CuisineSearch $searchModel
 */
$this->title = 'New DeliveryBoy';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="deliveryBoy-index">

    <?php //     echo $this->render('_search', ['model' =>$searchModel]);
    ?>
<?php if(\Yii::$app->session->hasFlash('success')):?>
      <?php echo "<div class='alert alert-success'>".\Yii::$app->session->getFlash('success')."</div>"; ?>
      <?php endif; ?>
     
      <?php if(\Yii::$app->session->hasFlash('warning')):?>
      <?php //echo "<div class='alert alert-warning'>".\Yii::$app->session->getFlash('warning')."</div>"; ?>
      <?php endif; ?>
	 <?php if (\Yii::$app->user->can('selectRestaurant')) {
                Modal::begin([
                    'header' => '<h2>Select restaurant</h2>',
                    'toggleButton' => ['label' => 'Select Restaurant', 'class' => 'btn btn-success'],
                ]);
                echo Html::hiddenInput('user_id', '', ['id' => 'user_id']);
                echo Select2::widget([
                    'name' => 'restaurant_id',
                    'data' => ArrayHelper::map(\common\models\base\Restaurant::findAll(['status' => 'Active']), 'id', 'title'),
                    'options' => [
                        'placeholder' => 'Select Restaurant..',
                        'id' => 'restaurant_id'
                    ],
                ]);
                ?>
            <div class="row p-t-10" id="restaurant_info_div" style="display: none">
            </div>
            <div class="row p-t-10">
                <?php echo Html::button('Select', ['id' => 'select_restaurant_btn', 'class' => 'btn btn-default pull-right']); ?>
            </div>
            <?php
            Modal::end();
            $this->registerJs($this->render('select_restaurant_js'), \yii\web\VIEW::POS_END);
        }?>
<?php $this->beginBlock('dbl'); ?>
    <div class="table-responsive">
     <input id="pac-input" class="controls" type="text" placeholder="Search Box">
    <div id="map" style="width:1180px; height:400px;"></div>
    </div>
    <?php $this->endBlock(); ?> 
<?php $this->beginBlock('main'); ?>
    <div class="clearfix">
        <p class="pull-left">
            <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . 'New' . ' DeliveryBoy', ['create'], ['class' => 'btn btn-success newDeliveryBoy']) ?>
        </p> 
    </div>


    <div class="table-responsive">
        <?=
        GridView::widget([
            'layout' => '{summary}{pager}{items}{pager}',
            'dataProvider' => $dataProvider,
            'pager' => [
                'class' => yii\widgets\LinkPager::className(),
                'firstPageLabel' => 'First',
                'lastPageLabel' => 'Last'],
            'filterModel' => $searchModel,
            'columns' => [

                [
                    'class' => 'yii\grid\ActionColumn',
                    'urlCreator' => function($action, $model, $key, $index) {
                        // using the column name as key, not mapping to 'id' like the standard generator
                        $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
                        $params[0] = \Yii::$app->controller->id ? \Yii::$app->controller->id . '/' . $action : $action;
                        return Url::toRoute($params);
                    },
                            'contentOptions' => ['nowrap' => 'nowrap']
                        ],
                        'id',
                        'email',
                        'first_name',
                        'last_name',
                        'username', 
                        'mobile_no',
                        [
                            'attribute' => 'image',
                            'format' => 'html',
                            'value' => function ($model) {
								 $url = $model->getResizeImageUrl();
                                return Html::img($url, ['alt'=>'','width'=>'200','height'=>'100']);
                                
                            },
                         
                        ],
                       
                         
                    ],
                ]);
                ?>
    </div>
    <?php $this->endBlock(); ?> 
    
    <?php $this->beginBlock('mmm'); ?>
     
   
		
		 <div class="row">
                            <div class="col-md-6 m-b-10 noti-all-block"> 
                                <div class="widget-8 panel no-border noti-background bg-success no-margin widget-loader-bar">
                                    <div class="container-xs-height full-height">
                                        <div class="row-xs-height noti-top-block">
                                            <div class="col-xs-height col-top">
                                                <div class="panel-heading top-left top-right">
                                                    <div class="panel-title text-black hint-text">
                                                        <span class="font-montserrat fs-11 all-caps">Total Delivery Boy <i class="fa fa-chevron-right"></i>
                                                        </span>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row-xs-height noti-bottom-block">
                                            <div class="col-xs-height col-top relative">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div>
															<?php $total= \common\models\base\DeliveryBoy::findAll(['status' =>'10']);?>
                                                            <h3 class="no-margin p-b-5 text-white"><?php echo count($data) ; ?></h3>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6 m-b-10 noti-all-block">

                                <div class="widget-9 panel no-border bg-primary noti-background no-margin widget-loader-bar">
                                    <div class="container-xs-height full-height">
                                        <div class="row-xs-height noti-top-block">
                                            <div class="col-xs-height col-top">
                                                <div class="panel-heading  top-left top-right">
                                                    <div class="panel-title text-black">
                                                        <span class="font-montserrat fs-11 all-caps">Online Delivery Boy <i class="fa fa-chevron-right"></i>
                                                        </span>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row-xs-height noti-bottom-block">
                                            <div class="col-xs-height col-top">
                                                <div>
													<?php $total_online =\common\models\base\DeliveryBoy::findAll(['is_active' =>'1','status' =>'10']) ?>
                                                    <h3 class="no-margin p-b-5 text-white"><?php echo count($live_data)  ; ?></h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </div> 
               </div>
               
               <!------------ Start  list -->
               
                 <div class="row">
                            <div class="col-md-12">

                                <div class="panel panel-transparent">
                                    <div class="panel-heading nopadding">
                                        <div class="panel-title">Monthly Travel
                                        </div>
                                        <div class="tools">
                                            <a class="collapse" href="javascript:;"></a>
                                            <a class="config" data-toggle="modal" href="#grid-config"></a>
                                            <a class="reload" href="javascript:;"></a>
                                            <a class="remove" href="javascript:;"></a>
                                        </div>
                                    </div>
                                    <div class="panel-body nopadding">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-condensed" id="condensedTable">
                                                <thead>
                                                    <tr>
                                                        <th style="width:30%">Name </th>
                                                        <th style="width:30%">Email</th>
                                                        <th style="width:30%">Phone</th>
                                                        <th style="width:40%">Total Travel</th>
                                                        
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
													 
													foreach ($data as $row) { ?>
                                                        <tr>
                                                            <td class="v-align-middle semi-bold"><?php echo $row['first_name']." ".$row['last_name']; ?></td>
                                                           
                                                            <td class="v-align-middle semi-bold"><?php echo $row['email']; ?></td>
                                                            <td class="v-align-middle semi-bold"> <?php echo round($row['mobile_no'], 2); ?></td>
                                                            <?php    
                                                            $travel = \common\models\base\DeliveryBoyTravel::findAll(['user_id' => $id]);
															foreach($travel as $travels){ 
																
															  $total_travel +=$travels->travel_distance;
														   } ?>
                                                            <td class="v-align-middle semi-bold"><?php echo $total_travel; ?> </td>
                                                            
                                                        </tr>
                                                    <?php } ?>


                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div>
             
               
               
               
               <!------End  -->
               
               
               
               
		 
	<?php $this->endBlock(); ?> 
		
    <?=
        Tabs::widget([
    'items' => [
		[
            'label' => 'Delivery Boys Location',
            'content' => $this->blocks['dbl'],
            'options' => ['tag' => 'div'],
            'headerOptions' => ['class' => 'my-class'],
        ],
        [
            'label' => 'Manage Delivery Boy',
            'content' => $this->blocks['main'],
        ],
        [
            'label' => 'Delivery Boy Dashboard',
            'content' => $this->blocks['mmm'],
            'options' => ['tag' => 'div'],
            'headerOptions' => ['class' => 'my-class'],
        ],
		
       
    ],
    'options' => ['tag' => 'div'],
    'itemOptions' => ['tag' => 'div'],
    'headerOptions' => ['class' => 'my-class'],
    'clientOptions' => ['collapsible' => false],
]); ?>


</div>

<div id="deliveryBoyModal" class="modal fade" role="dialog">
  <div class="modal-dialog"> 
    
    <!-- Modal content-->
    
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Warning</h4>
      </div>
      <div class="modal-body">
			<div class="alert alert-danger" id="deliveryBoyModal-warning">
			 
			</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
 
    </br>
    <!--<div class="page_load">
        <img title="Loading..." alt="Loading..." src="">
    </div>-->
    <div id="map" style="height:600px;width:100%;margin-top:30px;"></div>
    <input type="hidden" name="latitude" id="latitude">
    <input type="hidden" name="longitude" id="longitude">
</div>
<!-- map page js starts -->

 <style>
      .controls {
        margin-top: 10px;
        border: 1px solid transparent;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        height: 32px;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
      }

      #pac-input {
        background-color: #fff;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        margin-left: 12px;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 300px;
      }

      #pac-input:focus {
        border-color: #4d90fe;
      }

      .pac-container {
        font-family: Roboto;
      }

      #type-selector {
        color: #fff;
        background-color: #4d90fe;
        padding: 5px 11px 0px 11px;
      }

      #type-selector label {
        font-family: Roboto;
        font-size: 13px;
        font-weight: 300;
      }
      #target {
        width: 345px;
      }
    </style> 
 <?php $this->registerJs('//setTimeout(function(){ window.location.reload(1); }, 50000);', \yii\web\VIEW::POS_END); ?>
<?php  
    $this->registerJs($this->render('index_js'), \yii\web\VIEW::POS_LOAD);?>
	 
   
 <script src='http://maps.googleapis.com/maps/api/js?v=3&libraries=places&key=AIzaSyBXK4L_3Vjr5-ctCMQdCIasqUpJHitQj14'></script>


<script type="text/javascript">

            var popup_pin = "";
            var markersArray = [];
            var newmarkersArray = [];
            var customIcons = {
                restaura3t: {
                    icon: 'http://labs.google.com/ridefinder/images/mm_20_blue.png',
                    shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
                },
                bar: {
                    icon: 'http://labs.google.com/ridefinder/images/mm_20_red.png',
                    shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
                },
                driver_free: {
                    icon: '<?php echo  Yii::getAlias('@web/images/driver_available.png')?>',
                    shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
                },
                driver_not_approved: {
                    icon: '<?php echo Yii::getAlias('@web/image/driver_not_approved.png')?>',
                    shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
                },
                driver_on_trip: {
                    icon: '<?php echo Yii::getAlias('@web//images/driver_on_trip.png')?>',
                    shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
                },
                driver: {
                    icon: '<?php echo Yii::getAlias('@web/images/driver-70.png')?>',
                    shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
                }
            };

            function load(lat, lng) {
				        var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: <?php echo $latitude;?>, lng: <?php echo $longitude;?>},
          zoom: 12,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        });
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(function (position) {
				initialLocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
				map.setCenter(initialLocation);
			});
		}

        // Create the search box and link it to the UI element.
        var input = document.getElementById('pac-input');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
          searchBox.setBounds(map.getBounds());
        });

        var markers = [];
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function() {
		var places = searchBox.getPlaces();
		if (places.length == 0) {
			return;
		}
          // Clear out the old markers.
		  markers.forEach(function(marker) {
			marker.setMap(null);
		  });
		  markers = [];
          // For each place, get the icon, name and location.
          var bounds = new google.maps.LatLngBounds();
			  places.forEach(function(place) {
				var icon = {
				  url: place.icon,
				  size: new google.maps.Size(71, 71),
				  origin: new google.maps.Point(0, 0),
				  anchor: new google.maps.Point(17, 34),
				  scaledSize: new google.maps.Size(25, 25)
				};
	
				// Create a marker for each place.
					markers.push(new google.maps.Marker({
						  map: map,
						   icon: '/khayejao/backend/web/images/logo_white.png', // null = default icon
						  title: place.name,
						  position: place.geometry.location
					}));
				
	
				if (place.geometry.viewport) {
				  // Only geocodes have viewport.
				  bounds.union(place.geometry.viewport);
				} else {
				  bounds.extend(place.geometry.location);
				}
			  });
		  
          map.fitBounds(bounds);
        });
               // var infoWindow = new google.maps.InfoWindow;
						var infowindow = new google.maps.InfoWindow();
                (function () {
                    var f = function () {
                        var marker = new google.maps.Marker();
                       downloadUrl("<?php echo Url::to(['tableorder/delivery_location']); ?>",
                                function (data) {
                                    var xml = data.responseXML;
									
                                    var markers = xml.documentElement.getElementsByTagName("marker");
									
									 var image = {
											url: '<?= BASE_URL. 'backend/web/images/step-4-process.png'; ?>', // image is 512 x 512
											scaledSize : new google.maps.Size(70, 70)
										};
                                    popup_pin = "";
                                    for (var i = 0; i < markers.length; i++) { 
                                        var client_name = markers[i].getAttribute("client_name");
									
										 
                                        var contact = markers[i].getAttribute("contact");

                                        var type = markers[i].getAttribute("type");
                                        var id = markers[i].getAttribute("id");
                                        var angl = markers[i].getAttribute("angl");
                                        var point = new google.maps.LatLng(
										parseFloat(markers[i].getAttribute("lat")),
										parseFloat(markers[i].getAttribute("lng")));
                                        var html = "";
                                        var color = "";
                                        if (type == 'driver_on_trip') {
                                            color = "blue";
                                            /*html = "<b>" + client_name + "</b></p><p><span class ='icon-phone' style=''></span><span style='margin-left:5px;'><br>" + contact + "</span></p><b>";*/
                                            html = "<b>" + client_name + "</b><p style='font-size:16px;'><b><span class ='fa fa-mobile-phone icon-phone' style=''></span><span style='margin-left:5px;'>" + contact + "</span></b><br/>Status : On Trip</p>";
                                        } else if (type == 'driver_free') {
                                            color = "green";
                                            /*html = "<b>" + client_name + "</b></p><p><span class ='icon-phone' style=''></span><span style='margin-left:5px;'><br>" + contact + "</span></p><b>";*/
                                            html = "<b>" + client_name + "</b><p style='font-size:16px;'><b><span class ='fa fa-mobile-phone icon-phone' style=''></span><span style='margin-left:5px;'>" + contact + "</span></b><br/>Status : Free</p>";
                                        } else {
                                            color = "red";
                                            html = "<b>" + client_name + "</b><p style='font-size:16px;'><b><span class ='fa fa-mobile-phone icon-phone' style=''></span><span style='margin-left:5px;'>" + contact + "</span></b><br/>Status : InActive</p>";
                                            /*html = "<b>" + client_name + "</b></p><p><span class ='icon-phone' style=''></span><span style='margin-left:5px;'><br>" + contact + "</span></p><b>";*/
                                        }

                                        var icon = customIcons[type] || {};
                                        marker = new google.maps.Marker({
                                            map: map,
                                            position: point,
                                            icon: image ,
                                            shadow: icon.icon.shadow});
											
											
										  
										  bindInWindow(marker, html,i );
										   newmarkersArray.push(marker);
										  // bindInfoWindow(marker, map,infoWindow, html, type, name, popup_pin);
                                        /*marker = new google.maps.Marker({
                                         map: map,
                                         position: point,
                                         icon: {
                                         path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
                                         //path: 'M150 0 L75 200 L225 200 Z',
                                         //scale: .1,
                                         scale: 6,
                                         fillColor: color,
                                         fillOpacity: 0.8,
                                         strokeWeight: 2,
                                         rotation: parseFloat(markers[i].getAttribute("angl")) //this is how to rotate the pointer
                                         },
                                         shadow: icon.shadow});
                                        newmarkersArray.push(marker);
                                        bindInfoWindow(marker, map,
                                                infoWindow, html, type, name, popup_pin);*/
												
                                             
                                    }
							        clearOverlays(markersArray);
                                    markersArray = newmarkersArray;
                                    newmarkersArray = [];
                                  
                                });
                    };
                    window.setInterval(f, 5000);
                    f();

                    var legendDiv = document.createElement('DIV');
                    var legend = new Legend(legendDiv, map);
					
                    legendDiv.index = 1;
                    map.controls[google.maps.ControlPosition.RIGHT_TOP].push(legendDiv);

                })();
            }


            function clearOverlays(arr) {
                for (var i = 0; i < arr.length; i++) {
                    arr[i].setMap(null);
                }
            }
			
			  function bindInWindow(marker, html,i ) {
				  var infowindow = new google.maps.InfoWindow();
				  google.maps.event.addListener(marker, 'mouseover', (function(marker, i) {
											return function() {
											  infowindow.setContent(html);
											  //console.log(html);
											  infowindow.open(map, marker);
											}
										  })(marker, i));
			  }

            function bindInfoWindow(marker, map, infoWindow, html, type, name, popup_pin) {
                if (name == popup_pin) {
                    infoWindow.setContent(html);
                    infoWindow.open(map, marker);
                    popup_pin = "";
                }
                google.maps.event.addListener(marker, 'click', function () {

                    if (type == 'driver_free') {
                        infoWindow.setContent(html);
                        infoWindow.open(map, marker);
                    } else if (type == 'driver_on_trip') {
                        infoWindow.setContent(html);
                        infoWindow.open(map, marker);
                    } else {
                        infoWindow.setContent(html);
                        infoWindow.open(map, marker);
                    }
                });
            }

            function downloadUrl(url, callback) {
                var request = window.ActiveXObject ?
                        new ActiveXObject('Microsoft.XMLHTTP') :
                        new XMLHttpRequest;
                request.onreadystatechange = function () {
                    if (request.readyState == 4) {
                        request.onreadystatechange = doNothing;
                        callback(request, request.status);
                    }
                };
                request.open('GET', url, true);
                request.send(null);
            }


            function initialize() {

            }

            function codeAddress() {
                geocoder = new google.maps.Geocoder();
                var address = document.getElementById("my-address").value;
                geocoder.geocode({'address': address}, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {

                        var latitude = results[0].geometry.location.lat();
                        var longitude = results[0].geometry.location.lng();
                        // initialize_map(results[0].geometry.location.lat(),results[0].geometry.location.lng());
                        load(latitude, longitude);
                        //         var latlng = new google.maps.LatLng(latitude, longitude);
                        // var map = new google.maps.Map(document.getElementById('map'), {
                        //     center: latlng,
                        //     zoom: 11,
                        //     mapTypeId: google.maps.MapTypeId.ROADMAP
                        // });
                        // var marker = new google.maps.Marker({
                        //     position: latlng,
                        //     map: map,
                        //     title: 'Set lat/lon values for this property',
                        //     draggable: true
                        // });
                    }

                    else {
                        //alert("Geocode was not successful for the following reason: " + status);
                    }
                });
            }

            function doNothing() {
            }

            function Legend(controlDiv, map) {
                // Set CSS styles for the DIV containing the control
                // Setting padding to 5 px will offset the control
                // from the edge of the map
                controlDiv.style.padding = '5px';

                // Set CSS for the control border
                var controlUI = document.createElement('DIV');
                controlUI.style.backgroundColor = 'white';
                controlUI.style.borderStyle = 'solid';
                controlUI.style.borderWidth = '1px';
                controlUI.title = 'Legend';
                controlDiv.appendChild(controlUI);

                // Set CSS for the control text
                var controlText = document.createElement('DIV');
                controlText.style.fontFamily = 'Arial,sans-serif';
                controlText.style.fontSize = '12px';
                controlText.style.paddingLeft = '4px';
                controlText.style.paddingRight = '4px';

               // Add the text
                controlText.innerHTML = '<b>Legends</b><br />' +
                        '<img src="<?php echo  Yii::getAlias('@web/images/driver_available.png'); ?>" style="height:25px;"/> Available<br />' +
                        '<img src="<?php echo Yii::getAlias('@web/images/driver_on_trip.png'); ?>" style="height:25px;"/> On a Trip <br />' +
                        '<img src="<?php echo Yii::getAlias('@web/images/driver_not_approved.png'); ?>" style="height:25px;"/> Inactive<br />'
                controlUI.appendChild(controlText);
            }
            google.maps.event.addDomListener(window, 'load', load('', ''));

</script>