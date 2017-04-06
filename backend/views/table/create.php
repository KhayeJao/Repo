<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var common\models\Table $model
 */
$this->title = 'Create';
$this->params['breadcrumbs'][] = ['label' => 'Tables', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="table-create">

    <p class="pull-left">
        <?= Html::a('Cancel', \yii\helpers\Url::previous(), ['class' => 'btn btn-default']) ?>
    </p>
    <div class="clearfix"></div>

    <div class="table-form">

        <?php
        $form = ActiveForm::begin([
                    'id' => 'Table',
                    'layout' => 'horizontal',
                    'enableClientValidation' => TRUE,
                        ]
        );
        ?>

        <div class="" >
            <?php echo $form->errorSummary($model); ?>
            <?php $this->beginBlock('main'); ?>

            <p>
                <?=
                $form->field($model, 'restaurant_id')->hiddenInput()->label(FALSE);
                ?>
                <?= $form->field($model, 'no_of_seats')->dropDownList(['2' => '2', '4' => '4', '6' => '6', '8' => '8']) ?>
                <?= $form->field($model, 'price')->textInput() ?>
                <?php // $form->field($model, 'table_id')->textInput(['value' => ($model->isNewRecord ? getRandomTableName() : $model->table_id)])->hint("Table refrence for customer"); ?>
                <?php $form->field($model, 'status')->hiddenInput(['value' => 'Available'])->label(FALSE); ?>
            <div class="form-group required">
                <label class="control-label col-sm-3" for="no_of_tables">No of tables</label>
                <div class="col-sm-6">
                    <?= Html::dropDownList('no_of_tables', '', ['1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10', '11' => '11', '12' => '12'], ['id' => 'no_of_tables', 'class' => 'form-control']) ?>
                </div>

            </div>

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

</div>
