<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Settings $model
 */
$this->title = 'Settings ' . $model->title . ', ' . 'Edit';
$this->params['breadcrumbs'][] = ['label' => 'Settings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string) $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="settings-update">


    <?php
    echo $this->render('_form', [
        'model' => $model,
    ]);
    ?>

</div>
