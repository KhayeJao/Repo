<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;

/**
 * @var yii\web\View $this
 * @var common\models\ComboDish $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="combo-dish-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'ComboDish',
                'layout' => 'horizontal',
                'enableClientValidation' => false,
                    ]
    );
    ?>

    <div class="">
        <?php echo $form->errorSummary($model); ?>
        <?php $this->beginBlock('main'); ?>

        <p>

            <?= $form->field($model, 'combo_id')->hiddenInput()->label(FALSE); ?>
            <?= $form->field($model, 'dish_id')->dropDownList(\yii\helpers\ArrayHelper::map(common\models\Dish::find()->all(), 'id', 'title'), ["prompt" => "-- Select Dish --"]) ?>
            <?= $form->field($model, 'dish_qty')->textInput() ?>
        </p>
        <?php $this->endBlock(); ?>

        <?=
        Tabs::widget(
                [
                    'encodeLabels' => false,
                    'items' => [ [
                            'label' => 'ComboDish',
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
