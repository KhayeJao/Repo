<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;
use kartik\widgets\FileInput;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;

use kartik\widgets\TimePicker;
use kartik\datecontrol\DateControl;
use kartik\widgets\DatePicker;

use kartik\datetime\DateTimePicker;

/**
 * @var yii\web\View $this
 * @var common\models\Restaurant $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="restaurant-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'Restaurant',
                'layout' => 'horizontal',
                'enableClientValidation' => TRUE,
                'options' => ['enctype' => 'multipart/form-data']
                    ]
    );
    ?>

    <div class="">
        <?php echo $form->errorSummary($model); ?>
        <?php $this->beginBlock('main'); ?>

        <p>

            <?php // $form->field($model, 'user_id')->textInput() ?>
            <?php // echo "<pre>"; print_r(ArrayHelper::map(\common\models\base\User::findAll(['type' => 'Restaurant']), 'id', 'username')); exit; ?>
            <?php
            echo $form->field($model, 'user_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(\common\models\base\User::findAll(['type' => 'Restaurant']), 'id', 'username'),
                'options' => ['placeholder' => 'Select a user ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>

            <?= $form->field($model, 'title')->textInput(['maxlength' => 200]) ?>
            <?= $form->field($model, 'slogan')->textInput(['maxlength' => 300]) ?>
            <?= $form->field($model, 'address')->textInput(['maxlength' => 500]) ?>
            <?= $form->field($model, 'area')->textInput(['maxlength' => 50]) ?>
            <?= $form->field($model, 'city')->textInput(['maxlength' => 50]) ?>
            <?= $form->field($model, 'sms_number')->textInput(['maxlength' => 15]) ?>
            <?= $form->field($model, 'order_number')->textInput(['maxlength' => 255]) ?>
            <?= $form->field($model, 'latitude')->textInput(['maxlength' => 50]) ?>
            <?= $form->field($model, 'longitude')->textInput(['maxlength' => 50]) ?>
            <input id="pac-input" class="controls" type="text" placeholder="Search Box">
        <div id="map-canvas"></div>
        <?= $form->field($model, 'min_amount')->textInput() ?>
        <?php /* FILE INPUT STARTS */ ?>
        <?php // $form->field($model, 'logo')->fi(['maxlength' => 200]) ?>
        <?php if (isset($model->logo) && !empty($model->logo)) { ?>
            <div class="form-group field-tblmodules-modstatus required">
                <label for="restaurant-logo" class="control-label col-sm-3"></label>
                <div class="col-sm-6">
                    <?php
                    $title = isset($model->logo) && !empty($model->logo) ? $model->logo : '';
                    echo Html::img($model->getImageUrl(), [
                        'class' => 'img-thumbnail',
                        'alt' => $title,
                        'title' => $title,
                        'width' => '100'
                    ]);
                    ?>
                </div>

            </div>
            <?php
        } ?>
         <?php
        echo $form->field($model, 'logo')->widget(FileInput::classname(), [
            'options' => ['accept' => 'image/*', 'browseClass' => 'btn btn-primary btn-block', 'showCaption' => false, 'showRemove' => FALSE, 'showUpload' => false],
        ]);
        ?>
        
    <?php if (isset($model->advertise) && !empty($model->advertise)) { ?>
            <div class="form-group field-tblmodules-modstatus required">
                <label for="restaurant-logo" class="control-label col-sm-3"></label>
                <div class="col-sm-6">
                    <?php
                    $title = isset($model->advertise) && !empty($model->advertise) ? $model->advertise : '';
                    echo Html::img($model->getImageAUrl(), [
                        'class' => 'img-thumbnail',
                        'alt' => $title,
                        'title' => $title,
                        'width' => '100'
                    ]);
                    ?>
                     <?= Html::a('Delete', ['imagedelete', 'id' => $model->id], ['class' => 'btn btn-danger']) ?>
                </div>

            </div>
  <?php }?>
   <?php
        echo $form->field($model, 'advertise')->widget(FileInput::classname(), [
            'options' => ['accept' => 'image/*', 'browseClass' => 'btn btn-primary btn-block', 'showCaption' => false, 'showRemove' => FALSE, 'showUpload' => false],
        ]);
    ?>
       

        <?php /* FILE INPUT ENDS */ ?>
        <?= $form->field($model, 'delivery_network')->textInput()->label('Delivery (in Km)') ?>
        <?= $form->field($model, 'delivery_mins')->textInput()->label('Delivery time (in Minuits)') ?>
        <?= $form->field($model, 'food_type')->dropDownList([ 'Vegetarian' => 'Vegetarian', 'Non Vegetarian' => 'Non Vegetarian', 'Vegetarian,Non Vegetarian' => 'Both'], ['prompt' => '']) ?>
        <?php //$form->field($model, 'open_datetime_1')->textInput(['maxlength' => 15]) ?>
        <?=
