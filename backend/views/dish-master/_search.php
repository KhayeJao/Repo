<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\DishesSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="dish-search">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>

		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'menu_id') ?>

		<?= $form->field($model, 'restaurant_id') ?>

		<?= $form->field($model, 'title') ?>

		<?= $form->field($model, 'description') ?>

		<?php // echo $form->field($model, 'ingredients') ?>

		<?php // echo $form->field($model, 'price') ?>

		<?php // echo $form->field($model, 'discount') ?>

		<?php // echo $form->field($model, 'is_deleted') ?>

		<?php // echo $form->field($model, 'status') ?>

		<div class="form-group">
			<?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
			<?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
