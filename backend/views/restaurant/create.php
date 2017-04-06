<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var common\models\Restaurant $model
*/

$this->title = 'Create';
$this->params['breadcrumbs'][] = ['label' => 'Restaurants', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="restaurant-create">

    <p class="pull-left">
        <?= Html::a('Cancel', \yii\helpers\Url::previous(), ['class' => 'btn btn-default']) ?>
    </p>
    <div class="clearfix"></div>

    <?= $this->render('_form', [
    'model' => $model,
	'MenuMaster' => $MenuMaster,
	'DishMaster' => $DishMaster,
    ]); ?>

</div>
