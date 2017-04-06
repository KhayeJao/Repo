<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;
use mihaildev\ckeditor\CKEditor;
use diiimonn\widgets\CheckboxMultiple;
use yii\helpers\Url;

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
        <?php $this->beginBlock('main'); ?>

        <p>
            <?= Html::textInput("subject", "", ['id' => 'campaign-subject', 'placeholder' => 'Campaign Subject', 'class' => 'form-control']); ?>
            <?php
            echo CKEditor::widget([
                'name' => 'Content[content]',
                'id' => 'campaign-content',
                'editorOptions' => ['height' => '300px']
            ])
            ?>
            <?= $form->field($users, 'id')->dropDownList(yii\helpers\ArrayHelper::map(common\models\base\User::findAll(['type' => 'customer']), 'id', 'username'), ['class' => "testSelAll", 'multiple' => 'multiple', 'onchange' => "console.log($(this).children(':selected').length)"])->label("Users") ?>
        </p>
        <?php $this->endBlock(); ?>
        <?=
        Tabs::widget(
                [
                    'encodeLabels' => false,
                    'items' => [[
                    'label' => 'Campaign',
                    'content' => $this->blocks['main'],
                    'active' => true,
                        ],]
                ]
        );
        ?>
        <hr/>
        <?=
        Html::submitButton(
                '<span class="glyphicon glyphicon-check"></span> Send', [
            'id' => 'send-mail', 'class' => 'btn btn-success'
                ]
        );
        ?>
        <?=
        Html::button(
                '<span class="glyphicon glyphicon-check"></span> Send Preview', [
            'id' => 'send-preview-mail', 'class' => 'btn btn-success'
                ]
        );
        ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php $this->registerJs($this->render('_js'), \yii\web\VIEW::POS_READY); ?>