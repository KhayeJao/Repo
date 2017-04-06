<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\TableBookingSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="table-booking-search">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>

		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'user_id') ?>

		<?= $form->field($model, 'table_id') ?>

		<?= $form->field($model, 'checkin_datetime') ?>

		<?= $form->field($model, 'booking_date') ?>

		<?php // echo $form->field($model, 'comment') ?>

		<div class="form-group">
			<?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
			<?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
