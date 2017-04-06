<?php
use yii\helpers\Html;
?>
<h1>Invoice</h1>

<div class="invoice">

    <div class="row margin-top">



        <div class="col-sm-6">
            <div class="well min-h">
                <h2> <span><i class="fa fa-cutlery"></i></span> Restaurant</h2>
                <hr class="dark-border">
                <h4><?= $table_booking->tableBookingTables[0]->table->restaurant->title ?></h4>
                <p><strong>Address :</strong> <?= $table_booking->tableBookingTables[0]->table->restaurant->address . ", " . $table_booking->tableBookingTables[0]->table->restaurant->area . ", " . $table_booking->tableBookingTables[0]->table->restaurant->city ?></p>
                <p><strong>Open :</strong> <?= $table_booking->tableBookingTables[0]->table->restaurant->open_datetime_1 . " - " . $table_booking->tableBookingTables[0]->table->restaurant->close_datetime_1 . " AND " . $table_booking->tableBookingTables[0]->table->restaurant->open_datetime_2 . " - " . $table_booking->tableBookingTables[0]->table->restaurant->close_datetime_2 ?></p>

            </div>
        </div><!--/col-->

        <div class="col-sm-6">
            <div class="well min-h">
                <h2><span><i class="fa fa-info-circle"></i></span> Details</h2>
                <hr class="dark-border">
                <h4><?= $table_booking->user->first_name . " " . $table_booking->user->last_name ?></h4>
                <p>Order ID :<strong> <?= $table_booking->order_unique_id ?></strong></p>
                <p><strong>Check-In Date Time :</strong> <?= Yii::$app->formatter->asDatetime($table_booking->checkin_datetime) ?></p>
            </div>
        </div><!--/col-->		

    </div><!--/row-->
    <div class="table-invoice">
        <table class="table table-striped table-responsive">
            <thead>
                <tr>
                    <th class="center">#</th>
                    <th>Table #</th>
                    <th>Seats</th>
                    <th class="center">Price</th>
                </tr>
            </thead>   
            <tbody>
                <?php foreach ($table_booking->tableBookingTables as $booked_tables_key => $booked_tables_value) { ?>
                    <tr>
                        <td class="center"><?= $booked_tables_key + 1 ?></td>
                        <td class="left"><?= $booked_tables_value->table->table_id ?></td>
                        <td class="left"><?= $booked_tables_value->table->no_of_seats ?></td>
                        <td class="center">Rs <?= $booked_tables_value->table_price ?></td>
                    </tr>
                <?php } ?>                               
            </tbody>
        </table>
    </div>

    <div class="row">

        <div class="col-lg-4 col-sm-5 notice">
            <div class="well">
                <h2>Payment details</h2>
                <hr>
                <?php if(trim($table_booking->payment_info)){ ?>
                    <p><strong>Payment Info : </strong><?= $table_booking->payment_info ?></p>
                <?php }  ?>
                <p><strong>Order Date-Time :</strong><?= Yii::$app->formatter->asDatetime($table_booking->booking_date) ?></p>
            </div>	
        </div><!--/col-->

        <div class="col-lg-4 col-lg-offset-4 col-sm-5 col-sm-offset-2 recap">
            <table class="table table-clear">
                <tbody>
                    <tr>
                        <td class="left"><strong>Subtotal</strong></td>
                        <td class="right">Rs. <?= $table_booking->sub_total ?></td>                                        
                    </tr>
                    <?php if ($table_booking->discount_amount > 0) { ?>
                    <tr>
                        <td class="left"><strong>Discount <?= (trim($table_booking->discount_text) ? "(".$table_booking->discount_text.")" : '') ?>(20%)</strong></td>
                        <td class="right"><?= $table_booking->discount_amount ?></td>                                        
                    </tr>
                    <?php } ?>
                    <tr>
                        <td class="left"><strong>Total</strong></td>
                        <td class="right"><strong>Rs. <?= $table_booking->grand_total ?></strong></td>                                        
                    </tr>                                  
                </tbody>
            </table>
            <?= Html::a('<i class="fa fa-download"></i> ' . 'Download PDF', ['tablebookingoutputinfo', 'id' => base64_encode($table_booking->order_unique_id),'act' => 'download'], ['class' => 'btn btn-info ','target' => '_blank']) ?> 
            <?= Html::a('<i class="fa fa-print"></i> ' . 'Print', ['tablebookingoutputinfo', 'id' => base64_encode($table_booking->order_unique_id),'act' => 'print'], ['class' => 'btn btn-success','target' => '_blank']) ?>
        </div><!--/col-->

    </div><!--/row-->

</div>