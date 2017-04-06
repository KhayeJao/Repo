<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;
use kartik\widgets\FileInput;

/**
 * @var yii\web\View $this
 * @var common\models\RestaurantImages $model
 * @var yii\widgets\ActiveForm $form
 */
if (!$model->restaurant_id)
    $restaurant_id = $restaurant_id;
else
    $restaurant_id = $model->restaurant_id;
?>

<div class="restaurant-images-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'RestaurantImages',
                'layout' => 'horizontal',
                'enableClientValidation' => false,
                'options' => ['enctype' => 'multipart/form-data']
                    ]
    );
    ?>

    <div class="">
        <?php echo $form->errorSummary($model); ?>
        <?php $this->beginBlock('main'); ?>

        <p>

            <?= $form->field($model, 'restaurant_id')->hiddenInput(['value' => $restaurant_id])->label(FALSE); ?>
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
        <?= $form->field($model, 'title')->textInput(['maxlength' => 150]) ?>
        </p>
        <?php $this->endBlock(); ?>

        <?=
        Tabs::widget(
                [
                    'encodeLabels' => false,
                    'items' => [ [
                            'label' => 'RestaurantImages',
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
