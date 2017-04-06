<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var common\models\RestaurantCuisine $model
 */
$this->title = 'Restaurant Cuisine ' . $model->id . ', ' . 'Edit';
$this->params['breadcrumbs'][] = ['label' => 'Restaurant Cuisines', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string) $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Edit';
$cuisine = ArrayHelper::map(common\models\base\RestaurantCuisine::findAll(['restaurant_id' => $restaurant_id]), 'cuisine_id', 'cuisine_id');
?>
<div class="restaurant-cuisine-update">

    <?php
    echo $this->render('_form', [
        'model' => $model,
        'cuisine' => $cuisine,
        'restaurant_id' => $restaurant_id,
    ]);
    ?>

</div>
