<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var common\models\ComboDish $model
 */
$this->title = 'Combo Dish ' . $model->id . ', ' . 'Edit';
$this->params['breadcrumbs'][] = ['label' => 'Combo Dishes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string) $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="combo-dish-update">

    <?php
    echo $this->render('_form', [
        'model' => $model,
    ]);
    ?>

</div>
