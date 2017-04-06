<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Cuisine $model
 */

//$this->first_name = 'Delivery Boy ' . $model->first_name . ', ' . 'Edit';
$this->params['breadcrumbs'][] = ['label' => 'Delivery Boy', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->first_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="cuisine-update">

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-eye-open"></span> ' . 'View', ['view', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
    </p>

	<?php echo $this->render('_form', [
		'model' => $model,
	]); ?>

</div>
