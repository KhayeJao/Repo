<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\RestaurantSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="restaurant-search">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>

		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'user_id') ?>

		<?= $form->field($model, 'title') ?>

		<?= $form->field($model, 'slogan') ?>

		<?= $form->field($model, 'address') ?>

		<?php // echo $form->field($model, 'area') ?>

		<?php // echo $form->field($model, 'city') ?>

		<?php // echo $form->field($model, 'latitude') ?>

		<?php // echo $form->field($model, 'longitude') ?>

		<?php // echo $form->field($model, 'min_amount') ?>

		<?php // echo $form->field($model, 'logo') ?>

		<?php // echo $form->field($model, 'delivery_network') ?>

		<?php // echo $form->field($model, 'open_datetime_1') ?>

		<?php // echo $form->field($model, 'close_datetime_1') ?>

		<?php // echo $form->field($model, 'open_datetime_2') ?>

		<?php // echo $form->field($model, 'close_datetime_2') ?>

		<?php // echo $form->field($model, 'tax') ?>

		<?php // echo $form->field($model, 'vat') ?>

		<?php // echo $form->field($model, 'service_charge') ?>

		<?php // echo $form->field($model, 'scharge_type') ?>

		<?php // echo $form->field($model, 'kj_share') ?>

		<?php // echo $form->field($model, 'prior_table_booking_time') ?>

		<?php // echo $form->field($model, 'table_slot_time') ?>

		<?php // echo $form->field($model, 'who_delivers') ?>

		<?php // echo $form->field($model, 'meta_keywords') ?>

		<?php // echo $form->field($model, 'meta_description') ?>

		<?php // echo $form->field($model, 'coupon_text') ?>

		<?php // echo $form->field($model, 'avg_rating') ?>

		<?php // echo $form->field($model, 'is_featured') ?>

		<?php // echo $form->field($model, 'featured_image') ?>

		<?php // echo $form->field($model, 'status') ?>

		<div class="form-group">
			<?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
			<?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
