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
        <?php $this->beginBlock('main');  
		?>  
                <?= $form->field($model, 'title')->widget(MultipleInput::className(), [
      'limit' => 20,
      'columns' => [  
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


<?php $this->registerJs(' $("label[for=dish-dish]").remove();     $(".field-dish-dish .col-sm-6").addClass("col-sm-12"); $(".field-dish-dish .col-sm-12").removeClass("col-sm-6");'); ?>
