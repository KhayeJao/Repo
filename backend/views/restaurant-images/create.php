<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\RestaurantImages $model
 */
$this->title = 'Create';
$this->params['breadcrumbs'][] = ['label' => 'Restaurant Images', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="restaurant-images-create">

    <p class="pull-left">
        <?= Html::a('Cancel', \yii\helpers\Url::previous(), ['class' => 'btn btn-default']) ?>
    </p>
    <div class="clearfix"></div>

    <?=
    $this->render('_form', [
        'model' => $model,
        'restaurant_id' => $restaurant_id,
    ]);
    ?>

</div>
