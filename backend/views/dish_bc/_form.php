<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
/**
 * @var yii\web\View $this
 * @var common\models\Dish $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="dish-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'Dish',
                'layout' => 'horizontal',
                'enableClientValidation' => TRUE,
                    ]
    );
    ?>

    <div class="">
        <?php echo $form->errorSummary($model); ?>
        <?php $this->beginBlock('main'); ?>

        <p>

            <?= $form->field($model, 'restaurant_id')->hiddenInput()->label(FALSE) ?>
            <?php // $form->field($model, 'menu_id')->textInput() ?>

            <?php
            echo $form->field($model, 'menu_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(\common\models\base\Menu::findAll(['restaurant_id' => $model->restaurant_id]), 'id', 'title'),
                'options' => ['placeholder' => 'Select a meun ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>

            <?= $form->field($model, 'title')->textInput(['maxlength' => 250])?>
            <?= $form->field($model, 'description')->textarea(['maxlength' => 700]) ?>
            <?= $form->field($model, 'ingredients')->textInput(['maxlength' => 700])->hint("Ingredients separated by comma.") ?>
            <?= $form->field($model, 'price')->textInput() ?>
            <?php // $form->field($model, 'discount')->textInput()->hint("Enter 0 if you don't wish to provide discount for this dish.(Discount is in %)") ?>
            <?= $form->field($model, 'status')->dropDownList([ 'Active' => 'Active', 'Inactive' => 'Inactive',], ['prompt' => ''])->hint("Status with 'Inactive' will not show in website or application") ?>
            <?php
            if($model->isNewRecord){
                echo $form->field($model, 'is_deleted')->hiddenInput(['value' => 'No'])->label(FALSE);
            }
            ?>
        </p>
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
