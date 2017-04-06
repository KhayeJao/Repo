<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var common\models\DishTopping $model
*/

$this->title = 'Create';
$this->params['breadcrumbs'][] = ['label' => 'Dish Toppings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dish-topping-create">

    <p class="pull-left">
        <?= Html::a('Cancel', \yii\helpers\Url::previous(), ['class' => 'btn btn-default']) ?>
    </p>
    <div class="clearfix"></div>

    <?= $this->render('_form', [
    'model' => $model,
        'restaurant_id' => $restaurant_id,
    ]); ?>

</div>
