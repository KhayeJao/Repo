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

<?php $this->beginBlock('dbl'); ?>
    <div class="table-responsive">
     <input id="pac-input" class="controls" type="text" placeholder="Search Box">
    <div id="map" style="width:1180px; height:400px;"></div>
    </div>
    <?php $this->endBlock(); ?> 
<?php $this->beginBlock('main'); ?>
    <div class="clearfix">
        <p class="pull-left">
            <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . 'New' . ' DeliveryBoy', ['create'], ['class' => 'btn btn-success']) ?>
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
                                                    <h3 class="no-margin p-b-5 text-white"><?php echo count($total_online)  ; ?></h3>
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
                                                    <?php foreach ($data as $row) { ?>
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
	 
	 
    <script>
      // This example adds a search box to a map, using the Google Place Autocomplete
      // feature. People can enter geographical searches. The search box will return a
      // pick list containing a mix of places and predicted search terms.

      // This example requires the Places library. Include the libraries=places
      // parameter when you first load the API. For example:
      
      function initAutocomplete() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: 23.0396, lng: 72.566},
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
	
				/*// Create a marker for each place.
					markers.push(new google.maps.Marker({
						  map: map,
						  icon: icon,
						  title: place.name,
						  position: place.geometry.location
					}));*/
				
	
				if (place.geometry.viewport) {
				  // Only geocodes have viewport.
				  bounds.union(place.geometry.viewport);
				} else {
				  bounds.extend(place.geometry.location);
				}
			  });
		  
          map.fitBounds(bounds);
        });
		//////////////set custome Marker/////////////////////
		var locations = [
		  <?php if (count($live_data)>0) { 
		  		for($i=0;$i<count($live_data);$i++){
			?>
		  ['<?php echo $live_data[$i]['first_name']." ".$live_data[$i]['last_name']; ?>', <?php echo $live_data[$i]['latitude'].','.$live_data[$i]['longitude']; ?>, 4],
		  <?php }
		  } ?>
		];
        var infowindow = new google.maps.InfoWindow();
		var marker, i;
	
		for (i = 0; i < locations.length; i++) { 
		  marker = new google.maps.Marker({
			position: new google.maps.LatLng(locations[i][1], locations[i][2]),
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
    </script>
	 <script src="https://maps.googleapis.com/maps/api/js?libraries=places&callback=initAutocomplete"async defer></script> 
    
 
