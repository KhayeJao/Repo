<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Combo $model
 */
$this->title = 'Combo ' . $model->title . ', ' . 'Edit';
$this->params['breadcrumbs'][] = ['label' => 'Combos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string) $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="combo-update">

    <?php
    echo $this->render('_form', [
        'model' => $model,
    ]);
    ?>

</div>
