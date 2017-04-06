<?php

use yii\helpers\Html;
?>
<div class="col-md-12">
    <div class="container">
        <div class="row">
            <div class="col-xs-6">
                <a href="<?= \yii\helpers\Url::home(TRUE) ?>"><img src="<?= Yii::$app->params['base_url'] . '/images/red-logo.png'; ?>"/></a>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-xs-5 pull-left">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Restaurant : <?= $table_booking->tableBookingTables[0]->table->restaurant->title ?></h4>
                    </div>
                    <div class="panel-body">
                        <p>
                            Address : <?= $table_booking->tableBookingTables[0]->table->restaurant->address . ", " . $table_booking->tableBookingTables[0]->table->restaurant->area . ", " . $table_booking->tableBookingTables[0]->table->restaurant->city ?><br>
                            Open : <?= $table_booking->tableBookingTables[0]->table->restaurant->open_datetime_1 . " - " . $table_booking->tableBookingTables[0]->table->restaurant->close_datetime_1 . " AND " . $table_booking->tableBookingTables[0]->table->restaurant->open_datetime_2 . " - " . $table_booking->tableBookingTables[0]->table->restaurant->close_datetime_2 ?><br>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xs-5 pull-right">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Details</h4>
                    </div>
                    <div class="panel-body">
                        <p>
                            <?= $table_booking->user->first_name . " " . $table_booking->user->last_name ?><br>
                            Order #<?= $table_booking->order_unique_id ?><br>
                            Check-In Date Time : <?= Yii::$app->formatter->asDatetime($table_booking->checkin_datetime) ?>
                        </p>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>


<div class="invoice">

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
        <div class="col-xs-5 pull-left">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Payment details</h4>
                </div>
                <div class="panel-body">

                    <?php if (trim($table_booking->payment_info)) { ?>
                        <p><strong>Payment Info : </strong><?= $table_booking->payment_info ?></p>
                    <?php } ?>
                    <p><strong>Order Date-Time :</strong><?= Yii::$app->formatter->asDatetime($table_booking->booking_date) ?></p>

                </div>
            </div>
        </div>
        <div class="col-xs-5 pull-right">
            <table class="table table-clear">
                <tbody>
                    <tr>
                        <td class="left"><strong>Subtotal</strong></td>
                        <td class="right">Rs. <?= $table_booking->sub_total ?></td>                                        
                    </tr>
                    <?php if ($table_booking->discount_amount > 0) { ?>
                        <tr>
                            <td class="left"><strong>Discount <?= (trim($table_booking->discount_text) ? "(" . $table_booking->discount_text . ")" : '') ?></strong></td>
                            <td class="right">Rs. <?= $table_booking->discount_amount ?></td>                                        
                        </tr>
                    <?php } ?>
                    <tr>
                        <td class="left"><strong>Total</strong></td>
                        <td class="right"><strong>Rs. <?= $table_booking->grand_total ?></strong></td>                                        
                    </tr>                                  
                </tbody>
            </table>

        </div>
    </div>

</div>