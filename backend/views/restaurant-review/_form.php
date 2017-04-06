<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;

/**
* @var yii\web\View $this
* @var common\models\RestaurantReview $model
* @var yii\widgets\ActiveForm $form
*/

?>

<div class="restaurant-review-form">

    <?php $form = ActiveForm::begin([
                        'id'     => 'RestaurantReview',
                        'layout' => 'horizontal',
                        'enableClientValidation' => false,
                    ]
                );
    ?>

    <div class="">
        <?php echo $form->errorSummary($model); ?>
        <?php $this->beginBlock('main'); ?>

        <p>
            
			<?= $form->field($model, 'user_id')->textInput() ?>
			<?= $form->field($model, 'restaurant_id')->textInput() ?>
			<?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>
			<?= $form->field($model, 'comment')->textInput(['maxlength' => 750]) ?>
			<?= $form->field($model, 'rate')->textInput() ?>
			<?= $form->field($model, 'created_on')->textInput() ?>
			<?= $form->field($model, 'status')->dropDownList([ 'Active' => 'Active', 'Inactive' => 'Inactive', ], ['prompt' => '']) ?>
        </p>
        <?php $this->endBlock(); ?>
        
        <?=
    Tabs::widget(
                 [
                   'encodeLabels' => false,
                     'items' => [ [
    'label'   => 'RestaurantReview',
    'content' => $this->blocks['main'],
    'active'  => true,
], ]
                 ]
    );
    ?>
        <hr/>

        <?= Html::submitButton(
                '<span class="glyphicon glyphicon-check"></span> ' . ($model->isNewRecord
                            ? 'Create' : 'Save'),
                [
                    'id'    => 'save-' . $model->formName(),
                    'class' => 'btn btn-success'
                ]
            );
        ?>


        <?php ActiveForm::end(); ?>

    </div>

</div>
