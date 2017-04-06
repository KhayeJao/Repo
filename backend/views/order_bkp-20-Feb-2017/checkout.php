<?php

use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use dmstr\bootstrap\Tabs;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use yii\bootstrap\Modal;
//echo '<pre>';
//print_r($_SESSION['cart']); exit;
/**
 * @var yii\web\View $this
 * @var common\models\Order $model
 */
$this->title = 'Checkout Order';
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Checkout';

$user_type= \Yii::$app->session['user_type'];  
 if($user_type=='L'){
			
			 $user_model->id='';
		}
?>
<div class="order-view">
    <div class="col-md-12">
        <?php if (Yii::$app->session->getFlash('error')) { ?>
            <div class="alert alert-danger" role="alert">
                <button class="close" data-dismiss="alert"></button>
                <strong>Error: </strong><?= Yii::$app->session->getFlash('error') ?>
            </div>
        <?php }
        ?>

        <div class="col-md-8 pad">
            <?php
            $form = ActiveForm::begin([
                        'id' => 'checkout_form',
                        'layout' => 'horizontal',
                        'enableClientValidation' => TRUE,
                        'action' => Url::to(['order/checkoutpricess/'])
                            ]
            );
            ?>

            <div class="">
                <?php echo $form->errorSummary($model); ?>

                <p>

                    <?= $form->field($model, 'user_id')->hiddenInput(['value' => ($user_model ? $user_model->id : '')])->label(FALSE) ?>
                    <?= $form->field($model, 'restaurant_id')->hiddenInput(['value' => $restaurant_model->id])->label(FALSE); ?>
                    <?= $form->field($model, 'user_full_name')->textInput(['maxlength' => 400, 'value' => ($user_model ? $user_model->first_name . ' ' . $user_model->last_name : '')]) ?>
                    <?php if ($user_model) { ?>
                    <div class = "form-group required">
                        <label class = "control-label col-sm-3" for = "order-email">Mobile No</label>
                        <div class = "col-sm-6">
                            <?= $user_model->mobile_no ?>
                            <?php echo $form->field($model, 'mobile')->hiddenInput(['value' => $user_model->mobile_no])->label(FALSE); ?>
                        </div>
                    </div>
                    <?php
                } else {
                    echo $form->field($model, 'mobile')->textInput(['maxlength' => 15]);
                }
                ?>
                <?php if ($user_model) { ?>
                    <div class = "form-group required">
                        <label class = "control-label col-sm-3" for = "order-email">Email</label>
                        <div class = "col-sm-6">
                            <?= $user_model->email ?>
                            <?php echo $form->field($model, 'email')->hiddenInput(['value' => $user_model->email])->label(FALSE); ?>
                        </div>
                    </div>
                    <?php
                } else {
                    echo $form->field($model, 'email')->textInput(['maxlength' => 255]);
                }
                ?>
                <?php if (!$user_model) { ?>
                    <?= $form->field($model, 'address_line_1')->textInput(['maxlength' => 100]) ?>
                    <?= $form->field($model, 'address_line_2')->textInput(['maxlength' => 100]) ?>
                    <?php
                    $restaurant_area_ids = ArrayHelper::getColumn(common\models\RestaurantArea::findAll(['restaurant_id' => $restaurant_model->id]), 'area_id');
                    $areas_query = common\models\Area::find();
                    $restaurant_areas = $areas_query->where(['IN', 'id', ArrayHelper::getColumn(common\models\RestaurantArea::findAll(['restaurant_id' => $restaurant_model->id]), 'area_id')])
                    ->orderBy([
								   'area_name'=>SORT_ASC, 
								])->all();
                    echo $form->field($model, 'area')->widget(Select2::classname(), [
                        'data' => yii\helpers\ArrayHelper::map($restaurant_areas, 'id', 'area_name'),
                        'options' => ['placeholder' => 'Select an area...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                    ?>
                    
                    <?= $form->field($model, 'city')->textInput(['maxlength' => 50]) ?>
                    <?= $form->field($model, 'pincode')->textInput(['maxlength' => 50]) ?>
                <?php } else {
                    ?>
                    <div class="hidden">
                        <?php
                        echo Html::hiddenInput('address_id', '', ['id' => 'address_id']);
                        ?>
                    </div>
                <?php }
                ?>
                <?php if ($user_model) { ?>
                    <div id="addresses_container">
                        <h4>User Addresses</h4>

                        <div id="addresses_div">

                        </div>
                    </div>
                <?php } ?>



                <?php
                echo $form->field($model, 'delivery_type')->dropDownList(['Delivery' => 'Delivery', 'Pickup' => 'Pickup'], ['class' => 'form-control']);
//                echo $form->field($model, 'delivery_type')->inline()->radioList([
//                    'Delivery' => 'Delivery',
//                    'Pickup' => 'Pickup'], ['id' => 'delivery_type_id']);
                ?>


                <div class="form-group field-order-delivery_time required">
                    <label class="control-label col-sm-3" for="delivery_time">Delivery Time</label>
                    <div class="col-sm-6">
                        <select id="delivery_time_radio" class="form-control" name="delivery_time_radio">
                            <option value="Now" <?= ($restaurant_model->isOpen() ? '' : 'disabled') ?> selected="">Now</option>
                            <option value="Pre-Order" <?= ($restaurant_model->isOpen() ? '' : 'selected') ?>>Pre-Order</option>
                        </select>
                        <?php if (!$restaurant_model->isOpen()) { ?>
                            <label class=" m-t-10">Restaurant is closed now! You can Pre-Order</label>
                        <?php }
                        ?>

                        <div style="display: none" id="delivery_time_input_div" class="row m-t-10" >
                            <div class="col-md-12">
                                <label class="control-label col-sm-3" for="delivery_time">Delivery Date</label>
                                <?php
                                echo DatePicker::widget([
                                    'name' => 'delivery_date_input',
                                    'id' => 'delivery_date_input',
                                    'options' => ['placeholder' => 'Enter order date ...'],
                                    'pluginOptions' => [
                                        'todayHighlight' => true,
                                        'todayBtn' => true,
                                        'format' => 'dd-M-yyyy',
                                        'autoclose' => true,
                                        'startDate' => 'd',
                                        'endDate' => '+1d'
                                    ]
                                ]); ?>
                                
                           
                            </div>
                            <div class="col-md-12 m-t-10">
                                <label class="control-label col-sm-3" for="delivery_time">Delivery Time</label>
                                <select id="delivery_time_input" name="delivery_time_input" class="form-control col-sm-9">
                                    <?php for ($time = strtotime($restaurant_model->open_datetime_1); $time <= strtotime($restaurant_model->close_datetime_1); $time += 1800) { ?>
                                        <option value="<?= date('H:i', $time) ?>" <?= ($time > strtotime(date('H:i:s')) ? '' : 'disabled') ?> <?= ($time > strtotime(date('H:i:s')) ? '' : 'date-disabled="disabled"') ?>><?= date('H:i', $time) ?></option>
                                        <?php
                                    }
                                    for ($time = strtotime($restaurant_model->open_datetime_2); $time <= strtotime($restaurant_model->close_datetime_2); $time += 1800) {
                                        ?>
                                        <option value="<?= date('H:i', $time) ?>" <?= ($time > strtotime(date('H:i:s')) ? '' : 'disabled') ?> <?= ($time > strtotime(date('H:i:s')) ? '' : 'date-disabled="disabled"') ?>><?= date('H:i', $time) ?></option>
                                        <?php
                                    }
                                    ?>

                                </select>
                            </div>

                        </div>
                    </div>

                </div>
                <?= $form->field($model, 'comment')->textarea(['maxlength' => 700]) ?>
                  
                    <?=
                    $form->field($model, 'order_to')
                        ->radioList(
                            ['restaurant' => 'Restaurant','khayejao' => 'khayejao'],
                            [
                                'item' => function($index, $label, $name, $checked, $value) { 
									
                                    $return = '<label class="modal-radio">';
                                    $return .= '<input type="radio" id="'.$value.'" name="' . $name . '" value="' . $value . '" tabindex="3">';
                                    $return .= '<i></i>';
                                    $return .= '<span>' . ucwords($label) . '</span>';
                                    $return .= '</label>';

                                    return $return;
                                }
                            ]
                        )
                    ->label(true);
                    ?>
                    
                </p>
            </div>
           

            <?=
            Html::submitButton(
                    '<span class="glyphicon glyphicon-check"></span> Order', [
                'id' => 'save-' . $model->formName(),
                'class' => 'btn btn-success'
                    ]
            );
            ?>

            <?php ActiveForm::end(); ?>

        </div>
        <div class="col-md-4" id="order_container">
            <div class="col-md-12">
                <h2 class="list-view-fake-header">Shopping Cart <a href="javascript:void(0);" title="Empty Cart" class="empty_cart pull-right"><span class="fa fa-trash fa-2x"></span></a></h2>
                
            </div>
            <div id="cart_div" class="col-md-12">

            </div>

        </div>
    </div>
</div>
<?php
Modal::begin([
    'header' => '<h2>Add Address</h2>',
    'toggleButton' => ['label' => '<span class="fa fa-plus"></span>Select topping', 'class' => 'hidden btn btn-success'],
    'id' => 'model_add_address',
]);
?>
<?php 
 
$form = ActiveForm::begin([
            'id' => 'address_form',
            'layout' => 'horizontal',
            'enableClientValidation' => TRUE,
                ]
);

$user_type= \Yii::$app->session['user_type'];  
 
?>

<div class="">
    <?php echo $form->errorSummary($addressModel); ?>
    <p>
<?php if($user_type=='L') {  $user_model->id=$_GET['user_id']; }?>
        <?= $form->field($addressModel, 'user_id')->hiddenInput(['value' => ($user_model ? $user_model->id : 0)])->label(FALSE); ?>
       
        <?= $form->field($addressModel, 'address_line_1')->textInput(['maxlength' => true]) ?>
        <?= $form->field($addressModel, 'address_line_2')->textInput(['maxlength' => true]) ?>
        <?php
        echo $form->field($addressModel, 'area')->widget(Select2::classname(), [
            'data' => yii\helpers\ArrayHelper::map(\common\models\base\Area::find()->all(), 'id', 'area_name'),
            'options' => ['placeholder' => 'Select an area...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
        ?>
        <?= $form->field($addressModel, 'city')->textInput(['readonly' => 'true', 'value' => 'Admedabad']) ?>
        <?= $form->field($addressModel, 'pincode')->textInput() ?>
    </p>

    <hr/>

    <?=
    Html::submitButton(
            '<span class="glyphicon glyphicon-check"></span> Add', [
        'id' => 'save-' . $model->formName(),
        'class' => 'btn btn-success'
            ]
    );
    ?>


    <?php ActiveForm::end(); ?>

</div>

<?php
Modal::end();
?>
<?php $this->registerJs('var user_id = ' . ($user_model ? $user_model->id : 0) . '; var restaurant_id = ' . $restaurant_model->id . '; var is_restaurant_open = ' . ($restaurant_model->isOpen() ? 1 : 0) . '; $("#restaurant").prop("checked", true);', \yii\web\VIEW::POS_END); ?>
<?php if ($user_model) {
    $this->registerJs($this->render('check_out_js'), \yii\web\VIEW::POS_END);
}else{
    $this->registerJs($this->render('check_out_js'), \yii\web\VIEW::POS_LOAD);
} ?>
<?php $this->registerJsFile(Yii::$app->request->baseUrl . '/js/jquery-dateFormat.js', ['depends' => [\yii\web\JqueryAsset::className()]]); ?>
<?php $this->registerCss("#checkout_button{display:none} .table.table-condensed thead tr th, .table.table-condensed tbody tr td, .table.table-condensed tbody tr td *{white-space:normal} .sub-tr td{border-bottom : none !important; padding:0 20px !important;}.pad{padding-left:10px; padding-right:25px;}"); ?>
<?php
if ($user_model) {
    $this->registerJs($this->render('address_js'), \yii\web\VIEW::POS_END);
}
?>
