<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;

/**
* @var yii\web\View $this
* @var common\models\Order $model
* @var yii\widgets\ActiveForm $form
*/

?>

<div class="order-form">

    <?php $form = ActiveForm::begin([
                        'id'     => 'Order',
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
			<?= $form->field($model, 'order_unique_id')->textInput(['maxlength' => 30]) ?>
			<?= $form->field($model, 'user_full_name')->textInput(['maxlength' => 400]) ?>
			<?= $form->field($model, 'mobile')->textInput(['maxlength' => 15]) ?>
			<?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>
			<?= $form->field($model, 'address_line_1')->textInput(['maxlength' => 100]) ?>
			<?= $form->field($model, 'address_line_2')->textInput(['maxlength' => 100]) ?>
			<?= $form->field($model, 'area')->textInput(['maxlength' => 50]) ?>
			<?= $form->field($model, 'city')->textInput(['maxlength' => 50]) ?>
			<?= $form->field($model, 'pincode')->textInput(['maxlength' => 50]) ?>
			<?= $form->field($model, 'delivery_time')->textInput() ?>
			<?= $form->field($model, 'discount_amount')->textInput() ?>
			<?= $form->field($model, 'order_items')->textInput() ?>
			<?= $form->field($model, 'tax')->textInput() ?>
			<?= $form->field($model, 'tax_text')->textInput(['maxlength' => 255]) ?>
			<?= $form->field($model, 'vat')->textInput() ?>
			<?= $form->field($model, 'vat_text')->textInput(['maxlength' => 255]) ?>
			<?= $form->field($model, 'service_charge')->textInput() ?>
			<?= $form->field($model, 'service_charge_text')->textInput(['maxlength' => 255]) ?>
			<?= $form->field($model, 'booking_time')->textInput() ?>
			<?= $form->field($model, 'order_ip')->textInput() ?>
			<?= $form->field($model, 'status')->dropDownList([ 'Placed' => 'Placed', 'Approved' => 'Approved', 'Rejected' => 'Rejected', 'Completed' => 'Completed', ], ['prompt' => '']) ?>
			<?= $form->field($model, 'order_status_change_datetime')->textInput() ?>
			<?= $form->field($model, 'affiliate_order_id')->textInput(['maxlength' => 30]) ?>
			<?= $form->field($model, 'discount_text')->textInput(['maxlength' => 255]) ?>
			<?= $form->field($model, 'coupon_code')->textInput(['maxlength' => 100]) ?>
			<?= $form->field($model, 'comment')->textInput(['maxlength' => 700]) ?>
			<?= $form->field($model, 'order_status_change_reason')->textInput(['maxlength' => 300]) ?>
        </p>
        <?php $this->endBlock(); ?>
        
        <?=
    Tabs::widget(
                 [
                   'encodeLabels' => false,
                     'items' => [ [
    'label'   => 'Order',
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
