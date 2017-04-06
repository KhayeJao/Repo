<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use dmstr\bootstrap\Tabs; 
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;

/**
 * @var yii\web\View $this
 * @var common\models\Order $model
 */
$this->title = 'Order ' . $model->order_unique_id;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string) $model->order_unique_id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'View';
?>
<div class="order-view">

    <!-- menu buttons -->
    <p class='pull-left'>
        <?= Html::a('<span class="glyphicon glyphicon-list"></span> ' . 'List', ['index'], ['class' => 'btn btn-default']) ?>
    </p>

    <div class="clearfix"></div>

    <!-- flash message -->
    <?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
        <span class="alert alert-info alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <?= \Yii::$app->session->getFlash('deleteError') ?>
        </span>
    <?php endif; ?>



    <h3>
		  
        <?= "Order ID : " . $model->order_unique_id ?> </h3>  
         
        <div class="row" style="float:right !important">
			
			<?php 
	 $deliveryBoyOrder = \common\models\base\DeliveryBoyOrder::findAll(['order_id' => $model->id]);
	 
				 if(!$deliveryBoyOrder){
												
					 
			
								if(yii::$app->user->identity->type=='telecaller'){
									Modal::begin([
										'header' => '<h2>Select Delivery Boy</h2>',
										'toggleButton' => ['label' => 'Manualy Delivery Boy Assign', 'class' => 'btn btn-success'],
									]);
									echo Html::hiddenInput('order_id', '"'.$model->id.'"', ['id' => 'order_id']);
									
									echo Select2::widget([
										'name' => 'user_id',
										'data' => ArrayHelper::map(\common\models\base\DeliveryBoy::findAll(['status' => '10']), 'id', 'first_name'),
										'options' => [
											'placeholder' => 'Select Delivery Boy..',
											'id' => 'user_id'
										],
									]);
									?>
								<div class="row p-t-10" id="restaurant_info_div" style="display: none">
								</div>
								<div class="row p-t-10">
									<?php echo Html::button('Assign', ['id' => 'select_deliveryboy_btn', 'class' => 'btn btn-default pull-right']); ?>
								</div>
								<?php
								Modal::end();
								$this->registerJs($this->render('select_deliveryboy_js'), \yii\web\VIEW::POS_END);
							}
							
        
}
        
        ?>
       <?php if(($model->status=="Placed") && (yii::$app->user->identity->type=='restaurant' || yii::$app->user->identity->type=='admin') ){ ?>
        <?= Html::a('<span class="glyphicons glyphicons-tick"></span> ' . 'Accept', ['orderchangestatus', 'id' => $model->id, 'act' => 'accept'], ['class' => 'btn btn-success  m-r-10 ']) ?> 
        
        <?= Html::a('<span class="glyphicons glyphicons-tick "></span> ' . 'Reject', ['#'], [
										'data-toggle' => 'modal',
										'data-target' => '#msgModal',
                                        'title' => 'Order Reject',
                                        'class' => 'btn btn-info  m-r-10 ',
                                        'Onclick' => 'GetID('.$model->id.')',
                            ]) ?>
          <?php  } ?> 
        
           <?= Html::a('<span class="glyphicon glyphicon-download"></span> ' . 'Download PDF', ['outputinfo', 'id' => $model->id, 'act' => 'download'], ['class' => 'btn btn-info ', 'target' => '_blank']) ?> <?= Html::a('<span class="glyphicon glyphicon-print"></span> ' . 'Print', ['outputinfo', 'id' => $model->id, 'act' => 'print'], ['class' => 'btn btn-info  m-r-10', 'target' => '_blank']) ?>
</div>

    <?php $this->beginBlock('common\models\Order'); ?>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
//            'id',
//            'user_id',
            [
                'label' => 'Restaurant',
                'value' => $model->restaurant->title,
            ],
            'order_unique_id',
//            'affiliate_order_id',
            'user_full_name',
            'mobile',
            'email:email',
            [
                'label' => 'Delivery Address',
                'value' => $model->delivery_type != 'Pickup' ? $model->address_line_1 . ($model->address_line_2 ? ", " . $model->address_line_2 : '') . ", " . $model->area0->area_name . ", " . $model->city . ", " . $model->pincode : "Not Applicable"
            ],
//            'address_line_1',
//            'address_line_2',
//            'area',
//            'city',
//            'pincode',
            'delivery_time:datetime',
//            'coupon_code',
            [
                'label' => 'Coupon Code',
                'value' => ($model->coupon_code ? $model->coupon_code : 'Not Used'),
            ],
            [
                'label' => 'Discount Amount',
                'value' => ($model->discount_amount ? $model->discount_amount : 0),
            ],
//            'discount_amount',
            'discount_text',
            [
                'label' => 'Order Items',
                'value' => $model->order_items . " Items",
            ],
            [
                'label' => 'Tax',
                'value' => $model->tax . " (" . $model->tax_text . ")",
            ],
//            'tax',
//            'tax_text',
            [
                'label' => 'Vat',
                'value' => $model->vat . " (" . $model->vat_text . ")",
            ],
//            'vat',
//            'vat_text',
            [
                'label' => 'Service Charge',
                'value' => $model->service_charge . " (" . $model->service_charge_text . ")",
            ],
//            'service_charge',
//            'service_charge_text',
            'comment',
            'booking_time:datetime',
            'order_status_change_datetime',
            'order_status_change_reason',
            'order_ip',
            'status',
        ],
    ]);
    ?>

    <hr/>

    <?php
//    Html::a('<span class="glyphicon glyphicon-trash"></span> ' . 'Delete', ['delete', 'id' => $model->id], [
//        'class' => 'btn btn-danger',
//        'data-confirm' => '' . 'Are you sure to delete this item?' . '',
//        'data-method' => 'post',
//    ]);
    ?>
    <?php $this->endBlock(); ?>



    <?php $this->beginBlock('OrderDetails'); ?>
    <?= $order_view ?>
    <?php $this->endBlock() ?>

    <?=
    Tabs::widget(
            [
                'id' => 'relation-tabs',
                'encodeLabels' => false,
                'items' => [ [
                        'label' => '<span class="glyphicon glyphicon-asterisk"></span> Overview',
                        'content' => $this->blocks['common\models\Order'],
                        'active' => false,
                    ], [
                        'label' => '<small><span class="glyphicon glyphicon-paperclip"></span> Details</small>',
                        'content' => $this->blocks['OrderDetails'],
                        'active' => true,
                    ],]
            ]
    );
    ?></div>
    
    
    <script>
function GetID(cid){
	$('#id_r').val(cid);
}  
 
</script>

    <div id="msgModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
       <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" > Reason </h4>
      </div>
      <div class="modal-body text-center">
       <form action="" id="sendpushmsg" method="post">
		   <input type="hidden" name="id" id="id_r" value="">
		   <input type="hidden" name="act"   value="reject"> 
			<select name="msg" id="cmsg" class="form-control" required>
				<option value="Restaurant closed" selected>Restaurant closed</option>
				<option value="Delivery boy unavailable">Delivery boy unavailable</option>
				<option value="cant deliver within 45 minutes">cant deliver within 45 minutes</option>
				<option value="Other">Other</option>
			</select> 
			<span id="custume_msg">  <lable> Custume Massage: </lable> <input type="text" name="cmsg"    value="">  </span>
			<br>
		    <input type="button"  class="btn btn-success reasonmsg"    value="Submit" style="margin-top:20px;">
		    
       </form>
      </div>
      <div class="modal-footer">
       
      </div>
    </div>

  </div>
</div>

