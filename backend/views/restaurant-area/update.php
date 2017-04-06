<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\RestaurantArea $model
 */
$this->title = 'Restaurant Area ' . $model->id . ', ' . 'Edit';
$this->params['breadcrumbs'][] = ['label' => 'Restaurant Areas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string) $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="restaurant-area-update">


    <?php
    echo $this->render('_form', [
        'model' => $model,
    ]);
    ?>

</div>
