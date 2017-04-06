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

use kartik\widgets\DatePicker;

use kartik\datetime\DateTimePicker;

use yii\bootstrap\Modal;

//echo '<pre>';

//print_r($_SESSION['cart']); exit;

/**

 * @var yii\web\View $this

 * @var common\models\Order $model

 */

$this->title = 'Checkout Order';

$this->params['breadcrumbs'][] = ['label' => 'Menu list', 'url' => ['tableorder/placeorder']];

$this->params['breadcrumbs'][] = 'Checkout';

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



        <div class="col-md-8">

            <?php

            $form = ActiveForm::begin([

                        'id' => 'checkout_form',

                        'layout' => 'horizontal',

                        'enableClientValidation' => TRUE,

                        'action' => Url::to(['tableorder/checkoutpricess/'])

                            ]

            );

            ?>



            <div class="">

                <?php echo $form->errorSummary($model); ?> 

                <p> 

                   

                    <?= $form->field($model, 'restaurant_id')->hiddenInput(['value' => $restaurant_model->id])->label(FALSE); ?>

                   <?php  

                        echo $form->field($model, 'mobile')->textInput(['maxlength' => 15]); 

                    ?> 

                    <?= $form->field($model, 'email')->textInput(['maxlength' => 400, 'email','value' => ('')]) ?>

                   

                     <?= $form->field($model, 'user_full_name')->textInput(['maxlength' => 400, 'value' => ('')]) ?>

                   <?php
/*
					   echo $form->field($model, 'dob')->widget(DatePicker::className([

								 'name' => 'dp_1',

								'type' => DatePicker::TYPE_COMPONENT_PREPEND,

								'value' => '23-Feb-1982 ',

								'pluginOptions' => [

									'autoclose' => true,

									'format' => 'dd-M-yyyy'

								]

					   ]));
*/
                   ?>

                    <?php /*

					   echo $form->field($model, 'annversary_date')->widget(DatePicker::className([

								 'name' => 'dp_2',

								'type' => DatePicker::TYPE_COMPONENT_PREPEND,

								'value' => '23-Feb-1982 ',

								'pluginOptions' => [

									'autoclose' => true,

									'format' => 'dd-M-yyyy'

								]

					   ]));
*/
                   ?>
                   
                   
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
					<?= $form->field($model, 'comment')->hiddenInput(['value' =>'test'])->label(FALSE); ?>
                   <? //$form->field($model, 'comment')->textarea(['maxlength' => 700]) ?> 

                </p>

            </div> 


<div class="text-center">
            <?=

            Html::submitButton(

                    'Order', [

                'id' => 'save-' . $model->formName(),

                'class' => 'btn btn-success'

                    ]

            );

            ?> 
</div>
            <?php ActiveForm::end(); ?>



        </div>

        <div class="col-md-4" id="order_container">

            <div class="col-md-12 col-sm-12 col-xs-12 gutter">

                <h2 class="list-view-fake-header">Shopping Cart <a href="javascript:void(0);" title="Empty Cart" class="empty_cart pull-right"><span class="fa fa-trash fa-2x"></span></a></h2>

               

            </div>

            <div id="cart_div" class="col-md-12 col-sm-12 col-xs-12">



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

?>



<div class="">

    

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
  
 
<?php //$this->registerJs('var user_id = '.($user_model ? $user_model->id : 0).'; var restaurant_id = '.$restaurant_model->id.';', \yii\web\VIEW::POS_END); ?>
 <?php 
    $this->registerJs($this->render('check_out_js'), \yii\web\VIEW::POS_LOAD);

 ?>

<?php $this->registerJsFile(Yii::$app->request->baseUrl . '/js/jquery-dateFormat.js', ['depends' => [\yii\web\JqueryAsset::className()]]); ?>

<?php $this->registerCss("#save-Order {

    background: #f43e11;

    border: 0px;

    color: #fff;

    height: auto;

    border-radius: 5px; 

    font-size: 13px;

    margin: 15px 0;

    white-space: inherit;

}
#checkout_form{
margin-right: 27px;
}


#save-Order:hover{background:#8bbc00;}
.form-horizontal .form-group{ background-color: #e6e6e6; } .form-horizontal .form-group .control-label{  opacity: 8.42;} #finale_print{display:block}#kitchen_print,#save_button{display:none}#checkout_button{display:none} #save_button.btn-lg.btn{display:none} .table.table-condensed thead tr th, .table.table-condensed tbody tr td, .table.table-condensed tbody tr td *{white-space:normal} .sub-tr td{border-bottom : none !important; padding:0 20px !important;}"); ?>

<?php

//if ($user_model) {

  //  $this->registerJs($this->render('address_js'), \yii\web\VIEW::POS_END);

//}

?>

