<?php

use yii\helpers\Json;
use yii\helpers\Html; 
use yii\helpers\ArrayHelper; 
/*
$coupon_details = common\models\base\Coupons::findOne($coupon_id);
if ($coupon_details) {
    $coupon_perameter = Json::decode($coupon_details->coupon_perameter);
}*/ 
?>
<div class="row">
	<form id='send-sms-form' name="send-sms-form"   method="post"> 
			<div class="col-md-4">
				<div class="pweb-field dropdown-check-list formquick-group ">  
                                

<table class="table items" id="mobile">
						  <thead>
							<tr>
							  <th style="width:6%"><input type="checkbox" id="checkAllm"/></th>
							  <th>Name</th> 
							  <th>Mobile Number</th>
							</tr>
						  </thead>
						 <tbody> 
							<?php
							  foreach($mobileData as $val){
									echo "<tr>
									<th scope='row'><input type='checkbox' id='mobile_no_m' class='update-text-box' name='mobile_no_m[]' value='".$val['mobile']."'></th>
									<td>".$val['name']."</td> 
									<td>".$val['mobile']."</td>
									</tr>";  
							  }?> 
				    </tbody>
         </table> 


				</div>
			</div>
			
			<div class="col-md-4 col-md-offset-1">  
				<textarea rows="4" cols="40" maxlength="150" name="marketing" id="sms-textarea_m" required="required"></textarea> 
			</div>
			<div class="col-md-4 dropdown-smstemplate-list">
				<ul class="items" id="template"> 
					 <?php $i=1; foreach($SmsTemplate as $templates){ ?>
                                         <li class="templateTitle"> <?= ucwords($templates['title'])?> </li>
					 <li ><label id="template_<?= $i?>" class="template-sms_m"><?= $templates['sms']?></label> </li>
					 <?php $i++; } ?>
				 </ul>
			 </div> 
		  <span class="glyphicon glyphicon-check">
			  <input type="button"  class="btn btn-success" id="send-sms" value="Send">	 
			</span>  

  </form>
</div>	
