<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Content $model
 */
$this->title = 'Content ' . $model->Title . ', ' . 'Edit';
$this->params['breadcrumbs'][] = ['label' => 'Contents', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string) $model->Title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="content-update">

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-eye-open"></span> ' . 'View', ['view', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
    </p>

    <?php
    echo $this->render('_form', [
        'model' => $model,
        'Content' => $Content
    ]);
    ?>

</div>
