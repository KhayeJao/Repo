<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\DatePicker;
use kartik\grid\GridView;
use yii\helpers\Url;

$this->title = 'Dashboard';
?>
<div class="page-container">
    <div class="page-content-wrapper">
        <div class="content sm-gutter">
            <div class="container-fluid padding-25 sm-padding-10">
                <div class="row">
                    <div class="col-md-12 col-lg-11 col-xlg-10 ">
                        <div class="row">
                            <div class="settings-search">
                                <?php
                                $form = ActiveForm::begin([
                                            'action' => ['index'],
                                            'method' => 'get',
                                ]);
                                ?>
                                <div class="form-group">
                                    <label class="control-label col-sm-1"> Month </label>
                                    <div class="col-sm-2">
										<input type="text" name ="site[date]" class="form-control"   id="txtDate" />
                                          <? /*
                                        DatePicker::widget([
                                            'name' => 'site[date]',
                                            'type' => kartik\widgets\DatePicker::TYPE_INPUT,
                                            'pluginOptions' => [
                                                'autoclose' => true,
                                                'format' => 'yyyy-MM'
                                            ],
                                        ]);
                                        * */
                                        ?>
                                        <div class="help-block help-block-error "></div>
                                    </div>

                                </div>


                                <div class="form-group">
                                    <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
                                    <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
                                </div>

                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 m-b-10">

                                <div class="widget-8 panel no-border bg-success no-margin widget-loader-bar">
                                    <div class="container-xs-height full-height">
                                        <div class="row-xs-height">
                                            <div class="col-xs-height col-top">
                                                <div class="panel-heading top-left top-right">
                                                    <div class="panel-title text-black hint-text">
                                                        <span class="font-montserrat fs-11 all-caps">Restaurant <i class="fa fa-chevron-right"></i>
                                                        </span>
                                                    </div>
                                                    <div class="panel-controls">
                                                        <ul>
                                                            <li>
                                                                <a data-toggle="refresh" class="portlet-refresh text-black" href="#"><i class="portlet-icon portlet-icon-refresh"></i></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row-xs-height ">
                                            <div class="col-xs-height col-top relative">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="p-l-20">
                                                            <h3 class="no-margin p-b-5 text-white"><?php echo $restCount; ?></h3>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-4 m-b-10">

                                <div class="widget-9 panel no-border bg-primary no-margin widget-loader-bar">
                                    <div class="container-xs-height full-height">
                                        <div class="row-xs-height">
                                            <div class="col-xs-height col-top">
                                                <div class="panel-heading  top-left top-right">
                                                    <div class="panel-title text-black">
                                                        <span class="font-montserrat fs-11 all-caps">Orders <i class="fa fa-chevron-right"></i>
                                                        </span>
                                                    </div>
                                                    <div class="panel-controls">
                                                        <ul>
                                                            <li><a href="#" class="portlet-refresh text-black" data-toggle="refresh"><i class="portlet-icon portlet-icon-refresh"></i></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row-xs-height">
                                            <div class="col-xs-height col-top">
                                                <div class="p-l-20 p-t-15">
                                                    <h3 class="no-margin p-b-5 text-white"><?php echo $orderCount; ?></h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-4 m-b-10">

                                <div class="widget-10 panel no-border bg-white no-margin widget-loader-bar">
                                    <div class="container-xs-height">
                                    <div class="panel-heading top-left top-right ">
                                        <div class="panel-title text-black hint-text">
                                            <span class="font-montserrat fs-11 all-caps">Customer Enrolled <i class="fa fa-chevron-right"></i>
                                            </span>
                                        </div>
                                        <div class="panel-controls">
                                            <ul>
                                                <li><a data-toggle="refresh" class="portlet-refresh text-black" href="#"><i class="portlet-icon portlet-icon-refresh"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="panel-body p-t-40">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <h4 class="no-margin p-b-5 text-danger semi-bold"><?php echo $custCount; ?></h4>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 m-b-10">

                                <div class="widget-8 panel no-border bg-success no-margin widget-loader-bar">
                                    <div class="container-xs-height full-height">
                                        <div class="row-xs-height">
                                            <div class="col-xs-height col-top">
                                                <div class="panel-heading top-left top-right">
                                                    <div class="panel-title text-black hint-text">
                                                        <span class="font-montserrat fs-11 all-caps">Total order amount <i class="fa fa-chevron-right"></i>
                                                        </span>
                                                    </div>
                                                    <div class="panel-controls">
                                                        <ul>
                                                            <li>
                                                                <a data-toggle="refresh" class="portlet-refresh text-black" href="#"><i class="portlet-icon portlet-icon-refresh"></i></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row-xs-height ">
                                            <div class="col-xs-height col-top relative">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="p-l-20">
                                                            <h3 class="no-margin p-b-5 text-white"><span class="rupyaINR">Rs</span> <?php echo $orderAmount; ?></h3>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-4 m-b-10">

                                <div class="widget-9 panel no-border bg-primary no-margin widget-loader-bar">
                                    <div class="container-xs-height full-height">
                                        <div class="row-xs-height">
                                            <div class="col-xs-height col-top">
                                                <div class="panel-heading  top-left top-right">
                                                    <div class="panel-title text-black">
                                                        <span class="font-montserrat fs-11 all-caps">Average order amount <i class="fa fa-chevron-right"></i>
                                                        </span>
                                                    </div>
                                                    <div class="panel-controls">
                                                        <ul>
                                                            <li><a href="#" class="portlet-refresh text-black" data-toggle="refresh"><i class="portlet-icon portlet-icon-refresh"></i></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row-xs-height">
                                            <div class="col-xs-height col-top">
                                                <div class="p-l-20 p-t-15">
                                                    <h3 class="no-margin p-b-5 text-white"><span class="rupyaINR">Rs</span> <?php echo $orderAvg; ?></h3>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                            <div class="col-md-4">

                                <div class="widget-10 panel no-border bg-white no-margin widget-loader-bar">
                                <div class="container-xs-height">
                                    <div class="panel-heading top-left top-right ">
                                        <div class="panel-title text-black hint-text">
                                            <span class="font-montserrat fs-11 all-caps">KhayeJao income <i class="fa fa-chevron-right"></i>
                                            </span>
                                        </div>
                                        <div class="panel-controls">
                                            <ul>
                                                <li><a data-toggle="refresh" class="portlet-refresh text-black" href="#"><i class="portlet-icon portlet-icon-refresh"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="panel-body p-t-40">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <h4 class="no-margin p-b-5 text-danger semi-bold"><span class="rupyaINR">Rs</span> <?php echo round($kIncome, 2); ?></h4>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">

                                <div class="panel panel-transparent">
                                    <div class="panel-heading">
                                        <div class="panel-title">Monthly Revenue
                                        </div>
                                        <div class="tools">
                                            <a class="collapse" href="javascript:;"></a>
                                            <a class="config" data-toggle="modal" href="#grid-config"></a>
                                            <a class="reload" href="javascript:;"></a>
                                            <a class="remove" href="javascript:;"></a>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-condensed" id="condensedTable">
                                                <thead>
                                                    <tr>
                                                        <th style="width:30%">Restaurant Title</th>
                                                        <th style="width:30%">Total Order</th>
                                                        <th style="width:40%">Grand Total</th>
                                                        <th style="width:40%">Khayejao Income</th>
                                                        <th style="width:40%">Khayejao Share</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($dataProvider as $row) { ?>
                                                        <tr>
                                                            <td class="v-align-middle semi-bold"><?php echo $row['Restaurent']; ?></td>
                                                            <td class="v-align-middle"><?php echo $row['Orders']; ?></td>
                                                            <td class="v-align-middle semi-bold"><span class="rupyaINR">Rs</span> <?php echo $row['Total']; ?></td>
                                                            <td class="v-align-middle semi-bold"><span class="rupyaINR">Rs</span> <?php echo round($row['Income'], 2); ?></td>
                                                            <td class="v-align-middle semi-bold"><?php echo $row['Per']; ?> %</td>
                                                        </tr>
                                                    <?php } ?>


                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 
<?php $this->registerJs($this->render('index_js'), \yii\web\VIEW::POS_END); ?> 
<?php $this->registerCss(".ui-datepicker-calendar {
    display: none;
}​ 
 "); 

 ?>
