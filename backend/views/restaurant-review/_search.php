<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\RestaurantReviewSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="restaurant-review-search">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>

		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'user_id') ?>

		<?= $form->field($model, 'restaurant_id') ?>

		<?= $form->field($model, 'title') ?>

		<?= $form->field($model, 'comment') ?>

		<?php // echo $form->field($model, 'rate') ?>

		<?php // echo $form->field($model, 'created_on') ?>

		<?php // echo $form->field($model, 'status') ?>

		<div class="form-group">
			<?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
			<?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
