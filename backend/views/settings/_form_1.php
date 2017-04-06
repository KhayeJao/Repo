<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;
use kartik\widgets\FileInput;
use mihaildev\ckeditor\CKEditor;

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
                    <?php if (isset($model->value) && !empty($model->value)) { ?>
                    <div class="form-group field-tblmodules-modstatus required">
                        <label for="restaurant-value" class="control-label col-sm-3"></label>
                        <div class="col-sm-6">
                            <?php
                            $title = isset($model->value) && !empty($model->value) ? $model->value : '';
                            echo Html::img($model->getImageUrl(), [
                                'class' => 'img-thumbnail',
                                'alt' => $title,
                                'title' => $title,
                                'width' => '100',
                            ]);
                            ?>
                        </div>

                    </div>
                    <?php
                }
                echo $form->field($model, 'value')->widget(FileInput::classname(), [
                    'options' => ['accept' => 'image/*', 'browseClass' => 'btn btn-primary btn-block', 'showCaption' => false, 'showRemove' => FALSE, 'showUpload' => false],
                ])->label("Background Image");
                ?>
                <?php
                break;
            case 2:
                ?>
                <?php
                echo $form->field($model, 'value')->widget(CKEditor::className(), [
                    'editorOptions' => [
                        'allowedContent' => true,
                        'preset' => 'full', //разработанны стандартные настройки basic, standard, full данную возможность не обязательно использовать
                        'inline' => false, //по умолчанию false
                    ],
                ]);
                ?>

                <?php
                break;
            case 3:
                ?>
                <?= $form->field($model, 'value')->textInput(['maxlength' => 250]) ?>
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
