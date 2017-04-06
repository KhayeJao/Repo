<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;

/**
 * @var yii\web\View $this
 * @var common\models\base\User $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="user-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'User',
                'layout' => 'horizontal',
                'enableClientValidation' => TRUE,
                    ]
    );
    ?>

    <div class="">
        <?php echo $form->errorSummary($model); ?>
        <?php $this->beginBlock('main'); ?>

        <p>

            <?= $form->field($model, 'username')->textInput(['maxlength' => 255]) ?>
            <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <?= $form->field($model, 'first_name')->textInput(['maxlength' => 50]) ?>
            <?= $form->field($model, 'last_name')->textInput(['maxlength' => 50]) ?>
            <?= $form->field($model, 'mobile_no')->textInput(['maxlength' => 15]) ?>
            <?= $form->field($model, 'fb_id')->textInput(['maxlength' => 255]) ?>
            <?= $form->field($model, 'fb_profile')->textInput(['maxlength' => 255]) ?>
            <?= $form->field($model, 'mobile_v_code')->textInput(['maxlength' => 10]) ?>
            <?= $form->field($model, 'is_mobile_verified')->dropDownList([ 'No' => 'No', 'Yes' => 'Yes',], ['prompt' => '']) ?>
            <?= $form->field($model, 'is_email_verified')->dropDownList([ 'No' => 'No', 'Yes' => 'Yes',], ['prompt' => '']) ?>
            <?= $form->field($model, 'type')->dropDownList([ 'customer' => 'Customer', 'restaurant' => 'Restaurant', 'telecaller' => 'Telecaller', 'admin' => 'Admin'], ['prompt' => '']) ?>
            <?php // $form->field($model, 'auth_key')->textInput(['maxlength' => 32]) ?>
            <?php // $form->field($model, 'password_hash')->textInput(['maxlength' => 255]) ?>
            <?php // $form->field($model, 'created_at')->textInput() ?>
            <?php // $form->field($model, 'updated_at')->textInput() ?>
            <?= $form->field($model, 'last_login_ip')->textInput(['maxlength' => 20]) ?>
            <?php // $form->field($model, 'status')->textInput() ?>
            <?php // $form->field($model, 'password_reset_token')->textInput(['maxlength' => 255]) ?>
        </p>
        <?php $this->endBlock(); ?>

        <?=
        Tabs::widget(
                [
                    'encodeLabels' => false,
                    'items' => [ [
                            'label' => 'User',
                            'content' => $this->blocks['main'],
                            'active' => true,
                        ],]
                ]
        );
        ?>
        <hr/>

        <?=
        Html::submitButton(
                '<span class="glyphicon glyphicon-check"></span> Save', [
            'id' => 'save-' . $model->formName(),
            'class' => 'btn btn-success'
                ]
        );
        ?>


        <?php ActiveForm::end(); ?>

    </div>

</div>
