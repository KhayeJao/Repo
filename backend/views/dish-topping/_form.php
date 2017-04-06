<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;

/**
 * @var yii\web\View $this
 * @var common\models\DishTopping $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="dish-topping-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'DishTopping',
                'layout' => 'horizontal',
                'enableClientValidation' => false,
                    ]
    );
    ?>

    <div class="">
        <?php echo $form->errorSummary($model); ?>
        <?php $this->beginBlock('main'); ?>

        <p>

            <?= $form->field($model, 'topping_group_id')->hiddenInput()->label(FALSE) ?>
            <?= $form->field($model, 'topping_id')->dropDownList(yii\helpers\ArrayHelper::map(common\models\Topping::findAll(['restaurant_id' => $restaurant_id]), 'id', 'title'), ['prompt' => '-- Select --']) ?>
            <?= $form->field($model, 'price')->textInput() ?>
        </p>
        <?php $this->endBlock(); ?>

        <?=
        Tabs::widget(
                [
                    'encodeLabels' => false,
                    'items' => [ [
                            'label' => 'Dish Topping',
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
