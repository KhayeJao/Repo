<?php
use yii\helpers\Url;
?>
<div class="header ">


    <div class="pull-left full-height visible-sm visible-xs">

        <div class="sm-action-bar">
            <a href="#" class="btn-link toggle-sidebar" data-toggle="sidebar">
                <span class="icon-set menu-hambuger"></span>
            </a>
        </div>

    </div>

    <div class="pull-right full-height visible-sm visible-xs">

        <div class="sm-action-bar">
       <span class=""><?= yii::$app->user->identity->first_name.' '.yii::$app->user->identity->last_name ?> </span>

 <a href="<?php echo Url::to(['site/logout']); ?>" class="btn-link" data-method="post" data-toggle="quickview" data-toggle-element="#quickview">
                <span class="fa fa-lg fa-power-off"></span>
            </a>
        </div>

    </div>

    <div class=" pull-left sm-table">
        <div class="header-inner">
            <div class="brand inline">
                <a href="<?= Url::home(); ?>"><img src="<?= Yii::getAlias('@web/images/logo_white.png'); ?>" alt="logo" data-src="<?= Yii::getAlias('@web/images/logo_white.png'); ?>" data-src-retina="<?= Yii::getAlias('@web/images/logo_white.png'); ?>" height="22"></a>
            </div>
        </div>
    </div>
    <div class="pull-right">

        <div class="visible-lg visible-md m-t-10">
            <div class="dropdown pull-left p-r-10 p-t-10 fs-16 font-heading ">
                <div>
                    <span class="semi-bold"><?= yii::$app->user->identity->first_name.' '.yii::$app->user->identity->last_name ?> </span><a href="<?php echo Url::to(['site/logout']); ?>" data-method="post"><span class="fa fa-lg fa-power-off"></span></a>
                </div>
            </div>

        </div>

    </div>
</div>

