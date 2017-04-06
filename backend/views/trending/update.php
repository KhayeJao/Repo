<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Trending $model
 */

$this->title = 'Trending ' . $model->title . ', ' . 'Edit';
$this->params['breadcrumbs'][] = ['label' => 'Trending', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="cuisine-update">
	<?php echo $this->render('_form', [
		'model' => $model,
	]); ?>

</div>
