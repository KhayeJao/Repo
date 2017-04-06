<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var common\models\RestaurantCuisine $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="restaurant-cuisine-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'Restaurant Cuisine',
                'layout' => 'horizontal',
                'enableClientValidation' => false,
                    ]
    );
    ?>

    <div class="">
        <?php echo $form->errorSummary($model); ?>
        <?php $this->beginBlock('main'); ?>

        <p>

            <?= $form->field($model, 'restaurant_id')->hiddenInput(['value' => $restaurant_id])->label(FALSE); ?>


        <div class="form-group field-restaurantcuisine-cuisine_id required">
            <?php echo '<label class="control-label col-sm-3" for="restaurantcuisine-cuisine_id">Cuisine</label>'; ?>
            <div class="col-sm-6">
                <?php
                echo Select2::widget([
                    'name' => 'RestaurantCuisine[cuisine_id]',
                    'data' => ArrayHelper::map(common\models\base\Cuisine::find()->all(), 'id', 'title'),
                    'value' => $cuisine,
                    'options' => [
                        'placeholder' => 'Select cuisine ...',
                        'id' => 'restaurantcuisine-cuisine_id',
                        'multiple' => true
                    ],
                ]);
                ?>
                <div class="help-block help-block-error "></div>
            </div>
        </div>
        </p>
        <?php $this->endBlock(); ?>

        <?=
        Tabs::widget(
                [
                    'encodeLabels' => false,
                    'items' => [ [
                            'label' => 'Restaurant Cuisine',
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