<?php  if(yii::$app->user->identity->type=='admin' || yii::$app->user->identity->type=='restaurant' || yii::$app->user->identity->type=='telecaller'){
					      $date = Date('Y-m-d');
					      $cnt =0;
					      if(yii::$app->user->identity->type=='restaurant'){
							  $res_id      =  yii::$app->user->identity->id;
							  $restaurant_model = \common\models\base\Restaurant::findOne(['user_id' => $res_id]);  
					          $order_model = \common\models\base\Order::findAll(['restaurant_id' =>$restaurant_model->id,'status' => 'Placed','close' => '0','placed_via'=>'Telecaller/Admin']); 
					          $order_model_today = \common\models\base\Order::find()
					          ->where(['restaurant_id' =>$restaurant_model->id,'status' => 'Approved','placed_via'=>'Telecaller/Admin','close' => '0','Date(accept_reject_datetime)'=>$date]) 
								->orderBy([
								   'booking_time'=>SORT_ASC, 
								])
								->all(); 			          
								//accept_reject_datetime
						  }else{
							  
							   $order_model = \common\models\base\Order::findAll(['status' => 'Placed','close' => '0','placed_via'=>'Telecaller/Admin']); 
							   $order_model_today = \common\models\base\Order::findAll(['status' => 'Approved','close' => '0','placed_via'=>'Telecaller/Admin','Date(accept_reject_datetime)'=>$date]); 
							    
							  
							    $order_model_today_Rejected = \common\models\base\Order::findAll(['status' => 'Rejected','close' => '0','placed_via'=>'Telecaller/Admin','Date(accept_reject_datetime)'=>$date]);
							   
							     //  today place order  ' or status' => 'Rejected', 
							   $order_model_today_pace = \common\models\base\Order::findAll(['status' => 'Placed','close' => '0', 'placed_via'=>'Telecaller/Admin','Date(booking_time)'=>$date]);
						  } 
						  
						 if(yii::$app->user->identity->type=='telecaller'){
							 
								$cnt   =   count($order_model_today) + count($order_model_today_pace) + count($order_model_today_Rejected);	
								
							}elseif(yii::$app->user->identity->type=='restaurant'){
								
							    $cnt    =  count($order_model) ;	
							    
							}else{
								$cnt =0;
							}
				//if($cnt>0){	
					?>
<div class="container m8">
<div class="row"><div class="col-md-12 col-lg-11 col-xlg-10 noti-back">
						<h2 style=""><i class="fa fa-bell-o" aria-hidden="true"></i><span class="notification-red" id="sp-noti" ><?= $cnt?></span></span> Notification </h2>
					<ul id="notifi">
				<?php
				             $html='';
							 $i=1;
						 if(yii::$app->user->identity->type=='restaurant')
						  {
							
							 foreach($order_model as $order_val){  
								 if($i<4){   
									 $newDateTime = date('F j, Y, g:i a', strtotime($order_val->booking_time));
									 $color=($order_val->view==0 ? "808080" :"A4A4A4");
									$html.='<li><a style="color:#'.$color.'" href="'.Yii::$app->request->baseUrl.'/order/view?id='.$order_val->id.'"><i class="fa fa-envelope"></i> New order placed <span>'.$newDateTime.'</span> </a><a style="color:#'.$color.'" href="#"> <span class="sp" id="'.$order_val->id.'">X</span></a></li>';
							      }
							     $i++;
							 } 
						}
		if(yii::$app->user->identity->type=='telecaller'){
						if($order_model_today){ 
							     
								foreach($order_model_today as $order_model_todays){  
									if($i<4){
										 $newDateTime = date('F j, Y, g:i a', strtotime($order_model_todays->accept_reject_datetime));
										 $color=($order_model_todays->view==0 ? "808080" :"A4A4A4");
										 $restaurant = \common\models\base\Restaurant::findOne(['id' => $order_model_todays->restaurant_id]);
										 $color=($order_model_todays->view==0 ? "808080" :"A4A4A4");
										 if($order_model_todays->status=="Approved"){
										 
										 $html.='<li><a style="color:#'.$color.'" href="'.Yii::$app->request->baseUrl.'/order/view?id='.$order_model_todays->id.'"><i class="fa fa-envelope"></i> Order from '.ucfirst($order_model_todays->user_full_name).'  is accepted by '.ucfirst($restaurant->title).' <span>'.$newDateTime.'</span> </a><a style="color:#'.$color.'" href="#"><span class="sp" id="'.$order_model_todays->id.'">X</span> </a></li>';
										 
										  /* for send notification to telecoller  when order assign to delivery boy  autometicaly by nearby */
								            $deliveryBoyOrder = \common\models\base\DeliveryBoyOrder::findAll(['order_id' => $order_model_todays->id]);
								            if($deliveryBoyOrder){
												
												 $user_info = \common\models\base\DeliveryBoy::findOne(['id' => $deliveryBoyOrder->user_id]);
												
												 if($deliveryBoyOrder->status =="Acknowledge"){ 
													 
													
													 $minutes =  (time()-strtotime($deliveryBoyOrder->created_at))/60*1000; 
													 
													 if($minutes<2){
														 
														$html.='<li><a style="color:#'.$color.'" href="'.Yii::$app->request->baseUrl.'/order/view?id='.$order_model_todays->id.'"><i class="fa fa-envelope"></i> Delivery boy&#39; '.ucfirst($user_info->first_name." ". $user_info->last_name).'  has been assigned the delivery of '.ucfirst($restaurant->title).' <span>'.$newDateTime.'</span> </a><a style="color:#'.$color.'" href="#"><span class="sp" id="'.$order_model_todays->id.'">X</span> </a></li>'; 
														
													}else{
														
													  $html.='<li><a style="color:#'.$color.'" href="'.Yii::$app->request->baseUrl.'/order/view?id='.$order_model_todays->id.'"><i class="fa fa-envelope"></i>  '.ucfirst($user_info->first_name." ". $user_info->last_name).'  has not yet acknowledged the order from '.ucfirst($restaurant->title).' <span>'.$newDateTime.'</span> </a><a style="color:#'.$color.'" href="#"><span class="sp" id="'.$order_model_todays->id.'">X</span> </a></li>'; 
													  
													}
													 
													 
												 }elseif($deliveryBoyOrder->status =="Acknowledged"){
													 
													  $html.='<li><a style="color:#'.$color.'" href="'.Yii::$app->request->baseUrl.'/order/view?id='.$order_model_todays->id.'"><i class="fa fa-envelope"></i>  '.ucfirst($user_info->first_name." ". $user_info->last_name).'  is ready to pick up the order from  '.ucfirst($restaurant->title).' <span>'.$newDateTime.'</span> </a><a style="color:#'.$color.'" href="#"><span class="sp" id="'.$order_model_todays->id.'">X</span> </a></li>';
													  
												 }elseif($deliveryBoyOrder->status =="Pickup"){
													 
													  $html.='<li><a style="color:#'.$color.'" href="'.Yii::$app->request->baseUrl.'/order/view?id='.$order_model_todays->id.'"><i class="fa fa-envelope"></i>  '.ucfirst($user_info->first_name." ". $user_info->last_name).'  has picked up the order from  '.ucfirst($restaurant->title).' <span>'.$newDateTime.'</span> </a><a style="color:#'.$color.'" href="#"><span class="sp" id="'.$order_model_todays->id.'">X</span> </a></li>';
													  
												 }elseif($deliveryBoyOrder->status =="Delivered"){
													 
													  $html.='<li><a style="color:#'.$color.'" href="'.Yii::$app->request->baseUrl.'/order/view?id='.$order_model_todays->id.'"><i class="fa fa-envelope"></i> The order has been delivered from  '.ucfirst($restaurant->title).'  to  '.ucfirst($order_model_todays->user_full_name).' <span>'.$newDateTime.'</span> </a><a style="color:#'.$color.'" href="#"><span class="sp" id="'.$order_model_todays->id.'">X</span> </a></li>';
												 }
												 
												 
												
											}else{
												
												$html.='<li><a style="color:#'.$color.'" href="'.Yii::$app->request->baseUrl.'/order/view?id='.$order_model_todays->id.'"><i class="fa fa-envelope"></i> Order of  '.ucfirst($restaurant->title).'  has not yet been assigned for Delivery 
												<span>'.$newDateTime.'</span> </a><a style="color:#'.$color.'" href="#"><span class="sp" id="'.$order_model_todays->id.'">X</span> </a></li>'; 
												
											}
								     
								          /* end */
										 } 
									}
									$i++;
								}
							 
						 }
						 
						  // if reject by resurant owner  
						 
						 if($order_model_today_Rejected){
						 	       $j=$i;
						 	       foreach($order_model_today_Rejected as $order_model_todays_R){
									   if($j<5){
											 $restaurant = \common\models\base\Restaurant::findOne(['id' => $order_model_todays_R->restaurant_id]);
											 $newDateTime = date('F j, Y, g:i a', strtotime($order_model_todays_R->accept_reject_datetime)); 
											 $html.='<li><a style="color:#'.$color.'" href="'.Yii::$app->request->baseUrl.'/order/view?id='.$order_model_todays_R->id.'"><i class="fa fa-envelope"></i> Order from '.ucfirst($order_model_todays_R->user_full_name).' is cancelled by '.ucfirst($restaurant->title).' <span>'.$newDateTime.'</span> </a><a style="color:#'.$color.'" href="#"><span class="sp" id="'.$order_model_todays_R->id.'">X</span> </a></li>';
									    }
									    $j++;
									 }
									 
							}
						// end 			 
						 
						 if($order_model_today_pace){ 
				                if($html) $html.="<hr>"; 
				                
								foreach($order_model_today_pace as $order_model_today_paces){ 
									if($i<4){  
										 $connection = \Yii::$app->db;
                                         $data = $connection->createCommand("SELECT  * FROM tbl_order where id='".$order_model_today_paces->id."' AND booking_time > DATE_SUB(now(), INTERVAL 5 MINUTE) ")->queryAll(); 
										 
                                         /*
										  cr_dt >= NOW( ) - INTERVAL 10 MINUTE
                                         print_r($data);die;
										 $booking_time =   strtotime($order_model_today_paces->booking_time);
										
										  
										 //$date = date('Y-m-d H:i:s');
										 $currentdate_time =  strtotime($date); 
										// $minut =  date('i',$currentdate_time-$booking_time) ; 
										 $minut =  (time()-$booking_time)/60*1000;
										 */
										if(!$data){
											
										 $newDateTime = date('F j, Y, g:i a', strtotime($order_model_today_paces->booking_time));  
										 $restaurant = \common\models\base\Restaurant::findOne(['id' => $order_model_today_paces->restaurant_id]);
										 $color=($order_model_today_paces->view==0 ? "808080" :"A4A4A4");
										 
										 $html.='<li><a style="color:#'.$color.'" href="'.Yii::$app->request->baseUrl.'/order/view?id='.$order_model_today_paces->id.'"><i class="fa fa-envelope"></i> '.ucfirst($restaurant->title).'  has not yet seen the order from '.ucfirst($order_model_today_paces->user_full_name).' <span>'.$newDateTime.'</span> </a><a style="color:#'.$color.'" href="#"><span class="sp" id="'.$order_model_today_paces->id.'">X</span> </a></li>';
										 
									     }
									     
								     }
								     $i++;
								     
								    
								}
							 
						 }
			}
				 ?> 
				<?= $html?> 
					</ul>
					<h3 class="text-center noti-viewmore"><button class="view-more">view more</button></h3>
					</div></div>
</div>
<?php   } ?> 

 
