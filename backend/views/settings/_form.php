<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;
use mihaildev\ckeditor\CKEditor;
use pendalf89\filemanager\widgets\FileInput;
/**
 * @var yii\web\View $this
 * @var common\models\Settings $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<div class="settings-form">
    <?php
    $form = ActiveForm::begin([
                'id' => 'Settings',
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
            switch ($model->id) {
                case 1:
                    ?>
                    <?=
                    $form->field($model, 'value')->widget(FileInput::className(), [
                        'buttonTag' => 'button',
                        'buttonName' => 'Browse',
                        'buttonOptions' => ['class' => 'btn btn-default'],
                        'options' => ['class' => 'form-control'],
                        // Widget template
                        'template' => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
                        // Optional, if set, only this image can be selected by user
                        'thumb' => 'original',
                        // Optional, if set, in container will be inserted selected image
                        'imageContainer' => '.img',
                        // Default to FileInput::DATA_URL. This data will be inserted in input field
                        'pasteData' => FileInput::DATA_URL,
                        // JavaScript function, which will be called before insert file data to input.
                        // Argument data contains file data.
                        // data example: [alt: "Ведьма с кошкой", description: "123", url: "/uploads/2014/12/vedma-100x100.jpeg", id: "45"]
                        'callbackBeforeInsert' => 'function(e, data) {
        console.log( data );
    }',
                    ]);
                    ?>
                    <?php
                    break;
                case 2:
                    ?>
                    <?php
                  /*  echo $form->field($model, 'value')->widget(CKEditor::className(), [
                        'editorOptions' => [
                            'allowedContent' => true,
                            'preset' => 'full',
                            'inline' => false,
                        ],
                    ]);*/

 echo $form->field($model, 'value')->widget(FileInput::className(), [
                        'buttonTag' => 'button',
                        'buttonName' => 'Browse',
                        'buttonOptions' => ['class' => 'btn btn-default'],
                        'options' => ['class' => 'form-control'],
                        // Widget template
                        'template' => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
                        // Optional, if set, only this image can be selected by user
                        'thumb' => 'original',
                        // Optional, if set, in container will be inserted selected image
                        'imageContainer' => '.img',
                        // Default to FileInput::DATA_URL. This data will be inserted in input field
                        'pasteData' => FileInput::DATA_URL,
                        // JavaScript function, which will be called before insert file data to input.
                        // Argument data contains file data.
                        // data example: [alt: "Ведьма с кошкой", description: "123", url: "/uploads/2014/12/vedma-100x100.jpeg", id: "45"]
                        'callbackBeforeInsert' => 'function(e, data) {
        console.log( data );
    }',
                    ]);
                    ?>

                    <?php
                    break;
                case 3:
                    ?>
                   <?=
                    $form->field($model, 'value')->widget(FileInput::className(), [
                        'buttonTag' => 'button',
                        'buttonName' => 'Browse',
                        'buttonOptions' => ['class' => 'btn btn-default'],
                        'options' => ['class' => 'form-control'],
                        // Widget template
                        'template' => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
                        // Optional, if set, only this image can be selected by user
                        'thumb' => 'original',
                        // Optional, if set, in container will be inserted selected image
                        'imageContainer' => '.img',
                        // Default to FileInput::DATA_URL. This data will be inserted in input field
                        'pasteData' => FileInput::DATA_URL,
                        // JavaScript function, which will be called before insert file data to input.
                        // Argument data contains file data.
                        // data example: [alt: "Ведьма с кошкой", description: "123", url: "/uploads/2014/12/vedma-100x100.jpeg", id: "45"]
                        'callbackBeforeInsert' => 'function(e, data) {
        console.log( data );
    }',
                    ]);
                    ?>
                   <?php
                    break; 
                    case 4: 
                    ?>
                   <?=
                    $form->field($model, 'value')->widget(FileInput::className(), [
                        'buttonTag' => 'button',
                        'buttonName' => 'Browse',
                        'buttonOptions' => ['class' => 'btn btn-default'],
                        'options' => ['class' => 'form-control'],
                        // Widget template
                        'template' => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
                        // Optional, if set, only this image can be selected by user
                        'thumb' => 'original',
                        // Optional, if set, in container will be inserted selected image
                        'imageContainer' => '.img',
                        // Default to FileInput::DATA_URL. This data will be inserted in input field
                        'pasteData' => FileInput::DATA_URL,
                        // JavaScript function, which will be called before insert file data to input.
                        // Argument data contains file data.
                        // data example: [alt: "Ведьма с кошкой", description: "123", url: "/uploads/2014/12/vedma-100x100.jpeg", id: "45"]
                        'callbackBeforeInsert' => 'function(e, data) {
        console.log( data );
    }',
                    ]);
                    ?>
                    <?= $form->field($model, 'url')->textInput() ?>
                     <?= $form->field($model, 'status')->dropDownList([ 'Active' => 'Active', 'Inactive' => 'Inactive',], ['prompt' => '']) ?>
                     
                     <?php
                    break; 
                    case 5: 
                    ?>
                   <?=
                    $form->field($model, 'value')->widget(FileInput::className(), [
                        'buttonTag' => 'button',
                        'buttonName' => 'Browse',
                        'buttonOptions' => ['class' => 'btn btn-default'],
                        'options' => ['class' => 'form-control'],
                        // Widget template
                        'template' => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
                        // Optional, if set, only this image can be selected by user
                        'thumb' => 'original',
                        // Optional, if set, in container will be inserted selected image
                        'imageContainer' => '.img',
                        // Default to FileInput::DATA_URL. This data will be inserted in input field
                        'pasteData' => FileInput::DATA_URL,
                        // JavaScript function, which will be called before insert file data to input.
                        // Argument data contains file data.
                        // data example: [alt: "Ведьма с кошкой", description: "123", url: "/uploads/2014/12/vedma-100x100.jpeg", id: "45"]
                        'callbackBeforeInsert' => 'function(e, data) {
        console.log( data );
    }',
                    ]);
                    ?> 
                     <?= $form->field($model, 'title')->textInput() ?>
                     <?= $form->field($model, 'url')->textInput() ?>
                     <?= $form->field($model, 'status')->dropDownList([ 'Active' => 'Active', 'Inactive' => 'Inactive',], ['prompt' => '']) ?>
					 <?= $form->field($model, 'url')->textInput() ?>
                     <?= $form->field($model, 'status')->dropDownList([ 'Active' => 'Active', 'Inactive' => 'Inactive',], ['prompt' => '']) ?>
                     
                     <?php
                    break; 
                    case 6: 
                    ?>
					 <?= $form->field($model, 'title')->textInput() ?>
					 <?= $form->field($model, 'value')->textInput() ?>
					  <?= $form->field($model, 'status')->dropDownList([ 'Active' => 'Active', 'Inactive' => 'Inactive',], ['prompt' => '']) ?>
		    <?php
                    break;
                default:

                    break;
            }
            ?>
        </p>
        <?php $this->endBlock(); ?>
    </div>
    <?=
    Tabs::widget(
            [
                'encodeLabels' => false,
                'items' => [[
                'label' => 'Settings',
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
