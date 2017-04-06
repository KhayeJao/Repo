<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\MenuSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="menu-search">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>

		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'parent_id') ?>

		<?= $form->field($model, 'restaurant_id') ?>

		<?= $form->field($model, 'title') ?>

		<?= $form->field($model, 'excerpt') ?>

		<?php // echo $form->field($model, 'image') ?>

		<?php // echo $form->field($model, 'discount') ?>

		<div class="form-group">
			<?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
			<?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
