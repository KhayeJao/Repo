<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var common\models\UserSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="user-search">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>

		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'username') ?>

		<?= $form->field($model, 'email') ?>

		<?= $form->field($model, 'first_name') ?>

		<?= $form->field($model, 'last_name') ?>

		<?php // echo $form->field($model, 'mobile_no') ?>

		<?php // echo $form->field($model, 'fb_id') ?>

		<?php // echo $form->field($model, 'fb_profile') ?>

		<?php // echo $form->field($model, 'mobile_v_code') ?>

		<?php // echo $form->field($model, 'is_mobile_verified') ?>

		<?php // echo $form->field($model, 'is_email_verified') ?>

		<?php // echo $form->field($model, 'type') ?>

		<?php // echo $form->field($model, 'auth_key') ?>

		<?php // echo $form->field($model, 'password_hash') ?>

		<?php // echo $form->field($model, 'password_reset_token') ?>

		<?php // echo $form->field($model, 'status') ?>

		<?php // echo $form->field($model, 'created_at') ?>

		<?php // echo $form->field($model, 'updated_at') ?>

		<?php // echo $form->field($model, 'last_login_ip') ?>

		<div class="form-group">
			<?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
			<?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
