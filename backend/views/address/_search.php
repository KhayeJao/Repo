<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\AddressSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="address-search">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>

		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'user_id') ?>

		<?= $form->field($model, 'address_line_1') ?>

		<?= $form->field($model, 'address_line_2') ?>

		<?= $form->field($model, 'area') ?>

		<?php // echo $form->field($model, 'city') ?>

		<?php // echo $form->field($model, 'pincode') ?>

		<?php // echo $form->field($model, 'country') ?>

		<?php // echo $form->field($model, 'created_on') ?>

		<div class="form-group">
			<?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
			<?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
