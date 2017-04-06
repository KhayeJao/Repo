<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;
use kartik\widgets\FileInput;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;

 
/**
 * @var yii\web\View $this
 * @var common\models\Cuisine $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="cuisine-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'homeslider',
                'layout' => 'horizontal',
                'enableClientValidation' => false,
                'options' => ['enctype' => 'multipart/form-data'],
                    ]
    );
    ?>

    <div class="">
        <?php echo $form->errorSummary($model); ?>
        <?php $this->beginBlock('main'); ?>

        <p>
			 
       
            <?php
               echo $form->field($model, 'restaurant_id')->widget(Select2::classname(),[
                    'name' => 'restaurant_id',
                    'data' => ArrayHelper::map(\common\models\base\Restaurant::findAll(['status' => 'Active']), 'id', 'title'),
                    'options' => [
                        'placeholder' => 'Select Restaurant..',
                        'id' => 'restaurant_id'
                    ],
                ]);
                ?>
            <?= $form->field($model, 'title',['options' => ['value'=> 'your value','id'=>'title'] ])->hiddenInput()->label(false);?> 
            
            <?= $form->field($model, 'description')->textInput(['maxlength' => 250]) ?>
            <?php if (isset($model->image) && !empty($model->image)) { ?>
            <div class="form-group field-tblmodules-modstatus required">
                <label for="restaurant-image" class="control-label col-sm-3"></label>
                <div class="col-sm-6">
                    <?php
                    $title = isset($model->image) && !empty($model->image) ? $model->image : '';
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
        }
        echo $form->field($model, 'image')->widget(FileInput::classname(), [
            'options' => ['accept' => 'image/*', 'browseClass' => 'btn btn-primary btn-block', 'showCaption' => false, 'showRemove' => FALSE, 'showUpload' => false],
        ]);
        ?>
        <?= $form->field($model, 'status')->dropDownList([ 'Active' => 'Active', 'Inactive' => 'Inactive']) ?>
       
      
        </p>
        <?php $this->endBlock(); ?>

        <?=
        Tabs::widget(
                [
                    'encodeLabels' => false,
                    'items' => [ [
                            'label' => 'Slider',
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
 
