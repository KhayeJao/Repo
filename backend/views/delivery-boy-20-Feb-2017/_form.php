<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;
use kartik\widgets\FileInput;

/**
 * @var yii\web\View $this
 * @var common\models\Cuisine $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="cuisine-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'Deliveryboy',
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

            <?= $form->field($model, 'first_name')->textInput(['maxlength' => 150]) ?>
            <?= $form->field($model, 'last_name')->textInput(['maxlength' => 250]) ?>
            <?= $form->field($model, 'email')->textInput(['maxlength' => 150]) ?>
            <?= $form->field($model, 'username')->textInput(['maxlength' => 150]) ?>
            <?= $form->field($model, 'mobile_no')->textInput(['maxlength' => 250]) ?>
            <?= $form->field($model, 'area_id')->dropDownList(yii\helpers\ArrayHelper::map(\common\models\Area::find()->all(), 'id', 'area_name'), ['prompt' => "-- Select Area --"]) ?> 
			  <?php
				echo $form->field($model, 'delivery_time')->dropDownList(['General shift' => 'General shift', 'Morning shift' => 'Morning shift','Evening shift' => 'Evening shift','Night shift' => 'Night shift'],['prompt'=>'Select Option']);
				//license_number
			?>
			 <?= $form->field($model, 'license_number')->textInput(['maxlength' => 150]) ?>
			 
			 
			 
			 
			  <?php if (isset($model->license_image) && !empty($model->license_image)) { ?>
            <div class="form-group field-tblmodules-modstatus required">
                <label for="license-image" class="control-label col-sm-3"></label>
                <div class="col-sm-6">
                    <?php
                    $title = isset($model->license_image) && !empty($model->license_image) ? $model->license_image : '';
                    echo Html::img($model->getImageFUrl(), [
                        'class' => 'img-thumbnail', 
                        'width' => '100'
                    ]);
                    ?>
                </div>

            </div>
            <?php
        }
        echo $form->field($model, 'license_image')->widget(FileInput::classname(), [
            'options' => ['accept' => 'image/*', 'browseClass' => 'btn btn-primary btn-block', 'showCaption' => false, 'showRemove' => FALSE, 'showUpload' => false],
        ]);
        ?>
        
			 
            <?php if (isset($model->profile_pic) && !empty($model->profile_pic)) { ?>
            <div class="form-group field-tblmodules-modstatus required">
                <label for="profile_pic-image" class="control-label col-sm-3"></label>
                <div class="col-sm-6">
                    <?php
                    $title = isset($model->profile_pic) && !empty($model->profile_pic) ? $model->profile_pic : '';
                    echo Html::img($model->getImageUrl(), [
                        'class' => 'img-thumbnail', 
                        'width' => '100'
                    ]);
                    ?>
                </div>

            </div>
            <?php
        }
        echo $form->field($model, 'profile_pic')->widget(FileInput::classname(), [
            'options' => ['accept' => 'image/*', 'browseClass' => 'btn btn-primary btn-block', 'showCaption' => false, 'showRemove' => FALSE, 'showUpload' => false],
        ]);
        ?>
        <?= $form->field($model, 'status')->dropDownList([ '10' => 'Active', '9' => 'Inactive',], ['prompt' => '']) ?>
        
        </p>
        <?php $this->endBlock(); ?>

        <?=
        Tabs::widget(
                [
                    'encodeLabels' => false,
                    'items' => [ [
                            'label' => 'DeliveyBoy',
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
