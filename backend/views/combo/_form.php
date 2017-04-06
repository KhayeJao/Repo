<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;

/**
 * @var yii\web\View $this
 * @var common\models\Combo $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="combo-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'Combo',
                'layout' => 'horizontal',
                'enableClientValidation' => false,
                    ]
    );
    ?>

    <div class="">
        <?php echo $form->errorSummary($model); ?>
        <?php $this->beginBlock('main'); ?>

        <p>

            <?= $form->field($model, 'restaurant_id')->hiddenInput()->label(FALSE); ?>
            <?= $form->field($model, 'title')->textInput(['maxlength' => 250]) ?>
            <?= $form->field($model, 'price')->textInput() ?>
            <?= $form->field($model, 'combo_type')->dropDownList([ 'Lunch Special' => 'Lunch Special', 'Night special' => 'Night special', 'Genral' => 'Genral',], ['prompt' => '']) ?>
        </p>
        <?php $this->endBlock(); ?>

        <?=
        Tabs::widget(
                [
                    'encodeLabels' => false,
                    'items' => [ [
                            'label' => 'Combo',
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
