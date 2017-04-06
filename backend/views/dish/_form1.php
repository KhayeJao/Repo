<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use unclead\widgets\MultipleInput;
/**
 * @var yii\web\View $this
 * @var common\models\Dish $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="dish-form">
	<?php if(\Yii::$app->getSession()->hasFlash('success')):?>
    <div class="alert alert-success">
        <?php echo \Yii::$app->getSession()->getFlash('success'); ?>
    </div>
<?php endif; ?>

    <?php
    $form = ActiveForm::begin([
                'id' => 'Dish',
                'layout' => 'horizontal',
                'enableClientValidation' => TRUE, 
				'enableClientValidation'    => false,
				'validateOnChange'          => false,
				'validateOnSubmit'          => true,
				'validateOnBlur'            => false,
                    ]
    );
    ?>

    <div class="">
        <?php echo $form->errorSummary($model); ?>
        <?php $this->beginBlock('main'); ?> 
       
			<?= $form->field($model, 'restaurant_id')->hiddenInput()->label(FALSE) ?> 
 
            <?= $form->field($model, 'dish')->widget(MultipleInput::className(), [
      'limit' => 10,
      'columns' => [ [
            'name'  => 'menu_id',
            'type'  => 'dropDownList',
            'title' => 'Select menu',
            'options' => ['placeholder' => 'Select a meun ...'], 
            'defaultValue' => 1,
            'items' => ArrayHelper::map(\common\models\base\Menu::findAll(['restaurant_id' => $_GET['Dish']['restaurant_id']]), 'id', 'title'),
        ],
        [
            'name'  => 'title',
            'title' => 'title',
            'enableError' => true,
            
            'options' => [
                'class' => 'input-priority',
                 'required' =>true,
            ]
        ],
        [
            'name'  => 'description',
            'title' => 'description',
            
            'type'  => 'textArea',
            'enableError' => true,
            'options' => [
                'class' => 'input-priority',
                 
            ]
        ],
        [
            'name'  => 'ingredients',
            'title' => 'ingredients',
            'enableError' => true,
            'options' => [
                'class' => 'input-priority',
                  
            ]
        ], 
        
        [
            'name'  => 'price',
            'title' => 'price', 
            'enableError' => true, 
            'options' => [
                'class' => 'input-priority',
                 'required' =>true,
                'type' => 'number'
            ]
        ],
        [
            'name'  => 'status',
            'title' => 'status',
            'type'  => 'dropDownList',
            'enableError' => true,
            'items' => [
                'Active' => 'Active',
                'Inactive'=> 'Inactive'
            ],
            'options' => [
                'class' => 'input-priority',
                 'required' =>true,
            ]
        ]
    ]
 ]);
/* 
 if(count($title)>0){
	 print_r($title);
	 print_r($menu_name);die;
	 
 } */
?>
  
        <?php $this->endBlock(); ?>

        <?=
        Tabs::widget(
                [
                    'encodeLabels' => false,
                    'items' => [ [
                            'label' => 'Dish',
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

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Select Dish From Master Table</h4>
      </div>
      <div class="modal-body">
	   <?php
    $form = ActiveForm::begin([
                'id' => 'Dish',
                'layout' => 'horizontal',
                'enableClientValidation' => TRUE, 
				'enableClientValidation'    => false,
				'validateOnChange'          => false,
				'validateOnSubmit'          => true,
				'validateOnBlur'            => false,
                    ]
    );
	
    ?>
	
        <p><?php
		echo Html::hiddenInput('master', 'master');
		
		echo Select2::widget([
                'name' => 'menu-id',
                'id' => 'id',
                'data' => ArrayHelper::map(\common\models\base\Menu::findAll(['restaurant_id' => $_GET['Dish']['restaurant_id']]), 'title', 'title'), 
                'options' => [
                    'placeholder' => 'Select Menu', 'required' => 'required','id'=>'menu-id',
                    'multiple' => false,
                ],
            ]);
			?></p>
			<p> </p>
			<p>
			<?php
        echo Select2::widget([
                'name' => 'title',
                'id' => 'id',
                'data' => ArrayHelper::map(\common\models\base\DishMaster::findAll(['status' => 'Active']), 'title', 'title'), 
                'options' => [
                    'placeholder' => 'Select Dish', 'required' => 'required','id'=>'menu-dish',
                    'multiple' => true,
                ],
            ]);
            ?>	
			
			 </p>
			
			<p> </p><p> </p>
			 <?=
        Html::submitButton(
                '<span class="glyphicon glyphicon-check"></span> ' . ($model->isNewRecord ? 'Submit' : 'Save'), [
            'id' => 'save-' . $model->formName(),
            'class' => 'btn btn-success'
                ]
        );
        ?>


        <?php ActiveForm::end(); ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
 
<?php $this->registerJs(' $("label[for=dish-dish]").remove();     $(".field-dish-dish .col-sm-6").addClass("col-sm-12"); $(".field-dish-dish .col-sm-12").removeClass("col-sm-6");

 

'); ?>
