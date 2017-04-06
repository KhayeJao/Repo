<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var common\models\RestaurantServices $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="restaurant-services-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'Restaurant Services',
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
            <?php echo '<label class="control-label col-sm-3" for="restaurantcuisine-cuisine_id">Services</label>'; ?>
            <div class="col-sm-6">
                <?php
                echo Select2::widget([
                    'name' => 'RestaurantServices[service_id]',
                    'data' => ArrayHelper::map(common\models\base\Service::find()->all(), 'id', 'title'),
                    'value' => $service,
                    'options' => [
                        'placeholder' => 'Select services ...',
                        'id' => 'restaurantservices-service_id',
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
                            'label' => 'Restaurant Services',
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
