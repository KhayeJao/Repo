<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\RestaurantPhone $model
 */
$this->title = 'Restaurant Phone ' . $model->id . ', ' . 'Edit';
$this->params['breadcrumbs'][] = ['label' => 'Restaurant Phones', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string) $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="restaurant-phone-update">



    <?php
    echo $this->render('_form', [
        'model' => $model,
    ]);
    ?>

</div>
