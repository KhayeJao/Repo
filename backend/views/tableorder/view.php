<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use dmstr\bootstrap\Tabs;

/**
 * @var yii\web\View $this
 * @var common\models\Order $model
 */
$this->title = 'Order ' . $model->order_unique_id;
   
?>

<ul class="breadcrumb"><li><a href="/khayejao/backend/web/index.php">Home</a></li>
<li><a href="/khayejao/backend/web/index.php/order/index">Orders</a></li>
<li><a href="/khayejao/backend/web/index.php/order/view?id=<?= $model->id?>"><?= $model->order_unique_id?></a></li>
<li class="active">View</li>
</ul>
<div class="order-view">

    <!-- menu buttons -->
    <p class='pull-left'>
		<a class="btn btn-default" href="/khayejao/backend/web/index.php/order/index"><span class="glyphicon glyphicon-list"></span> List</a>
        
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
        <?= "Order ID : " . $model->order_unique_id ?>    <?= Html::a('<span class="glyphicon glyphicon-download"></span> ' . 'Download PDF', ['outputinfo', 'id' => $model->id, 'act' => 'download'], ['class' => 'btn btn-info pull-right', 'target' => '_blank']) ?> <?= Html::a('<span class="glyphicon glyphicon-print"></span> ' . 'Print', ['outputinfo', 'id' => $model->id, 'act' => 'print'], ['class' => 'btn btn-info pull-right m-r-10', 'target' => '_blank']) ?></h3>


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
