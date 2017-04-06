<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;

/**
* @var yii\web\View $this
* @var common\models\Address $model
* @var yii\widgets\ActiveForm $form
*/

?>

<div class="address-form">

    <?php $form = ActiveForm::begin([
                        'id'     => 'Address',
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
			<?= $form->field($model, 'address_line_1')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'address_line_2')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'area')->textInput() ?>
			<?= $form->field($model, 'city')->textInput() ?>
			<?= $form->field($model, 'pincode')->textInput() ?>
			<?= $form->field($model, 'country')->textInput() ?>
			<?= $form->field($model, 'created_on')->textInput() ?>
        </p>
        <?php $this->endBlock(); ?>
        
        <?=
    Tabs::widget(
                 [
                   'encodeLabels' => false,
                     'items' => [ [
    'label'   => 'Address',
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
