<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var common\models\Table $model
 * @var yii\widgets\ActiveForm $form
 */
function getRandomTableName() {
    $chars = "0123456789";
    $res = "";
    for ($i = 0; $i < 5; $i++) {
        $res .= $chars[mt_rand(0, strlen($chars) - 1)];
    }
    return $res;
}
?>

<div class="table-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'Table',
                'layout' => 'horizontal',
                'enableClientValidation' => TRUE,
                    ]
    );
    ?>

    <div class="">
        <?php echo $form->errorSummary($model); ?>
        <?php $this->beginBlock('main'); ?>

        <p>


            <?=
            $form->field($model, 'restaurant_id')->hiddenInput()->label(FALSE);
            ?>
            <?= $form->field($model, 'no_of_seats')->dropDownList(['2' => '2', '4' => '4', '6' => '6', '8' => '8']) ?>
            <?= $form->field($model, 'price')->textInput() ?>
            <?= $form->field($model, 'table_id')->textInput(['value' => ($model->isNewRecord ? getRandomTableName() : $model->table_id)])->hint("Table refrence for customer"); ?>
            <?= $form->field($model, 'status')->hiddenInput(['value' => ($model->isNewRecord ? 'Available' : $model->status)])->label(FALSE); ?>
        </p>
        <?php $this->endBlock(); ?>

        <?=
        Tabs::widget(
                [
                    'encodeLabels' => false,
                    'items' => [ [
                            'label' => 'Table',
                            'content' => $this->blocks['main'],
                            'active' => true,
                        ],]
                ]
        );
        ?>
        <hr/>

        <?=
        Html::submitButton(
                '<span class="glyphicon glyphicon-check"></span> ' . ($model->isNewRecord ? 'Create' : 'Save'), [
            'id' => 'save-' . $model->formName(),
            'class' => 'btn btn-success'
                ]
        );
        ?>


        <?php ActiveForm::end(); ?>

    </div>

</div>
