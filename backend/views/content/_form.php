<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;
use mihaildev\ckeditor\CKEditor;

/**
 * @var yii\web\View $this
 * @var common\models\Content $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="content-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'Content',
                'layout' => 'horizontal',
                'enableClientValidation' => false,
                    ]
    );
    ?>

    <div class="">
        <?php echo $form->errorSummary($model); ?>
        <?php $this->beginBlock('main'); ?>

        <p>

            <?= $form->field($model, 'page_key')->hiddenInput()->label(FALSE) ?>
            <?= $form->field($model, 'Title')->textInput(['maxlength' => 255]) ?>
            <?php
            echo CKEditor::widget([
                'name' => 'Content[content]',
                'value' => $Content,
                'editorOptions' => ['height' => '500px']
            ])
            ?>
            <?= $form->field($model, 'meta_title')->textarea(['rows' => 6]) ?>
            <?= $form->field($model, 'meta_keywords')->textarea(['rows' => 6]) ?>
            <?= $form->field($model, 'meta_desctiption')->textarea(['rows' => 6]) ?>
            <?= $form->field($model, 'status')->dropDownList([ 'Active' => 'Active', 'Inactive' => 'Inactive',], ['prompt' => '']) ?>
        </p>
        <?php $this->endBlock(); ?>

        <?=
        Tabs::widget(
                [
                    'encodeLabels' => false,
                    'items' => [ [
                            'label' => 'Content',
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
