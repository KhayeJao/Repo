<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\ContentSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="content-search">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>

		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'page_key') ?>

		<?= $form->field($model, 'Title') ?>

		<?= $form->field($model, 'content') ?>

		<?= $form->field($model, 'meta_title') ?>

		<?php // echo $form->field($model, 'meta_keywords') ?>

		<?php // echo $form->field($model, 'meta_desctiption') ?>

		<?php // echo $form->field($model, 'status') ?>

		<div class="form-group">
			<?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
			<?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
