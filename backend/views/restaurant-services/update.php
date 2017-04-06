<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var common\models\RestaurantServices $model
 */
$this->title = 'Restaurant Services ' . $model->id . ', ' . 'Edit';
$this->params['breadcrumbs'][] = ['label' => 'Restaurant Services', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string) $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Edit';
$service = ArrayHelper::map(common\models\base\RestaurantServices::findAll(['restaurant_id' => $restaurant_id]), 'service_id', 'service_id');
?>
<div class="restaurant-services-update">

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-eye-open"></span> ' . 'View', ['view', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
    </p>

    <?php
    echo $this->render('_form', [
        'model' => $model,
        'service' => $service,
        'restaurant_id' => $restaurant_id,
    ]);
    ?>

</div>
