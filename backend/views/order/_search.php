<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\OrderSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="order-search">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>

		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'user_id') ?>

		<?= $form->field($model, 'restaurant_id') ?>

		<?= $form->field($model, 'order_unique_id') ?>

		<?= $form->field($model, 'affiliate_order_id') ?>

		<?php // echo $form->field($model, 'user_full_name') ?>

		<?php // echo $form->field($model, 'mobile') ?>

		<?php // echo $form->field($model, 'email') ?>

		<?php // echo $form->field($model, 'address_line_1') ?>

		<?php // echo $form->field($model, 'address_line_2') ?>

		<?php // echo $form->field($model, 'area') ?>

		<?php // echo $form->field($model, 'city') ?>

		<?php // echo $form->field($model, 'pincode') ?>

		<?php // echo $form->field($model, 'delivery_time') ?>

		<?php // echo $form->field($model, 'coupon_code') ?>

		<?php // echo $form->field($model, 'discount_amount') ?>

		<?php // echo $form->field($model, 'discount_text') ?>

		<?php // echo $form->field($model, 'order_items') ?>

		<?php // echo $form->field($model, 'tax') ?>

		<?php // echo $form->field($model, 'tax_text') ?>

		<?php // echo $form->field($model, 'vat') ?>

		<?php // echo $form->field($model, 'vat_text') ?>

		<?php // echo $form->field($model, 'service_charge') ?>

		<?php // echo $form->field($model, 'service_charge_text') ?>

		<?php // echo $form->field($model, 'comment') ?>

		<?php // echo $form->field($model, 'booking_time') ?>

		<?php // echo $form->field($model, 'order_status_change_datetime') ?>

		<?php // echo $form->field($model, 'order_status_change_reason') ?>

		<?php // echo $form->field($model, 'order_ip') ?>

		<?php // echo $form->field($model, 'status') ?>

		<div class="form-group">
			<?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
			<?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