//        $form->field($model, 'open_datetime_1')->widget(DateControl::classname(), [
//            'type' => DateControl::FORMAT_TIME
//        ]);
//        $form->field($model, 'open_datetime_1')->widget(DateControl::classname(), [
//            'type' => DateControl::FORMAT_TIME
//        ]);
        $form->field($model, 'open_datetime_1')->widget(TimePicker::classname(), [
            'pluginOptions' => [
                'showSeconds' => FALSE,
                'showMeridian' => false,
                'minuteStep' => 1,
                'minuteStep' => 15,
                'secondStep' => 5,
        ]]);
        ?>
        <?=
        $form->field($model, 'close_datetime_1')->widget(TimePicker::classname(), [
            'pluginOptions' => [
                'showSeconds' => FALSE,
                'showMeridian' => false,
                'minuteStep' => 1,
                'minuteStep' => 15,
                'secondStep' => 5,
        ]]);
//        $form->field($model, 'close_datetime_1')->widget(DateControl::classname(), [
//            'type' => DateControl::FORMAT_TIME
//        ]);
        ?>
        <?=
        $form->field($model, 'open_datetime_2')->widget(TimePicker::classname(), [
            'pluginOptions' => [
                'showSeconds' => FALSE,
                'showMeridian' => false,
                'minuteStep' => 1,
                'minuteStep' => 15,
                'secondStep' => 5,
        ]]);
//        $form->field($model, 'open_datetime_2')->widget(DateControl::classname(), [
//            'type' => DateControl::FORMAT_TIME
//        ]);
        ?>
        <?=
        $form->field($model, 'close_datetime_2')->widget(TimePicker::classname(), [
            'pluginOptions' => [
                'showSeconds' => FALSE,
                'showMeridian' => false,
                'minuteStep' => 1,
                'minuteStep' => 15,
                'secondStep' => 5,
        ]]);
