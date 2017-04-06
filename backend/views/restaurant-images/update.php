<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\RestaurantImages $model
 */
$this->title = 'Restaurant Images ' . $model->title . ', ' . 'Edit';
$this->params['breadcrumbs'][] = ['label' => 'Restaurant Images', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string) $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="restaurant-images-update">

    <?php
    echo $this->render('_form', [
        'model' => $model,
    ]);
    ?>

</div>
