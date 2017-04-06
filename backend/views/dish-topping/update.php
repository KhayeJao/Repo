<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\DishTopping $model
 */

$this->title = 'Dish Topping ' . $model->id . ', ' . 'Edit';
$this->params['breadcrumbs'][] = ['label' => 'Dish Toppings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="dish-topping-update">

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-eye-open"></span> ' . 'View', ['view', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
    </p>

	<?php echo $this->render('_form', [
		'model' => $model,
            'restaurant_id' => $restaurant_id,
	]); ?>

</div>