//        $form->field($model, 'close_datetime_2')->widget(DateControl::classname(), [
//            'type' => DateControl::FORMAT_TIME
//        ]);
        ?>
        <?= $form->field($model, 'tax')->textInput() ?>
        <?= $form->field($model, 'vat')->textInput() ?>
        <?= $form->field($model, 'service_charge')->textInput() ?>
        <?= $form->field($model, 'scharge_type')->dropDownList([ 'Percentage' => 'Percentage', 'Fixed Amount' => 'Fixed Amount',], ['prompt' => '']) ?>
        <?= $form->field($model, 'kj_share')->textInput() ?>
        <?= $form->field($model, 'does_tablebooking')->dropDownList([ 'Yes' => 'Yes', 'No' => 'No',]) ?>
        <?= $form->field($model, 'table_slot_time')->textInput() ?>
        <?= $form->field($model, 'prior_table_booking_time')->textInput() ?>
        <?= $form->field($model, 'table_booking_close_paddding_time')->textInput()->hint("In Minutes") ?>
        <?= $form->field($model, 'who_delivers')->dropDownList([ 'Restaurant' => 'Restaurant', 'Khayejao' => 'Khayejao',], ['prompt' => '']) ?>
        <?= $form->field($model, 'meta_keywords')->textInput(['maxlength' => 500]) ?>
        <?= $form->field($model, 'meta_description')->textarea(['maxlength' => 700]) ?>
        <?= $form->field($model, 'coupon_text')->textInput(['maxlength' => 200]) ?>
        <?= $form->field($model, 'avg_rating')->textInput(['disabled' => 'disabled']) ?>
        <?= $form->field($model, 'is_featured')->dropDownList(['0' => 'No', '1' => 'Yes']) ?>
        <?php if (isset($model->featured_image) && !empty($model->featured_image)) { ?>
            <div class="form-group field-tblmodules-modstatus required">
                <label for="restaurant-featured_image" class="control-label col-sm-3"></label>
                <div class="col-sm-6">
                    <?php
                    $title = isset($model->featured_image) && !empty($model->featured_image) ? $model->featured_image : '';
                    echo Html::img($model->getImageFUrl(), [
                        'class' => 'img-thumbnail',
                        'alt' => $title,
                        'title' => $title,
                        'width' => '100'
                    ]);
                    ?>
                   
                </div>

            </div>
            <?php
        }
        echo $form->field($model, 'featured_image')->widget(FileInput::classname(), [
            'options' => ['accept' => 'image/*', 'browseClass' => 'btn btn-primary btn-block', 'showCaption' => false, 'showRemove' => FALSE, 'showUpload' => false],
        ]);
        ?>
         <?= $form->field($model, 'is_featured_table')->dropDownList(['0' => 'No', '1' => 'Yes']) ?>
        <?= $form->field($model, 'is_sponserd')->dropDownList(['0' => 'No', '1' => 'Yes']) ?> 
         <?php 
          $type= yii::$app->user->identity->type; 
          
          if($type=='admin'){
         ?> 
       
        <?= $form->field($model, 'logistics')->dropDownList(['0' => 'No', '1' => 'Yes']) ?> 
       
        <?= $form->field($model, 'is_takeorder')->dropDownList(['0' => 'No', '1' => 'Yes']) ?>
        <?= $form->field($model, 'is_sendemail')->dropDownList(['0' => 'No', '1' => 'Yes']) ?>
		<?= $form->field($model, 'is_callcenter')->dropDownList(['0' => 'No', '1' => 'Yes']) ?>
		
		
        <?= $form->field($model, 'is_delivery_boy')->dropDownList(['0' => 'No', '1' => 'Yes']) ?>
		   
	<span id="delivery_boy_validation">	 
		
			<?=$form->field($model, 'delivery_boy_validity')->widget(DateControl::classname(), [
			  'name' => 'delivery_boy_validity',
				'value' => date('Y-m-d'), 
			   'type'=>DateControl::FORMAT_DATE ,
				'pluginOptions' => [ 
                    'autoclose'=>true, 
					 'format' => 'yyyy-dd-MM'
				]
			]);?>
 
		    <?= $form->field($model, 'plan_as')->dropDownList([ 'Per Person' => 'Per Person', 'Monthly' => 'Monthly',], ['prompt' => '']) ?> 
			<?= $form->field($model, 'number_of_delivery_boy')->textInput(['maxlength' => 200]) ?>
			<?= $form->field($model, 'amount')->textInput(['maxlength' => 200]) ?>
			
			<?php if (isset($model->db_app_logo) && !empty($model->db_app_logo)) { ?>
            <div class="form-group field-tblmodules-modstatus required">
                <label for="restaurant-logo" class="control-label col-sm-3"></label>
                <div class="col-sm-6">
                    <?php
                    $title = isset($model->db_app_logo) && !empty($model->db_app_logo) ? $model->db_app_logo : '';
                    echo Html::img($model->getAPPImageUrl(), [
                        'class' => 'img-thumbnail',
                        'alt' => $title,
                        'title' => $title,
                        'width' => '100'
                    ]);
                    ?>
                </div>

            </div>
            <?php
        } ?>
         <?php
        echo $form->field($model, 'db_app_logo')->widget(FileInput::classname(), [
            'options' => ['accept' => 'image/*', 'browseClass' => 'btn btn-primary btn-block', 'showCaption' => false, 'showRemove' => FALSE, 'showUpload' => false],
        ]);
        ?>
			
			
	</span>		
        <?php } ?>
        <?= $form->field($model, 'status')->dropDownList([ 'Active' => 'Active', 'Inactive' => 'Inactive',], ['prompt' => '']) ?>
        </p>
        <?php $this->endBlock(); ?> 
        <?=
        Tabs::widget(
                [
                    'encodeLabels' => false,
                    'items' => [ [
                            'label' => 'Restaurant',
                            'content' => $this->blocks['main'],
                            'active' => true,
                        ],]
                ]
        );
        ?>
        <hr/>

        <?=
        Html::submitButton(
                '<span class="glyphicon glyphicon-check"></span> ' . ($model->isNewRecord ? 'Create' : 'Save'), [
            'id' => 'save-' . $model->formName(),
            'class' => 'btn btn-success'
                ]
        );
        ?>


        <?php ActiveForm::end(); ?>

    </div>
	

</div>
<?php $this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyAHaKOcrpGF45y3UkQwc0lE16RNmO7EtaA'); ?>
<?php
if (!$model->isNewRecord) {
    $this->registerJs("var restaurantLat = '" . $model->latitude . "'; var restaurantLong = '" . $model->longitude . "';", \yii\web\VIEW::POS_END);
}
 

 $this->registerJs(" 
   var select = document.getElementById('restaurant-is_delivery_boy');
   if(select.options[select.selectedIndex].value==='1'){
	   $('#delivery_boy_validation').show(); 
   }else{
	   
	   $('#delivery_boy_validation').hide();
   }
select.onchange = function(){
    var selectedString = select.options[select.selectedIndex].value; 
	if(selectedString==='1'){
	   $('#delivery_boy_validation').show();
   }else{
	   $('#delivery_boy_validation').hide();
   }
}
   //$('#delivery_boy_validity').show();  
	  
   
 
 ", \yii\web\VIEW::POS_END);
?>
<?php $this->registerJs($this->render('_form_map_js'), \yii\web\VIEW::POS_END); ?>
<?php $this->registerCss($this->render('_form_map_css')); ?>

